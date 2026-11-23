<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogAktivitasController extends Controller
{
    // ==========================================
    // HALAMAN LOG AKTIVITAS
    // ==========================================
    public function index(Request $request): View
    {
        // LOG AKTIVITAS KHUSUS ADMIN
        abort_unless(
            $request->user()->role === 'admin',
            403
        );

        // ==========================================
        // VALIDASI FILTER
        // ==========================================
        $filters = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'aksi' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        // ==========================================
        // QUERY UTAMA
        // ==========================================
        $query = LogAktivitas::with([
            'user',
            'dokumen',
            'suratKeputusan',
        ]);

        // FILTER PENGGUNA
        $query->when(
            !empty($filters['user_id']),
            function (Builder $query) use ($filters) {
                $query->where('user_id', $filters['user_id']);
            }
        );

        // FILTER AKSI
        $query->when(
            !empty($filters['aksi']),
            function (Builder $query) use ($filters) {
                $query->where('aksi', $filters['aksi']);
            }
        );

        // FILTER TANGGAL MULAI
        $query->when(
            !empty($filters['tanggal_mulai']),
            function (Builder $query) use ($filters) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $filters['tanggal_mulai']
                );
            }
        );

        // FILTER TANGGAL AKHIR
        $query->when(
            !empty($filters['tanggal_akhir']),
            function (Builder $query) use ($filters) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $filters['tanggal_akhir']
                );
            }
        );

        // ==========================================
        // PENCARIAN
        // ==========================================
        $search = trim($filters['search'] ?? '');

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('aksi', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $user) use ($search) {
                        $user->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // ==========================================
        // STATISTIK LOG AKTIVITAS
        // ==========================================

        // TOTAL SELURUH AKTIVITAS
        $totalLog = LogAktivitas::count();

        // AKTIVITAS HARI INI
        $aktivitasHariIni = LogAktivitas::whereDate(
            'created_at',
            today()
        )->count();

        // PENGGUNA AKTIF HARI INI
        $penggunaAktif = LogAktivitas::whereDate(
            'created_at',
            today()
        )
            ->distinct()
            ->count('user_id');

        // AKTIVITAS DENGAN REFERENSI DOKUMEN ATAU SK
        $aktivitasDenganReferensi = LogAktivitas::where(
            function ($query) {
                $query->whereNotNull('dokumen_id')
                    ->orWhereNotNull('sk_id');
            }
        )->count();

        // ==========================================
        // DATA AKTIVITAS
        // ==========================================
        $aktivitas = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        // ==========================================
        // DAFTAR PENGGUNA
        // ==========================================
        $daftarPengguna = User::orderBy('nama_lengkap')
            ->get();

        // ==========================================
        // DAFTAR AKSI
        // ==========================================
        $daftarAksi = LogAktivitas::query()
            ->whereNotNull('aksi')
            ->distinct()
            ->orderBy('aksi')
            ->pluck('aksi');

        // ==========================================
        // TAMPILKAN HALAMAN
        // ==========================================
        return view('log-aktivitas.index', [
            'aktivitas' => $aktivitas,
            'totalLog' => $totalLog,
            'aktivitasHariIni' => $aktivitasHariIni,
            'penggunaAktif' => $penggunaAktif,
            'aktivitasDenganReferensi' => $aktivitasDenganReferensi,
            'daftarPengguna' => $daftarPengguna,
            'daftarAksi' => $daftarAksi,
            'search' => $search,
            'filters' => $filters,
        ]);
    }
}
