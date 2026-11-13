<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

test('administrators can view the document upload form', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);

    actingAs($admin)
        ->get(route('dokumen.create'))
        ->assertOk()
        ->assertSee('Unggah Dokumen Pengawasan')
        ->assertSee('Metadata &amp; Identitas Dokumen', false)
        ->assertSee('Berkas Lampiran Dokumen')
        ->assertSee('Simpan Dokumen')
        ->assertDontSee('Tingkat Kerahasiaan Berkas');
});

test('administrators can upload a supported document to private storage', function () {
    Storage::fake('local');
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);

    actingAs($admin)
        ->post(route('dokumen.store'), [
            'nomor_dokumen' => 'DOK/SPI/2026/UPLOAD-001',
            'judul' => 'Laporan Audit Unit Pelayanan',
            'kategori_id' => $category->id,
            'tahun' => 2026,
            'tanggal_dokumen' => '2026-11-13',
            'deskripsi' => 'Ringkasan pemeriksaan unit pelayanan.',
            'file' => UploadedFile::fake()->create('laporan.pdf', 250, 'application/pdf'),
        ])
        ->assertRedirect(route('dokumen.index'))
        ->assertSessionHas('success');

    $dokumen = Dokumen::where('nomor_dokumen', 'DOK/SPI/2026/UPLOAD-001')->firstOrFail();

    expect($dokumen->uploader_id)->toBe($admin->id)
        ->and($dokumen->status)->toBe('draft')
        ->and($dokumen->tingkat_kerahasiaan)->toBe('terbatas')
        ->and($dokumen->nama_file)->toBe('laporan.pdf')
        ->and(Storage::disk('local')->exists($dokumen->path_file))->toBeTrue()
        ->and(LogAktivitas::where('dokumen_id', $dokumen->id)->value('aksi'))->toBe('unggah_dokumen');
});

test('unsupported document files are rejected without creating a record', function () {
    Storage::fake('local');
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);

    actingAs($admin)
        ->from(route('dokumen.create'))
        ->post(route('dokumen.store'), [
            'nomor_dokumen' => 'DOK/SPI/2026/UPLOAD-002',
            'judul' => 'Berkas Tidak Didukung',
            'kategori_id' => $category->id,
            'tahun' => 2026,
            'tanggal_dokumen' => '2026-11-13',
            'tingkat_kerahasiaan' => 'terbatas',
            'file' => UploadedFile::fake()->create('payload.exe', 100, 'application/x-msdownload'),
        ])
        ->assertRedirect(route('dokumen.create'))
        ->assertSessionHasErrors('file');

    expect(Dokumen::where('nomor_dokumen', 'DOK/SPI/2026/UPLOAD-002')->exists())->toBeFalse();
});

test('non-administrators cannot upload documents', function () {
    /** @var User $user */
    $user = User::factory()->createOne(['role' => 'auditee']);

    actingAs($user)
        ->get(route('dokumen.create'))
        ->assertForbidden();

    actingAs($user)
        ->post(route('dokumen.store'))
        ->assertForbidden();
});
