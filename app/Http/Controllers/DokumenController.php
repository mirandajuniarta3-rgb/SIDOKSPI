<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\LogAktivitas;
use App\Exports\DokumenExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use ZipArchive;

class DokumenController extends Controller
{
    /**
     * Admin dan Staf SPI boleh mengakses pengelolaan dokumen.
     */
    private function authorizeDokumen(Request $request): void
    {
        abort_unless(
            in_array($request->user()->role, ['admin', 'staf_spi'], true),
            403
        );
    }

    /**
     * Query dokumen untuk daftar dan ekspor.
     */
    private function queryDokumen(array $filters): Builder
    {
        $search = trim($filters['search'] ?? '');

        return Dokumen::query()
            ->with(['kategori', 'uploader'])
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhereHas('uploader', fn (Builder $uploader) => $uploader
                        ->where('nama_lengkap', 'like', "%{$search}%"))
            ))
            ->when(
                isset($filters['kategori']),
                fn (Builder $query) => $query->where(
                    'kategori_id',
                    $filters['kategori']
                )
            )
            ->when(
                isset($filters['tahun']),
                fn (Builder $query) => $query->where(
                    'tahun',
                    $filters['tahun']
                )
            )
            ->when(
                isset($filters['status']),
                fn (Builder $query) => $query->where(
                    'status',
                    $filters['status']
                )
            )
            ->latest('created_at');
    }

    /**
     * Query khusus arsip dokumen.
     */
    private function queryArsipDokumen(array $filters): Builder
    {
        $search = trim($filters['search'] ?? '');

        return Dokumen::query()
            ->with(['kategori', 'uploader'])
            ->where('status', 'arsip')
            ->when($search !== '', fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                    ->orWhereHas('uploader', fn (Builder $uploader) => $uploader
                        ->where('nama_lengkap', 'like', "%{$search}%"))
            ))
            ->when(
                isset($filters['kategori']),
                fn (Builder $query) => $query->where(
                    'kategori_id',
                    $filters['kategori']
                )
            )
            ->when(
                isset($filters['tahun']),
                fn (Builder $query) => $query->where(
                    'tahun',
                    $filters['tahun']
                )
            )
            ->orderByDesc('archived_at')
            ->orderByDesc('created_at');
    }

    /**
     * Validasi filter halaman arsip.
     */
    private function validateArsipFilters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => [
                'nullable',
                'integer',
                'exists:kategori_dokumen,id',
            ],
            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.(now()->year + 1),
            ],
        ]);
    }

    /**
     * Form unggah dokumen.
     */
    public function create(Request $request): View
    {
        $this->authorizeDokumen($request);

        return view('dokumen.daftar_dokumen.create', [
            'kategori' => KategoriDokumen::query()
                ->orderBy('nama_kategori')
                ->get(),
            'tahunAnggaran' => range(now()->year + 1, now()->year - 10),
        ]);
    }

    /**
     * Simpan dokumen baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeDokumen($request);

        $validated = $request->validate([
            'nomor_dokumen' => [
                'required',
                'string',
                'max:100',
                'unique:dokumen,nomor_dokumen',
            ],
            'judul' => ['required', 'string', 'max:255'],
            'kategori_id' => [
                'required',
                'integer',
                'exists:kategori_dokumen,id',
            ],
            'tahun' => [
                'required',
                'integer',
                'min:1900',
                'max:'.(now()->year + 1),
            ],
            'tanggal_dokumen' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'file' => [
                'required',
                'file',
                'mimes:pdf,docx,xlsx',
                'max:20480',
            ],
        ], [
            'file.required' => 'Pilih berkas yang akan diunggah.',
            'file.mimes' => 'Format berkas harus PDF, DOCX, atau XLSX.',
            'file.max' => 'Ukuran berkas maksimal 20 MB.',
        ]);

        $file = $validated['file'];

        $path = $file->store(
            'dokumen/'.$validated['tahun'],
            'local'
        );

        if (! $path) {
            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Berkas gagal disimpan. Silakan coba kembali.',
                ]);
        }

        $originalName = preg_replace(
            '/[\x00-\x1F\x7F]/u',
            '',
            basename(str_replace('\\', '/', $file->getClientOriginalName()))
        );

        $originalName = Str::limit(
            $originalName ?: 'dokumen.'.$file->extension(),
            255,
            ''
        );

        try {
            DB::transaction(function () use (
                $validated,
                $request,
                $file,
                $path,
                $originalName
            ): void {
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
                report(new \RuntimeException(
                    'Gagal menghapus berkas setelah penyimpanan metadata gagal: '.$path
                ));
            }

            throw $exception;
        }

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diunggah dan disimpan sebagai draft.');
    }

    /**
     * Daftar seluruh dokumen.
     */
    public function index(Request $request): View
    {
        $this->authorizeDokumen($request);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => [
                'nullable',
                'integer',
                'exists:kategori_dokumen,id',
            ],
            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.(now()->year + 1),
            ],
            'status' => ['nullable', 'in:draft,final,arsip'],
        ]);

        $search = trim($filters['search'] ?? '');

        $dokumen = $this->queryDokumen($filters)
            ->paginate(10)
            ->withQueryString();

        return view('dokumen.daftar_dokumen.index', [
            'dokumen' => $dokumen,
            'kategori' => KategoriDokumen::query()
                ->orderBy('nama_kategori')
                ->get(),
            'tahun' => Dokumen::query()
                ->whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),
            'totalDokumen' => Dokumen::count(),
            'dokumenAktif' => Dokumen::where('status', '!=', 'arsip')->count(),
            'dokumenDiarsipkan' => Dokumen::where('status', 'arsip')->count(),
            'search' => $search,
            'selectedKategori' => $filters['kategori'] ?? '',
            'selectedTahun' => $filters['tahun'] ?? '',
            'selectedStatus' => $filters['status'] ?? '',
        ]);
    }

    /**
     * Menampilkan detail dokumen.
     */
    public function show(Request $request, Dokumen $dokumen): View
    {
        $this->authorizeDokumen($request);

        $dokumen->load(['kategori', 'uploader']);

        $disk = Storage::disk('local');
        $fileAvailable = filled($dokumen->path_file)
            && $disk->exists($dokumen->path_file);
        $fileChecksum = null;

        if ($fileAvailable) {
            $fileChecksum = hash_file('sha256', $disk->path($dokumen->path_file));

            if ($fileChecksum === false) {
                report(new \RuntimeException(
                    'Gagal menghitung checksum dokumen '.$dokumen->getKey()
                ));
                $fileChecksum = null;
            }
        }

        $dokumen->setAttribute(
            'file_available',
            $fileAvailable
        );

        $lastDownload = $dokumen->logAktivitas()
            ->with('user')
            ->where('aksi', 'unduh_dokumen')
            ->latest('created_at')
            ->first();

        LogAktivitas::create([
            'user_id' => $request->user()->id,
            'dokumen_id' => $dokumen->id,
            'aksi' => 'lihat_detail_dokumen',
            'ip_address' => $request->ip(),
        ]);

        return view('dokumen.daftar_dokumen.detail', compact(
            'dokumen',
            'fileChecksum',
            'lastDownload'
        ));
    }

    /**
     * Pratinjau PDF dokumen dari penyimpanan privat.
     */
    public function preview(
        Request $request,
        Dokumen $dokumen
    ): StreamedResponse {
        $this->authorizeDokumen($request);

        abort_unless(
            $dokumen->path_file
            && Storage::disk('local')->exists($dokumen->path_file),
            404
        );

        abort_unless(
            strtolower(pathinfo($dokumen->path_file, PATHINFO_EXTENSION)) === 'pdf',
            415
        );

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('local');

        return $disk->response(
            $dokumen->path_file,
            $dokumen->nama_file ?: basename($dokumen->path_file),
            [
                'Content-Type' => 'application/pdf',
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }

    /**
     * Ekspor seluruh data dokumen sesuai filter ke Excel.
     */
    public function export(Request $request)
    {
        $this->authorizeDokumen($request);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => [
                'nullable',
                'integer',
                'exists:kategori_dokumen,id',
            ],
            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.(now()->year + 1),
            ],
            'status' => ['nullable', 'in:draft,final,arsip'],
        ]);

        $query = $this->queryDokumen($filters);

        $namaFile = 'daftar-dokumen-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(
            new DokumenExport($query),
            $namaFile
        );
    }

    /**
     * Daftar arsip dokumen.
     */
    public function arsip(Request $request): View
    {
        $this->authorizeDokumen($request);

        $filters = $this->validateArsipFilters($request);
        $search = trim($filters['search'] ?? '');

        $arsipQuery = $this->queryArsipDokumen($filters);

        $dokumen = (clone $arsipQuery)
            ->paginate(8)
            ->withQueryString();

        $dokumen->getCollection()->each(function (Dokumen $item): void {
            $item->setAttribute(
                'file_available',
                filled($item->path_file)
                    && Storage::disk('local')->exists($item->path_file)
            );
        });

        return view('dokumen.daftar_dokumen.arsip_dokumen', [
            'dokumen' => $dokumen,
            'kategori' => KategoriDokumen::query()
                ->whereHas('dokumen', fn (Builder $query) => $query->where('status', 'arsip'))
                ->orderBy('nama_kategori')
                ->get(),
            'tahun' => Dokumen::query()
                ->where('status', 'arsip')
                ->whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),
            'totalArsip' => (clone $arsipQuery)->count(),
            'retensiRataRata' => (clone $arsipQuery)
                ->whereNotNull('retensi_tahun')
                ->avg('retensi_tahun'),
            'kapasitasArsipKb' => (int) (clone $arsipQuery)->sum('ukuran_kb'),
            'search' => $search,
            'selectedKategori' => $filters['kategori'] ?? '',
            'selectedTahun' => $filters['tahun'] ?? '',
        ]);
    }

    /**
     * Ekspor daftar arsip dokumen sesuai filter ke Excel.
     */
    public function exportArsip(Request $request)
    {
        $this->authorizeDokumen($request);

        $filters = $this->validateArsipFilters($request);
        $query = $this->queryArsipDokumen($filters);

        $namaFile = 'arsip-dokumen-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(
            new DokumenExport($query),
            $namaFile
        );
    }

    /**
     * Unduh semua file dokumen arsip dalam satu ZIP.
     */
    public function downloadBundle(Request $request)
    {
        $this->authorizeDokumen($request);

        $filters = $this->validateArsipFilters($request);
        $dokumen = $this->queryArsipDokumen($filters)->get();

        $zipPath = tempnam(sys_get_temp_dir(), 'sidokspi_dokumen_');

        if ($zipPath === false) {
            return back()->with('error', 'File ZIP sementara gagal dibuat.');
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipPath);

            return back()->with('error', 'File ZIP gagal dibuat.');
        }

        $jumlahFile = 0;
        $disk = Storage::disk('local');

        foreach ($dokumen as $item) {
            if (
                ! filled($item->path_file)
                || ! $disk->exists($item->path_file)
            ) {
                continue;
            }

            $namaAsli = $item->nama_file ?: basename($item->path_file);
            $namaAman = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($namaAsli));
            $namaDalamZip = $item->id.'_'.$namaAman;

            if ($zip->addFile($disk->path($item->path_file), $namaDalamZip)) {
                $jumlahFile++;
            }
        }

        $zip->close();

        if ($jumlahFile === 0) {
            @unlink($zipPath);

            return back()->with(
                'warning',
                'Tidak ada file arsip yang tersedia untuk dimasukkan ke ZIP.'
            );
        }

        $namaZip = 'bundel-arsip-dokumen-'.now()->format('Y-m-d-His').'.zip';

        return response()
            ->download($zipPath, $namaZip)
            ->deleteFileAfterSend(true);
    }

    /**
     * Arsipkan dokumen.
     */
    public function archive(
        Request $request,
        Dokumen $dokumen
    ): RedirectResponse {
        $this->authorizeDokumen($request);

        DB::transaction(function () use ($dokumen, $request): void {
            $lockedDocument = Dokumen::query()
                ->lockForUpdate()
                ->findOrFail($dokumen->id);

            abort_unless($lockedDocument->status !== 'arsip', 404);

            $lockedDocument->update([
                'status' => 'arsip',
                'archived_at' => now(),
            ]);

            LogAktivitas::create([
                'user_id' => $request->user()->id,
                'dokumen_id' => $lockedDocument->id,
                'aksi' => 'arsip_dokumen',
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diarsipkan.');
    }

    /**
     * Pemulihan arsip hanya untuk Admin.
     */
    public function restore(
        Request $request,
        Dokumen $dokumen
    ): RedirectResponse {
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

    /**
     * Hapus dokumen beserta file fisiknya.
     * Riwayat log aktivitas lama tetap dipertahankan.
     */
    public function destroy(
        Request $request,
        Dokumen $dokumen
    ): RedirectResponse {
        $this->authorizeDokumen($request);

        $pathFile = $dokumen->path_file;

        DB::transaction(function () use ($dokumen, $request): void {
            $lockedDocument = Dokumen::query()
                ->lockForUpdate()
                ->findOrFail($dokumen->id);

            // Lepaskan hubungan log lama dengan dokumen.
            LogAktivitas::where('dokumen_id', $lockedDocument->id)
                ->update([
                    'dokumen_id' => null,
                ]);

            // Simpan catatan penghapusan.
            LogAktivitas::create([
                'user_id' => $request->user()->id,
                'dokumen_id' => null,
                'aksi' => 'hapus_dokumen',
                'ip_address' => $request->ip(),
            ]);

            // Hapus data dokumen.
            $lockedDocument->delete();
        });

        // Hapus file fisik setelah transaksi database berhasil.
        if (filled($pathFile)) {
            $disk = Storage::disk('local');

            if ($disk->exists($pathFile)) {
                if (! $disk->delete($pathFile)) {
                    report(new \RuntimeException(
                        'File dokumen gagal dihapus: '.$pathFile
                    ));

                    return redirect()
                        ->route('dokumen.index')
                        ->with(
                            'warning',
                            'Data dokumen terhapus, tetapi file fisiknya gagal dihapus.'
                        );
                }
            }
        }

        return redirect()
            ->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Unduh dokumen untuk Admin dan Staf SPI.
     */
    public function download(
        Request $request,
        Dokumen $dokumen
    ): StreamedResponse {
        $this->authorizeDokumen($request);

        abort_unless(
            $dokumen->path_file
            && Storage::disk('local')->exists($dokumen->path_file),
            404
        );

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
