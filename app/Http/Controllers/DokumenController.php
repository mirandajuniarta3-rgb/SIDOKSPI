<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class DokumenController extends Controller
{
    public function create(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        return view('dokumen.daftar_dokumen.create', [
            'kategori' => KategoriDokumen::query()->orderBy('nama_kategori')->get(),
            'tahunAnggaran' => range(now()->year + 1, now()->year - 10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $validated = $request->validate([
            'nomor_dokumen' => ['required', 'string', 'max:100', 'unique:dokumen,nomor_dokumen'],
            'judul' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'integer', 'exists:kategori_dokumen,id'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'tanggal_dokumen' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'file' => ['required', 'file', 'mimes:pdf,docx,xlsx', 'max:20480'],
        ], [
            'file.required' => 'Pilih berkas yang akan diunggah.',
            'file.mimes' => 'Format berkas harus PDF, DOCX, atau XLSX.',
            'file.max' => 'Ukuran berkas maksimal 20 MB.',
        ]);

        $file = $validated['file'];
        $path = $file->store('dokumen/'.$validated['tahun'], 'local');

        if (! $path) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'Berkas gagal disimpan. Silakan coba kembali.']);
        }

        $originalName = preg_replace(
            '/[\x00-\x1F\x7F]/u',
            '',
            basename(str_replace('\\', '/', $file->getClientOriginalName()))
        );
        $originalName = Str::limit($originalName ?: 'dokumen.'.$file->extension(), 255, '');

        try {
            DB::transaction(function () use ($validated, $request, $file, $path, $originalName): void {
                $dokumen = Dokumen::create([
                    'kategori_id' => $validated['kategori_id'],
                    'uploader_id' => $request->user()->id,
                    'bidang' => $request->user()->bidang,
                    'nomor_dokumen' => $validated['nomor_dokumen'],
                    'judul' => $validated['judul'],
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'tanggal_dokumen' => $validated['tanggal_dokumen'],
                    'tahun' => $validated['tahun'],
                    'tingkat_kerahasiaan' => 'terbatas',
                    'nama_file' => $originalName,
                    'path_file' => $path,
                    'ukuran_kb' => (int) ceil($file->getSize() / 1024),
                    'status' => 'draft',
                ]);

                LogAktivitas::create([
                    'user_id' => $request->user()->id,
                    'dokumen_id' => $dokumen->id,
                    'aksi' => 'unggah_dokumen',
                    'ip_address' => $request->ip(),
                ]);
            });
        } catch (Throwable $exception) {
            if (! Storage::disk('local')->delete($path)) {
                report(new \RuntimeException('Gagal menghapus berkas setelah penyimpanan metadata gagal: '.$path));
            }

            throw $exception;
        }

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diunggah dan disimpan sebagai draft.');
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'integer', 'exists:kategori_dokumen,id'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'status' => ['nullable', 'in:draft,final,arsip'],
        ]);
        $search = trim($filters['search'] ?? '');

        $dokumen = Dokumen::query()
            ->with(['kategori', 'uploader'])
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhereHas('uploader', fn (Builder $uploader) => $uploader
                        ->where('nama_lengkap', 'like', "%{$search}%"))
            ))
            ->when(isset($filters['kategori']), fn (Builder $query) => $query->where('kategori_id', $filters['kategori']))
            ->when(isset($filters['tahun']), fn (Builder $query) => $query->where('tahun', $filters['tahun']))
            ->when(isset($filters['status']), fn (Builder $query) => $query->where('status', $filters['status']))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('dokumen.daftar_dokumen.index', [
            'dokumen' => $dokumen,
            'kategori' => KategoriDokumen::query()->orderBy('nama_kategori')->get(),
            'tahun' => Dokumen::query()->whereNotNull('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
            'totalDokumen' => Dokumen::count(),
            'dokumenAktif' => Dokumen::where('status', '!=', 'arsip')->count(),
            'dokumenDiarsipkan' => Dokumen::where('status', 'arsip')->count(),
            'search' => $search,
            'selectedKategori' => $filters['kategori'] ?? '',
            'selectedTahun' => $filters['tahun'] ?? '',
            'selectedStatus' => $filters['status'] ?? '',
        ]);
    }

    public function arsip(Request $request): View
    {
        abort_unless($request->user()->role === 'admin', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'integer', 'exists:kategori_dokumen,id'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
        ]);
        $search = trim($filters['search'] ?? '');

        $arsipQuery = Dokumen::query()->where('status', 'arsip');
        $dokumen = (clone $arsipQuery)
            ->with(['kategori', 'uploader'])
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhereHas('uploader', fn (Builder $uploader) => $uploader
                        ->where('nama_lengkap', 'like', "%{$search}%"))
            ))
            ->when(isset($filters['kategori']), fn (Builder $query) => $query->where('kategori_id', $filters['kategori']))
            ->when(isset($filters['tahun']), fn (Builder $query) => $query->where('tahun', $filters['tahun']))
            ->orderByDesc('archived_at')
            ->orderByDesc('created_at')
            ->paginate(8)
            ->withQueryString();

        $dokumen->getCollection()->each(function (Dokumen $item): void {
            $item->setAttribute(
                'file_available',
                filled($item->path_file) && Storage::disk('local')->exists($item->path_file)
            );
        });

        return view('dokumen.daftar_dokumen.arsip_dokumen', [
            'dokumen' => $dokumen,
            'kategori' => KategoriDokumen::query()
                ->whereHas('dokumen', fn (Builder $query) => $query->where('status', 'arsip'))
                ->orderBy('nama_kategori')
                ->get(),
            'tahun' => (clone $arsipQuery)->whereNotNull('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
            'totalArsip' => (clone $arsipQuery)->count(),
            'retensiRataRata' => (clone $arsipQuery)->whereNotNull('retensi_tahun')->avg('retensi_tahun'),
            'kapasitasArsipKb' => (int) (clone $arsipQuery)->sum('ukuran_kb'),
            'search' => $search,
            'selectedKategori' => $filters['kategori'] ?? '',
            'selectedTahun' => $filters['tahun'] ?? '',
        ]);
    }

    public function restore(Request $request, Dokumen $dokumen): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        DB::transaction(function () use ($dokumen, $request): void {
            $lockedDocument = Dokumen::query()
                ->lockForUpdate()
                ->findOrFail($dokumen->id);

            abort_unless($lockedDocument->status === 'arsip', 404);

            $lockedDocument->update([
                'status' => 'final',
                'archived_at' => null,
                'retensi_tahun' => null,
            ]);

            LogAktivitas::create([
                'user_id' => $request->user()->id,
                'dokumen_id' => $lockedDocument->id,
                'aksi' => 'pulihkan_dokumen',
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('dokumen.arsip')
            ->with('success', 'Dokumen berhasil diaktifkan kembali.');
    }

    public function download(Request $request, Dokumen $dokumen): StreamedResponse
    {
        abort_unless($request->user()->role === 'admin', 403);
        abort_unless($dokumen->status === 'arsip', 404);
        abort_unless($dokumen->path_file && Storage::disk('local')->exists($dokumen->path_file), 404);

        LogAktivitas::create([
            'user_id' => $request->user()->id,
            'dokumen_id' => $dokumen->id,
            'aksi' => 'unduh_dokumen',
            'ip_address' => $request->ip(),
        ]);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');

        return $disk->download(
            $dokumen->path_file,
            $dokumen->nama_file ?: basename($dokumen->path_file)
        );
    }
}
