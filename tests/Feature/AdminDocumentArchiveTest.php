<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

function createArchivedDocument(User $uploader, KategoriDokumen $category, array $attributes = []): Dokumen
{
    return Dokumen::create(array_merge([
        'kategori_id' => $category->id,
        'uploader_id' => $uploader->id,
        'nomor_dokumen' => 'DOK/SPI/2026/ARSIP-001',
        'judul' => 'Laporan Audit Arsip',
        'tanggal_dokumen' => '2026-10-10',
        'tahun' => 2026,
        'status' => 'arsip',
        'archived_at' => '2026-11-01 09:00:00',
        'retensi_tahun' => 5,
        'ukuran_kb' => 2048,
    ], $attributes));
}

test('administrators can browse and filter archived documents', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin', 'nama_lengkap' => 'Budi Prasetyo']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);

    createArchivedDocument($admin, $category);
    createArchivedDocument($admin, $category, [
        'nomor_dokumen' => 'DOK/SPI/2025/ARSIP-002',
        'judul' => 'Laporan Audit Tahun Lalu',
        'tahun' => 2025,
        'archived_at' => '2025-11-01 09:00:00',
    ]);
    Dokumen::create([
        'kategori_id' => $category->id,
        'uploader_id' => $admin->id,
        'nomor_dokumen' => 'DOK/SPI/2026/AKTIF-003',
        'judul' => 'Dokumen Aktif',
        'tanggal_dokumen' => '2026-11-10',
        'tahun' => 2026,
        'status' => 'final',
    ]);

    actingAs($admin)
        ->get(route('dokumen.arsip'))
        ->assertOk()
        ->assertSee('Arsip Dokumen Pengawasan')
        ->assertSee('DOK/SPI/2026/ARSIP-001')
        ->assertSee('DOK/SPI/2025/ARSIP-002')
        ->assertSee('Retensi Rata-rata')
        ->assertDontSee('DOK/SPI/2026/AKTIF-003');

    actingAs($admin)
        ->get(route('dokumen.arsip', [
            'search' => 'Arsip',
            'kategori' => $category->id,
            'tahun' => 2026,
        ]))
        ->assertOk()
        ->assertSee('DOK/SPI/2026/ARSIP-001')
        ->assertDontSee('DOK/SPI/2025/ARSIP-002');
});

test('administrators can reactivate an archived document and the action is logged', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);
    $document = createArchivedDocument($admin, $category);

    actingAs($admin)
        ->post(route('dokumen.restore', $document))
        ->assertRedirect(route('dokumen.arsip'))
        ->assertSessionHas('success');

    $document->refresh();
    expect($document->status)->toBe('final')
        ->and($document->archived_at)->toBeNull()
        ->and($document->retensi_tahun)->toBeNull()
        ->and(LogAktivitas::where('dokumen_id', $document->id)->value('aksi'))->toBe('pulihkan_dokumen');
});

test('administrators can download an archived file from private storage', function () {
    Storage::fake('local');
    Storage::disk('local')->put('dokumen/arsip/laporan.pdf', 'arsip-content');
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);
    $document = createArchivedDocument($admin, $category, [
        'path_file' => 'dokumen/arsip/laporan.pdf',
        'nama_file' => 'laporan.pdf',
    ]);

    actingAs($admin)
        ->get(route('dokumen.download', $document))
        ->assertDownload('laporan.pdf');

    expect(LogAktivitas::where('dokumen_id', $document->id)->value('aksi'))->toBe('unduh_dokumen');
});

test('non-administrators cannot access the archive', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);
    $document = createArchivedDocument($admin, $category);
    /** @var User $user */
    $user = User::factory()->createOne(['role' => 'auditee']);

    actingAs($user)
        ->get(route('dokumen.arsip'))
        ->assertForbidden();

    actingAs($user)
        ->post(route('dokumen.restore', $document))
        ->assertForbidden();

});

test('guests are redirected from the archive to login', function () {
    get(route('dokumen.arsip'))
        ->assertRedirect(route('login'));
});
