<?php

namespace App\Http\Controllers;

use App\Models\SuratKeputusan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuratKeputusanController extends Controller
{
    // ==========================================
    // DAFTAR SURAT KEPUTUSAN
    // ==========================================
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . (now()->year + 1),
            ],
            'status' => ['nullable', 'in:final,arsip,draft'],
        ]);

        $search = trim($filters['search'] ?? '');

        $suratKeputusan = SuratKeputusan::query()
            ->with('uploader')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('nomor_sk', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tentang', 'like', "%{$search}%")
                        ->orWhereHas('uploader', function ($uploader) use ($search) {
                            $uploader->where(
                                'nama_lengkap',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->when(isset($filters['tahun']), function (Builder $query) use ($filters) {
                $query->where('tahun', $filters['tahun']);
            })
            ->when(isset($filters['status']), function (Builder $query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->orderByDesc('tanggal_penetapan')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        return view('sk.index', [
            'suratKeputusan' => $suratKeputusan,
            'totalSk' => SuratKeputusan::count(),
            'skBerlaku' => SuratKeputusan::where('status', 'final')->count(),
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

    // ==========================================
    // HALAMAN TAMBAH SURAT KEPUTUSAN
    // ==========================================
    public function create(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        return view('sk.create');
    }

    // ==========================================
    // SIMPAN SURAT KEPUTUSAN
    // ==========================================
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $validated = $request->validate([
            'nomor_sk' => [
                'required',
                'string',
                'max:100',
                'unique:surat_keputusan,nomor_sk',
            ],

            'judul' => [
                'required',
                'string',
                'min:10',
                'max:255',
            ],

            'tentang' => [
                'required',
                'string',
                'min:20',
                'max:500',
            ],

            'tanggal_penetapan' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:final,draft',
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ]);

        $file = $request->file('file');

        $path = $file->store('surat-keputusan', 'public');

        if (!$path) {
            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Gagal mengunggah file SK.',
                ]);
        }

        SuratKeputusan::create([
            'uploader_id' => $request->user()->id,
            'nomor_sk' => $validated['nomor_sk'],
            'judul' => $validated['judul'],
            'tentang' => $validated['tentang'],

            'tanggal_penetapan' => $validated['tanggal_penetapan'],

            'tahun' => date(
                'Y',
                strtotime($validated['tanggal_penetapan'])
            ),

            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'ukuran_kb' => (int) ceil($file->getSize() / 1024),

            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('sk.index')
            ->with('success', 'Surat Keputusan berhasil ditambahkan.');
    }

    // ==========================================
    // HALAMAN ARSIP SURAT KEPUTUSAN
    // ==========================================
    public function arsip(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        // VALIDASI FILTER
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],

            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . (now()->year + 1),
            ],
        ]);

        $search = trim($filters['search'] ?? '');

        // QUERY DATA ARSIP
        $arsipQuery = SuratKeputusan::query()
            ->with('uploader')
            ->where('status', 'arsip');

        // PENCARIAN DAN FILTER
        $suratKeputusan = (clone $arsipQuery)

            ->when($search !== '', function (Builder $query) use ($search) {

                $query->where(function (Builder $query) use ($search) {

                    $query->where('nomor_sk', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('tentang', 'like', "%{$search}%")

                        ->orWhereHas('uploader', function (Builder $uploader) use ($search) {

                            $uploader->where(
                                'nama_lengkap',
                                'like',
                                "%{$search}%"
                            );

                        });

                });

            })

            ->when(isset($filters['tahun']), function (Builder $query) use ($filters) {

                $query->where('tahun', $filters['tahun']);

            })

            ->orderByDesc('tanggal_penetapan')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        // DATA UNTUK HALAMAN ARSIP
        return view('sk.arsip', [

            'suratKeputusan' => $suratKeputusan,

            'totalArsip' => (clone $arsipQuery)->count(),

            'tahun' => SuratKeputusan::where('status', 'arsip')
                ->whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),

            'search' => $search,

            'selectedTahun' => $filters['tahun'] ?? '',

        ]);
    }
}
