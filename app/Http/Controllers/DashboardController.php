<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\SuratKeputusan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * DASHBOARD UTAMA
     */
    public function index(Request $request): View
    {
        $role = strtolower(trim($request->user()->role));

        if ($role === 'admin') {
            return $this->adminDashboard($request);
        }

        if ($role === 'staf_spi') {
            return $this->stafDashboard($request);
        }

        abort(403, 'Anda tidak memiliki akses ke dashboard.');
    }

    /**
     * DASHBOARD ADMIN
     */
    private function adminDashboard(Request $request): View
    {
        $search = trim($request->input('search', ''));

        // ==========================================
        // STATISTIK DOKUMEN
        // ==========================================

        $totalDokumen = Dokumen::count();

        $dokumenFinal = Dokumen::where('status', 'final')->count();

        $dokumenDraft = Dokumen::where('status', 'draft')->count();

        $dokumenDiarsipkan = Dokumen::where('status', 'arsip')->count();

        $dokumenBulanIni = Dokumen::whereMonth(
            'created_at',
            now()->month
        )->whereYear(
            'created_at',
            now()->year
        )->count();

        // ==========================================
        // STATISTIK SURAT KEPUTUSAN
        // ==========================================

        $totalSk = SuratKeputusan::count();

        $skArsip = SuratKeputusan::where(
            'status',
            'arsip'
        )->count();

        // ==========================================
        // KATEGORI DOKUMEN
        // ==========================================

        $kategori = KategoriDokumen::query()
            ->withCount('dokumen')
            ->orderByDesc('dokumen_count')
            ->get();

        $kategoriTerbanyak = max(
            1,
            (int) $kategori->max('dokumen_count')
        );

        // ==========================================
        // DOKUMEN TERBARU
        // ==========================================

        $dokumenQuery = Dokumen::query()
            ->with(['kategori', 'uploader']);

        if ($search !== '') {
            $dokumenQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%");
            });
        }

        $dokumenTerbaru = $dokumenQuery
            ->latest('created_at')
            ->limit(5)
            ->get();

        // ==========================================
        // SURAT KEPUTUSAN TERBARU
        // ==========================================

        $skQuery = SuratKeputusan::query()
            ->with('uploader');

        if ($search !== '') {
            $skQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_sk', 'like', "%{$search}%");
            });
        }

        $skTerbaru = $skQuery
            ->latest('created_at')
            ->limit(5)
            ->get();

        // ==========================================
        // GRAFIK UNGGAHAN 12 BULAN
        // ==========================================

        $uploadsPerBulan = collect();

        $awalPeriode = now()->startOfMonth()->subMonths(11);

        for ($i = 0; $i < 12; $i++) {
            $bulan = $awalPeriode->copy()->addMonths($i);

            $awalBulan = $bulan->copy()->startOfMonth();
            $akhirBulan = $bulan->copy()->endOfMonth();

            $jumlahDokumen = Dokumen::whereBetween(
                'created_at',
                [$awalBulan, $akhirBulan]
            )->count();

            $jumlahSK = SuratKeputusan::whereBetween(
                'created_at',
                [$awalBulan, $akhirBulan]
            )->count();

            $uploadsPerBulan->push([
                'label' => $bulan->locale('id')->translatedFormat('M'),
                'total' => $jumlahDokumen + $jumlahSK,
            ]);
        }

        $totalUnggahan = $uploadsPerBulan->sum('total');

        $rataRataUnggahan = round(
            $totalUnggahan / 12,
            1
        );

        // ==========================================
        // TAMPILKAN DASHBOARD ADMIN
        // ==========================================

        return view('dashboard.index', [
            'totalDokumen' => $totalDokumen,
            'dokumenFinal' => $dokumenFinal,
            'dokumenDraft' => $dokumenDraft,
            'dokumenDiarsipkan' => $dokumenDiarsipkan,

            'totalSk' => $totalSk,
            'skArsip' => $skArsip,

            'dokumenBulanIni' => $dokumenBulanIni,

            'kategori' => $kategori,
            'kategoriTerbanyak' => $kategoriTerbanyak,

            'dokumenTerbaru' => $dokumenTerbaru,
            'skTerbaru' => $skTerbaru,

            'uploadsPerBulan' => $uploadsPerBulan,
            'rataRataUnggahan' => $rataRataUnggahan,

            'search' => $search,
        ]);
    }

    /**
     * DASHBOARD STAF SPI
     */
    public function staf(Request $request): View
    {
        return $this->stafDashboard($request);
    }

    /**
     * DATA DASHBOARD STAF SPI
     */
    private function stafDashboard(Request $request): View
    {
        $user = $request->user();

        // ==========================================
        // STATISTIK DOKUMEN
        // ==========================================

        $dokumenTerverifikasi = Dokumen::where(
            'status',
            'final'
        )->count();

        $dokumenMenunggu = Dokumen::where(
            'status',
            'draft'
        )->count();

        // ==========================================
        // STATISTIK SURAT KEPUTUSAN
        // ==========================================

        $skTerdaftar = SuratKeputusan::count();

        $skArsip = SuratKeputusan::where(
            'status',
            'arsip'
        )->count();

        // ==========================================
        // TOTAL ARSIP
        // ==========================================

        $dokumenArsip = Dokumen::where(
            'status',
            'arsip'
        )->count();

        $totalArsip = $dokumenArsip + $skArsip;

        // ==========================================
        // DOKUMEN MENUNGGU
        // ==========================================

        $daftarMenunggu = Dokumen::query()
            ->with(['kategori', 'uploader'])
            ->where('status', 'draft')
            ->latest('created_at')
            ->limit(5)
            ->get();

        // ==========================================
        // AKTIVITAS STAF
        // ==========================================

        $aktivitasSaya = LogAktivitas::query()
            ->with(['dokumen', 'suratKeputusan'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('dashboard.staf', [
            'dokumenTerverifikasi' => $dokumenTerverifikasi,
            'dokumenMenunggu' => $dokumenMenunggu,
            'skTerdaftar' => $skTerdaftar,
            'totalArsip' => $totalArsip,
            'daftarMenunggu' => $daftarMenunggu,
            'aktivitasSaya' => $aktivitasSaya,
        ]);
    }
}
