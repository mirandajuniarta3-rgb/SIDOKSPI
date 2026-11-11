<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\SuratKeputusan;
use App\Models\User;

test('administrators can view the operational dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
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

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Utama SPI')
        ->assertSee('DOK/SPI/001')
        ->assertSee('Laporan Hasil Audit')
        ->assertSee('SK/001')
        ->assertSee('Laporan Audit Tahunan')
        ->assertSee('Penetapan Tim Audit');
});

test('non-administrators cannot view the administration dashboard', function () {
    $user = User::factory()->create(['role' => 'auditee']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertForbidden();
});

test('administrators can search recent documents and decisions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
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

    $this->actingAs($admin)
        ->get(route('dashboard', ['search' => 'SPI-TAHUNAN']))
        ->assertOk()
        ->assertSee('Hasil pencarian dokumen')
        ->assertSee('SPI-TAHUNAN-DOK')
        ->assertSee('SPI-TAHUNAN-SK')
        ->assertSee('Dokumen Rencana Audit')
        ->assertSee('Surat Keputusan Tim Audit');
});

test('guests are redirected from the dashboard route to login', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});
