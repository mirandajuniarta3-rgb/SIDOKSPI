<?php

namespace App\Http\Controllers;

use App\Models\SuratKeputusan;
use App\Models\LogAktivitas;
use App\Exports\SuratKeputusanExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use ZipArchive;

class SuratKeputusanController extends Controller
{
    /**
     * Admin dan Staf SPI dapat mengakses modul SK.
     */
    private function authorizeSK(Request $request): void
    {
        abort_unless(
            in_array($request->user()->role, ['admin', 'staf_spi'], true),
            403
        );
    }

    /**
     * Query khusus arsip SK dengan filter pencarian dan tahun.
     */
    private function queryArsipSK(array $filters): Builder
    {
        $search = trim($filters['search'] ?? '');

        return SuratKeputusan::query()
            ->with('uploader')
            ->where('status', 'arsip')
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
            ->orderByDesc('created_at');
    }

    /**
     * Validasi filter arsip SK.
     */
    private function validateArsipFilters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.(now()->year + 1),
            ],
        ]);
    }

    // ==========================================
    // DAFTAR SURAT KEPUTUSAN
    // ==========================================
    public function index(Request $request): View
    {
        $this->authorizeSK($request);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'tahun' => [
                'nullable',
                'integer',
                'min:1900',
                'max:'.(now()->year + 1),
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
        $this->authorizeSK($request);

        return view('sk.create');
    }

    // ==========================================
    // SIMPAN SURAT KEPUTUSAN
    // ==========================================
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSK($request);

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

        $file = $validated['file'];

        $path = $file->store('surat-keputusan', 'public');

        if (! $path) {
            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'Gagal mengunggah file SK.',
                ]);
        }

        $originalName = preg_replace(
            '/[\x00-\x1F\x7F]/u',
            '',
            basename(str_replace('\\', '/', $file->getClientOriginalName()))
        );

        $originalName = Str::limit(
            $originalName ?: 'surat-keputusan.pdf',
            255,
            ''
        );

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $file,
                $path,
                $originalName
            ): void {
                $sk = SuratKeputusan::create([
                    'uploader_id' => $request->user()->id,
                    'nomor_sk' => $validated['nomor_sk'],
                    'judul' => $validated['judul'],
                    'tentang' => $validated['tentang'],
                    'tanggal_penetapan' => $validated['tanggal_penetapan'],
                    'tahun' => date(
                        'Y',
                        strtotime($validated['tanggal_penetapan'])
                    ),
                    'nama_file' => $originalName,
                    'path_file' => $path,
                    'ukuran_kb' => (int) ceil($file->getSize() / 1024),
                    'status' => $validated['status'],
                ]);

                LogAktivitas::create([
                    'user_id' => $request->user()->id,
                    'sk_id' => $sk->id,
                    'aksi' => 'tambah_surat_keputusan',
                    'ip_address' => $request->ip(),
                ]);
            });
        } catch (Throwable $exception) {
            if (! Storage::disk('public')->delete($path)) {
                report(new \RuntimeException(
                    'Gagal menghapus file SK setelah penyimpanan metadata gagal: '.$path
                ));
            }

            throw $exception;
        }

        return redirect()
            ->route('sk.index')
            ->with('success', 'Surat Keputusan berhasil ditambahkan.');
    }

    // ==========================================
    // DETAIL SURAT KEPUTUSAN
    // ==========================================
    public function show(
        Request $request,
        SuratKeputusan $suratKeputusan
    ): View {
        $this->authorizeSK($request);

        $suratKeputusan->load('uploader');

        $suratKeputusan->setAttribute(
            'file_available',
            filled($suratKeputusan->path_file)
                && Storage::disk('public')->exists($suratKeputusan->path_file)
        );

        return view('sk.show', compact('suratKeputusan'));
    }

    // ==========================================
    // UNDUH FILE PDF SURAT KEPUTUSAN
    // ==========================================
    public function download(
        Request $request,
        SuratKeputusan $suratKeputusan
    ) {
        $this->authorizeSK($request);

        if (
            ! filled($suratKeputusan->path_file)
            || ! Storage::disk('public')->exists($suratKeputusan->path_file)
        ) {
            return back()->with(
                'error',
                'File PDF Surat Keputusan tidak ditemukan.'
            );
        }

        $namaFile = $suratKeputusan->nama_file
            ?: basename($suratKeputusan->path_file);

        return Storage::disk('public')->download(
            $suratKeputusan->path_file,
            basename($namaFile)
        );
    }

    // ==========================================
    // ARSIPKAN SURAT KEPUTUSAN
    // ==========================================
    public function simpanArsip(
        Request $request,
        SuratKeputusan $suratKeputusan
    ): RedirectResponse {
        $this->authorizeSK($request);

        if ($suratKeputusan->status === 'arsip') {
            return back()->with(
                'warning',
                'Surat Keputusan tersebut sudah berstatus arsip.'
            );
        }

        DB::transaction(function () use ($request, $suratKeputusan): void {
            $suratKeputusan->update([
                'status' => 'arsip',
            ]);

            LogAktivitas::create([
                'user_id' => $request->user()->id,
                'sk_id' => $suratKeputusan->id,
                'aksi' => 'arsip_surat_keputusan',
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('sk.index')
            ->with(
                'success',
                'Surat Keputusan berhasil dipindahkan ke arsip.'
            );
    }

    // ==========================================
    // HAPUS SURAT KEPUTUSAN
    // ==========================================
    public function destroy(
        Request $request,
        SuratKeputusan $suratKeputusan
    ): RedirectResponse {
        $this->authorizeSK($request);

        $pathFile = null;

        DB::transaction(function () use (
            $request,
            $suratKeputusan,
            &$pathFile
        ): void {
            // Kunci data SK selama proses penghapusan.
            $sk = SuratKeputusan::query()
                ->whereKey($suratKeputusan->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $pathFile = $sk->path_file;

            // Lepaskan relasi log lama agar riwayat aktivitas
            // tetap tersimpan dan tidak menghalangi penghapusan SK.
            LogAktivitas::where('sk_id', $sk->id)
                ->update(['sk_id' => null]);

            // Catat aktivitas penghapusan.
            LogAktivitas::create([
                'user_id' => $request->user()->id,
                'sk_id' => null,
                'aksi' => 'hapus_surat_keputusan',
                'ip_address' => $request->ip(),
            ]);

            // Hapus data SK dari database.
            $sk->delete();
        });

        // Hapus file setelah transaksi database berhasil.
        if (
            filled($pathFile)
            && Storage::disk('public')->exists($pathFile)
            && ! Storage::disk('public')->delete($pathFile)
        ) {
            report(new \RuntimeException(
                'Data SK sudah dihapus, tetapi file PDF gagal dihapus: '.$pathFile
            ));

            return redirect()
                ->route('sk.index')
                ->with(
                    'warning',
                    'Data SK berhasil dihapus, tetapi file PDF gagal dihapus dari penyimpanan.'
                );
        }

        return redirect()
            ->route('sk.index')
            ->with('success', 'Surat Keputusan berhasil dihapus.');
    }

    // ==========================================
    // HALAMAN ARSIP SURAT KEPUTUSAN
    // ==========================================
    public function arsip(Request $request): View
    {
        $this->authorizeSK($request);

        $filters = $this->validateArsipFilters($request);
        $search = trim($filters['search'] ?? '');

        $arsipQuery = $this->queryArsipSK($filters);

        $suratKeputusan = (clone $arsipQuery)
            ->paginate(8)
            ->withQueryString();

        $suratKeputusan->getCollection()->each(function (SuratKeputusan $item): void {
            $item->setAttribute(
                'file_available',
                filled($item->path_file)
                    && Storage::disk('public')->exists($item->path_file)
            );
        });

        return view('sk.arsip', [
            'suratKeputusan' => $suratKeputusan,
            'totalArsip' => (clone $arsipQuery)->count(),

            'tahun' => SuratKeputusan::query()
                ->where('status', 'arsip')
                ->whereNotNull('tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),

            'search' => $search,
            'selectedTahun' => $filters['tahun'] ?? '',
        ]);
    }

    /**
     * Ekspor daftar arsip SK ke Excel sesuai filter.
     */
    public function exportArsip(Request $request)
    {
        $this->authorizeSK($request);

        $filters = $this->validateArsipFilters($request);
        $query = $this->queryArsipSK($filters);

        $namaFile = 'arsip-surat-keputusan-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(
            new SuratKeputusanExport($query),
            $namaFile
        );
    }

    /**
     * Unduh semua PDF SK arsip yang tersedia dalam satu ZIP.
     */
    public function downloadBundle(Request $request)
    {
        $this->authorizeSK($request);

        $filters = $this->validateArsipFilters($request);
        $suratKeputusan = $this->queryArsipSK($filters)->get();

        $zipPath = tempnam(sys_get_temp_dir(), 'sidokspi_sk_');

        if ($zipPath === false) {
            return back()->with('error', 'File ZIP sementara gagal dibuat.');
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipPath);

            return back()->with('error', 'File ZIP gagal dibuat.');
        }

        $jumlahFile = 0;
        $disk = Storage::disk('public');

        foreach ($suratKeputusan as $item) {
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
                'Tidak ada file PDF arsip SK yang tersedia untuk dimasukkan ke ZIP.'
            );
        }

        $namaZip = 'bundel-arsip-sk-'.now()->format('Y-m-d-His').'.zip';

        return response()
            ->download($zipPath, $namaZip)
            ->deleteFileAfterSend(true);
    }
}
