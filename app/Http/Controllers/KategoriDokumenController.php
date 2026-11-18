<?php

namespace App\Http\Controllers;

use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class KategoriDokumenController extends Controller
{
    // MENAMPILKAN DAFTAR KATEGORI
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $kategori = KategoriDokumen::withCount('dokumen')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_kategori', 'like', "%{$search}%")
                      ->orWhere('nama_kategori', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('kategori.index', compact(
            'kategori',
            'search'
        ));
    }

    // FORM TAMBAH KATEGORI
    public function create(): View
    {
        return view('kategori.create');
    }

    // MENYIMPAN KATEGORI
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

    // FORM EDIT KATEGORI
    public function edit(KategoriDokumen $kategori): View
    {
        return view('kategori.edit', compact('kategori'));
    }

    // MEMPERBARUI KATEGORI
    public function update(
        Request $request,
        KategoriDokumen $kategori
    ): RedirectResponse {

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

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori dokumen berhasil diperbarui.');
    }

    // MENGHAPUS KATEGORI
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
