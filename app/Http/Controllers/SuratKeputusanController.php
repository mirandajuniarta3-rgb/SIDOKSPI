<?php

namespace App\Http\Controllers;

use App\Models\SuratKeputusan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratKeputusanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'status' => ['nullable', 'in:final,arsip,draft'],
        ]);
        $search = trim($filters['search'] ?? '');

        $suratKeputusan = SuratKeputusan::query()
            ->with('uploader')
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('nomor_sk', 'like', "%{$search}%")
                    ->orWhere('judul', 'like', "%{$search}%")
                    ->orWhere('tentang', 'like', "%{$search}%")
                    ->orWhereHas('uploader', fn (Builder $uploader) => $uploader
                        ->where('nama_lengkap', 'like', "%{$search}%"))
            ))
            ->when(isset($filters['tahun']), fn (Builder $query) => $query->where('tahun', $filters['tahun']))
            ->when(isset($filters['status']), fn (Builder $query) => $query->where('status', $filters['status']))
            ->orderByDesc('tanggal_penetapan')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        return view('sk.index', [
            'suratKeputusan' => $suratKeputusan,
            'totalSk' => SuratKeputusan::count(),
            'skBerlaku' => SuratKeputusan::where('status', 'final')->count(),
            'skArsip' => SuratKeputusan::where('status', 'arsip')->count(),
            'tahun' => SuratKeputusan::query()
                ->whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),
            'search' => $search,
            'selectedTahun' => $filters['tahun'] ?? '',
            'selectedStatus' => $filters['status'] ?? '',
        ]);
    }
}
