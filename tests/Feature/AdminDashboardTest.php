<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\SuratKeputusan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('administrators can view the operational dashboard', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $kategori = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);

    Dokumen::create([
        'kategori_id' => $kategori->id,
        'uploader_id' => $admin->id,
        'nomor_dokumen' => 'DOK/SPI/001',
        'judul' => 'Laporan Audit Tahunan',
        'tanggal_dokumen' => today(),
        'tahun' => today()->year,
        'status' => 'arsip',
    ]);

    SuratKeputusan::create([
        'uploader_id' => $admin->id,
        'nomor_sk' => 'SK/001',
        'judul' => 'Penetapan Tim Audit',
        'tanggal_penetapan' => today(),
        'tahun' => today()->year,
        'nama_file' => 'sk-001.pdf',
        'path_file' => 'sk/sk-001.pdf',
        'status' => 'final',
    ]);

    actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Utama SPI')
        ->assertSee('Daftar Dokumen')
        ->assertSee('Unggah Dokumen')
        ->assertSee('Arsip')
        ->assertSee('Surat Keputusan (SK)')
        ->assertSee('Daftar SK')
        ->assertSee('Tambah SK')
        ->assertSee('Arsip SK')
        ->assertSee('href="'.route('dokumen.index').'"', false)
        ->assertSee('DOK/SPI/001')
        ->assertSee('Laporan Hasil Audit')
        ->assertSee('SK/001')
        ->assertSee('Laporan Audit Tahunan')
        ->assertSee('Penetapan Tim Audit');
});

test('non-administrators cannot view the administration dashboard', function () {
    /** @var User $user */
    $user = User::factory()->createOne(['role' => 'auditee']);

    actingAs($user)
        ->get(route('dashboard'))
        ->assertForbidden();
});

test('administrators can search recent documents and decisions', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $kategori = KategoriDokumen::create([
        'kode_kategori' => 'PKPT',
        'nama_kategori' => 'Program Kerja Pengawasan',
    ]);

    Dokumen::create([
        'kategori_id' => $kategori->id,
        'uploader_id' => $admin->id,
        'nomor_dokumen' => 'SPI-TAHUNAN-DOK',
        'judul' => 'Dokumen Rencana Audit',
        'status' => 'final',
    ]);

    SuratKeputusan::create([
        'uploader_id' => $admin->id,
        'nomor_sk' => 'SPI-TAHUNAN-SK',
        'judul' => 'Surat Keputusan Tim Audit',
        'nama_file' => 'sk-tahunan.pdf',
        'path_file' => 'sk/sk-tahunan.pdf',
        'status' => 'final',
    ]);

    actingAs($admin)
        ->get(route('dashboard', ['search' => 'SPI-TAHUNAN']))
        ->assertOk()
        ->assertSee('Hasil pencarian dokumen')
        ->assertSee('SPI-TAHUNAN-DOK')
        ->assertSee('SPI-TAHUNAN-SK')
        ->assertSee('Dokumen Rencana Audit')
        ->assertSee('Surat Keputusan Tim Audit');
});

test('guests are redirected from the dashboard route to login', function () {
    get(route('dashboard'))
        ->assertRedirect(route('login'));
});
