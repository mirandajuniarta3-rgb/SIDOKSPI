<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('administrators can view and filter the document list', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $auditCategory = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);
    $otherCategory = KategoriDokumen::create([
        'kode_kategori' => 'KKP',
        'nama_kategori' => 'Kertas Kerja Pemeriksaan',
    ]);

    Dokumen::create([
        'kategori_id' => $auditCategory->id,
        'uploader_id' => $admin->id,
        'nomor_dokumen' => 'DOK/SPI/2026/001',
        'judul' => 'Laporan Audit Tahunan',
        'tanggal_dokumen' => '2026-10-10',
        'tahun' => 2026,
        'status' => 'final',
    ]);
    Dokumen::create([
        'kategori_id' => $otherCategory->id,
        'uploader_id' => $admin->id,
        'nomor_dokumen' => 'DOK/SPI/2025/002',
        'judul' => 'Kertas Kerja Pemeriksaan',
        'tanggal_dokumen' => '2025-08-05',
        'tahun' => 2025,
        'status' => 'arsip',
    ]);

    actingAs($admin)
        ->get(route('dokumen.index'))
        ->assertOk()
        ->assertSee('Daftar Dokumen Pengawasan')
        ->assertSee('DOK/SPI/2026/001')
        ->assertSee('Laporan Audit Tahunan')
        ->assertSee('Kertas Kerja Pemeriksaan');

    actingAs($admin)
        ->get(route('dokumen.index', [
            'search' => 'Laporan',
            'kategori' => $auditCategory->id,
            'tahun' => 2026,
            'status' => 'final',
        ]))
        ->assertOk()
        ->assertSee('DOK/SPI/2026/001')
        ->assertDontSee('DOK/SPI/2025/002');
});

test('non-administrators cannot view the document list', function () {
    /** @var User $user */
    $user = User::factory()->createOne(['role' => 'auditee']);

    actingAs($user)
        ->get(route('dokumen.index'))
        ->assertForbidden();
});

test('guests are redirected from the document list to login', function () {
    get(route('dokumen.index'))
        ->assertRedirect(route('login'));
});
