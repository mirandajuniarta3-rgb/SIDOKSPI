<?php

use App\Models\SuratKeputusan;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('administrators can browse and filter Surat Keputusan', function () {
    /** @var User $admin */
    $admin = User::factory()->createOne(['role' => 'admin', 'nama_lengkap' => 'Budi Prasetyo']);

    SuratKeputusan::create([
        'uploader_id' => $admin->id,
        'nomor_sk' => 'SK/SPI/2026/001',
        'judul' => 'Penetapan Tim Audit Internal',
        'tentang' => 'Tata Kelola Audit',
        'tanggal_penetapan' => '2026-10-10',
        'tahun' => 2026,
        'status' => 'final',
    ]);
    SuratKeputusan::create([
        'uploader_id' => $admin->id,
        'nomor_sk' => 'SK/SPI/2025/002',
        'judul' => 'Pedoman Retensi Dokumen',
        'tentang' => 'Kearsipan',
        'tanggal_penetapan' => '2025-08-05',
        'tahun' => 2025,
        'status' => 'arsip',
    ]);

    actingAs($admin)
        ->get(route('sk.index'))
        ->assertOk()
        ->assertSee('Daftar Surat Keputusan (SK)')
        ->assertSee('href="'.route('sk.index').'"', false)
        ->assertSee('SK/SPI/2026/001')
        ->assertSee('Penetapan Tim Audit Internal')
        ->assertSee('SK/SPI/2025/002');

    actingAs($admin)
        ->get(route('sk.index', [
            'search' => 'Tata Kelola',
            'tahun' => 2026,
            'status' => 'final',
        ]))
        ->assertOk()
        ->assertSee('SK/SPI/2026/001')
        ->assertDontSee('SK/SPI/2025/002');
});

test('non-administrators cannot view the SK register', function () {
    /** @var User $user */
    $user = User::factory()->createOne(['role' => 'auditee']);

    actingAs($user)
        ->get(route('sk.index'))
        ->assertForbidden();
});

test('guests are redirected from the SK register to login', function () {
    get(route('sk.index'))
        ->assertRedirect(route('login'));
});
