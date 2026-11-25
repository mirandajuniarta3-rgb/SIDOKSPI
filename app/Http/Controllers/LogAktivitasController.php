<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogAktivitasController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()?->role === 'admin',
            403
        );

        $filters = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'aksi' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => [
                'nullable',
                'date_format:Y-m-d',
                ...($request->filled('tanggal_mulai')
                    ? ['after_or_equal:tanggal_mulai']
                    : []),
            ],
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => [
                'nullable',
                'in:created_at,pengguna,aksi,referensi,ip_address',
            ],
            'direction' => ['nullable', 'in:asc,desc'],
        ]);

        $query = LogAktivitas::with([
            'user',
            'dokumen',
            'suratKeputusan',
        ]);

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['aksi'])) {
            $query->where('aksi', $filters['aksi']);
        }

        if (!empty($filters['tanggal_mulai'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $filters['tanggal_mulai']
            );
        }

        if (!empty($filters['tanggal_akhir'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $filters['tanggal_akhir']
            );
        }

        $search = trim($filters['search'] ?? '');

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('aksi', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('dokumen_id', 'like', "%{$search}%")
                    ->orWhere('sk_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $user) use ($search) {
                        $user->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';

        switch ($sort) {
            case 'pengguna':
                $query->orderBy(
                    User::select('nama_lengkap')
                        ->whereColumn(
                            'users.id',
                            'log_aktivitas.user_id'
                        )
                        ->limit(1),
                    $direction
                );
                break;

            case 'referensi':
                $query->orderByRaw(
                    'CASE
                        WHEN dokumen_id IS NOT NULL THEN 1
                        WHEN sk_id IS NOT NULL THEN 2
                        ELSE 3
                    END ' . $direction
                )
                    ->orderBy('dokumen_id', $direction)
                    ->orderBy('sk_id', $direction);
                break;

            default:
                $query->orderBy($sort, $direction);
                break;
        }

        $aktivitas = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $tableData = [
            'aktivitas' => $aktivitas,
            'sort' => $sort,
            'direction' => $direction,
            'search' => $search,
            'filters' => $filters,
        ];

        if ($request->ajax()) {
            return view(
                'log-aktivitas.partials.results',
                $tableData
            );
        }

        $totalLog = LogAktivitas::count();

        $aktivitasHariIni = LogAktivitas::whereDate(
            'created_at',
            today()
        )->count();

        $penggunaAktif = LogAktivitas::whereDate(
            'created_at',
            today()
        )
            ->distinct()
            ->count('user_id');

        $aktivitasDenganReferensi = LogAktivitas::where(
            function (Builder $query) {
                $query->whereNotNull('dokumen_id')
                    ->orWhereNotNull('sk_id');
            }
        )->count();

        $daftarPengguna = User::orderBy('nama_lengkap')
            ->get();

        $daftarAksi = LogAktivitas::query()
            ->whereNotNull('aksi')
            ->distinct()
            ->orderBy('aksi')
            ->pluck('aksi');

        return view('log-aktivitas.index', array_merge(
            $tableData,
            compact(
                'totalLog',
                'aktivitasHariIni',
                'penggunaAktif',
                'aktivitasDenganReferensi',
                'daftarPengguna',
                'daftarAksi'
            )
        ));
    }
}
