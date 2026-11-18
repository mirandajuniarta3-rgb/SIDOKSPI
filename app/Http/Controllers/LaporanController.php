<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\SuratKeputusan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;


class LaporanController extends Controller
{
    // ==========================================
    // REKAP DOKUMEN
    // ==========================================
    public function dokumen(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'status' => ['nullable', 'in:draft,final,arsip'],
        ]);

        $search = trim($filters['search'] ?? '');

        $query = Dokumen::query()
            ->with(['kategori', 'uploader'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('bidang', 'like', "%{$search}%")
                        ->orWhereHas('uploader', function (Builder $uploader) use ($search) {
                            $uploader->where('nama_lengkap', 'like', "%{$search}%");
                        });
                });
            })
            ->when(isset($filters['tahun']), function (Builder $query) use ($filters) {
                $query->where('tahun', $filters['tahun']);
            })
            ->when(isset($filters['status']), function (Builder $query) use ($filters) {
                $query->where('status', $filters['status']);
            });

        $dokumen = (clone $query)
            ->orderByDesc('tanggal_dokumen')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('laporan.dokumen', [
            'dokumen' => $dokumen,

            'totalDokumen' => Dokumen::count(),
            'dokumenFinal' => Dokumen::where('status', 'final')->count(),
            'dokumenDraft' => Dokumen::where('status', 'draft')->count(),
            'dokumenArsip' => Dokumen::where('status', 'arsip')->count(),

            'tahun' => Dokumen::whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),

            'search' => $search,
            'selectedTahun' => $filters['tahun'] ?? '',
            'selectedStatus' => $filters['status'] ?? '',
        ]);
    }

    // ==========================================
    // REKAP SURAT KEPUTUSAN
    // ==========================================
    public function sk(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'status' => ['nullable', 'in:draft,final,arsip'],
        ]);

        $search = trim($filters['search'] ?? '');

        $query = SuratKeputusan::query()
            ->with('uploader')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('nomor_sk', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tentang', 'like', "%{$search}%")
                        ->orWhereHas('uploader', function (Builder $uploader) use ($search) {
                            $uploader->where('nama_lengkap', 'like', "%{$search}%");
                        });
                });
            })
            ->when(isset($filters['tahun']), function (Builder $query) use ($filters) {
                $query->where('tahun', $filters['tahun']);
            })
            ->when(isset($filters['status']), function (Builder $query) use ($filters) {
                $query->where('status', $filters['status']);
            });

        $suratKeputusan = (clone $query)
            ->orderByDesc('tanggal_penetapan')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('laporan.sk', [
            'suratKeputusan' => $suratKeputusan,

            'totalSk' => SuratKeputusan::count(),
            'skFinal' => SuratKeputusan::where('status', 'final')->count(),
            'skDraft' => SuratKeputusan::where('status', 'draft')->count(),
            'skArsip' => SuratKeputusan::where('status', 'arsip')->count(),

            'tahun' => SuratKeputusan::whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),

            'search' => $search,
            'selectedTahun' => $filters['tahun'] ?? '',
            'selectedStatus' => $filters['status'] ?? '',
        ]);
    }
}
