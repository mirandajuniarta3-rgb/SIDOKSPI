<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\SuratKeputusan;
use App\Models\KategoriDokumen;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik dokumen
        $totalDokumen = Dokumen::where('status', 'aktif')->count();

        // Statistik surat keputusan
        $totalSK = SuratKeputusan::where('status', 'aktif')->count();

        // Statistik kategori
        $totalKategori = KategoriDokumen::count();

        // Statistik pengguna
        $totalPengguna = User::count();

        // Lima dokumen terbaru
        $dokumenTerbaru = Dokumen::where('status', 'aktif')
            ->latest()
            ->take(5)
            ->get();

        // Lima surat keputusan terbaru
        $skTerbaru = SuratKeputusan::where('status', 'aktif')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalDokumen',
            'totalSK',
            'totalKategori',
            'totalPengguna',
            'dokumenTerbaru',
            'skTerbaru'
        ));
    }
}