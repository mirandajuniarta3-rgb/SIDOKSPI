<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

test('administrators can view document details and preview and download an active PDF', function () {
    Storage::fake('local');
    Storage::disk('local')->put(
        'dokumen/2026/laporan.pdf',
        "%PDF-1.4\nDetail dokumen\n%%EOF"
    );

    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin']);
    $category = KategoriDokumen::create([
        'kode_kategori' => 'LHA',
        'nama_kategori' => 'Laporan Hasil Audit',
    ]);

    $dokumen = Dokumen::create([
        'kategori_id' => $category->id,
        'uploader_id' => $admin->id,
        'nomor_dokumen' => 'DOK/SPI/2026/DETAIL-001',
        'judul' => 'Laporan Audit Unit Pelayanan',
        'tanggal_dokumen' => '2026-11-25',
        'tahun' => 2026,
        'tingkat_kerahasiaan' => 'terbatas',
        'status' => 'final',
        'nama_file' => 'laporan.pdf',
        'path_file' => 'dokumen/2026/laporan.pdf',
        'ukuran_kb' => 1,
    ]);

    actingAs($admin)
        ->get(route('dokumen.show', $dokumen))
        ->assertOk()
        ->assertSee('<aside class="sidebar"', false)
        ->assertSee('Informasi &amp; Metadata Dokumen', false)
        ->assertSee('Integritas Dokumen &amp; Jejak Audit', false)
        ->assertSee(route('dokumen.preview', $dokumen), false);

    $preview = actingAs($admin)
        ->get(route('dokumen.preview', $dokumen))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');

    expect($preview->headers->get('Content-Disposition'))->toStartWith('inline;');

    actingAs($admin)
        ->get(route('dokumen.download', $dokumen))
        ->assertDownload('laporan.pdf');
});
