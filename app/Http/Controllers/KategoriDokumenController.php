<?php

namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class KategoriDokumenController extends Controller
{

    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = [
            'kode_kategori',
            'nama_kategori',
            'keterangan',
            'dokumen_count',
            'status',
            'created_at',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $kategori = KategoriDokumen::withCount('dokumen')
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_kategori', 'like', "%{$search}%")
                        ->orWhere('nama_kategori', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->orderBy('id', 'asc')
            ->paginate(6)
            ->withQueryString();

        return view('kategori.index', compact(
            'kategori',
            'search',
            'sort',
            'direction'
        ));
    }


    public function create(): View
    {
        return view('kategori.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kategori' => [
                'required',
                'string',
                'max:10',
                'unique:kategori_dokumen,kode_kategori',
            ],
            'nama_kategori' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        KategoriDokumen::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori dokumen berhasil ditambahkan.');
    }

    public function edit(KategoriDokumen $kategori): View
    {
        return view('kategori.edit', compact('kategori'));
    }


    public function update(
        Request $request,
        KategoriDokumen $kategori
    ): RedirectResponse|JsonResponse {

        $validated = $request->validate([
            'kode_kategori' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kategori_dokumen', 'kode_kategori')
                    ->ignore($kategori->id),
            ],
            'nama_kategori' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $kategori->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori dokumen berhasil diperbarui.',
                'kategori' => $kategori->fresh(),
            ]);
        }

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori dokumen berhasil diperbarui.');
    }

    public function destroy(KategoriDokumen $kategori): RedirectResponse
    {
        if ($kategori->dokumen()->exists()) {
            return redirect()
                ->route('kategori.index')
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan oleh dokumen.'
                );
        }

        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori dokumen berhasil dihapus.');
    }
}
