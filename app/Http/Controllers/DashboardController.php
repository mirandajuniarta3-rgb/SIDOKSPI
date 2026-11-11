<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\SuratKeputusan;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $querySearch = $request->query('search');
        $search = is_string($querySearch) ? mb_substr(trim($querySearch), 0, 100) : '';
        $startOfMonth = now()->startOfMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();
        $firstMonth = now()->startOfMonth()->subMonths(11);

        $totalDokumen = Dokumen::count();
        $totalSk = SuratKeputusan::count();
        $dokumenBulanIni = Dokumen::where('created_at', '>=', $startOfMonth)
            ->where('created_at', '<', $nextMonth)
            ->count();
        $dokumenDiarsipkan = Dokumen::where('status', 'arsip')->count();

        $kategori = KategoriDokumen::query()
            ->withCount('dokumen')
            ->orderByDesc('dokumen_count')
            ->orderBy('nama_kategori')
            ->get();
        $kategoriTerbanyak = max(1, (int) $kategori->max('dokumen_count'));
        $unggahanBulanan = DB::table('dokumen')
            ->select('created_at')
            ->where('created_at', '>=', $firstMonth)
            ->where('created_at', '<', $nextMonth)
            ->unionAll(
                DB::table('surat_keputusan')
                    ->select('created_at')
                    ->where('created_at', '>=', $firstMonth)
                    ->where('created_at', '<', $nextMonth)
            );
        $unggahanBulanan = DB::query()
            ->fromSub($unggahanBulanan, 'unggahan')
            ->selectRaw('SUBSTR(created_at, 1, 7) AS bulan, COUNT(*) AS total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $uploadsPerBulan = collect(
            CarbonPeriod::create($firstMonth, '1 month', now()->startOfMonth())
        )->map(function (Carbon $month) use ($unggahanBulanan) {
            $start = $month->copy()->startOfMonth();

            return [
                'label' => $start->locale('id')->translatedFormat('M'),
                'total' => (int) $unggahanBulanan->get($start->format('Y-m'), 0),
            ];
        });

        $rataRataUnggahan = (int) round($uploadsPerBulan->avg('total') ?? 0);

        $dokumenTerbaru = Dokumen::query()
            ->with('kategori')
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
            ))
            ->latest('created_at')
            ->limit(5)
            ->get();

        $skTerbaru = SuratKeputusan::query()
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_sk', 'like', "%{$search}%")
            ))
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'search' => $search,
            'totalDokumen' => $totalDokumen,
            'totalSk' => $totalSk,
            'dokumenBulanIni' => $dokumenBulanIni,
            'dokumenDiarsipkan' => $dokumenDiarsipkan,
            'kategori' => $kategori,
            'kategoriTerbanyak' => $kategoriTerbanyak,
            'uploadsPerBulan' => $uploadsPerBulan,
            'rataRataUnggahan' => $rataRataUnggahan,
            'dokumenTerbaru' => $dokumenTerbaru,
            'skTerbaru' => $skTerbaru,
        ]);
    }
}
