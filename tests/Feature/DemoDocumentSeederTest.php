<?php

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('database seeder creates an idempotent document dataset for the list page', function () {
    seed(DatabaseSeeder::class);

    expect(Dokumen::count())->toBe(120)
        ->and(Dokumen::where('status', 'final')->count())->toBe(98)
        ->and(Dokumen::where('status', 'arsip')->count())->toBe(22)
        ->and(KategoriDokumen::count())->toBe(5);

    $archivedDocument = Dokumen::where('status', 'arsip')->firstOrFail();
    expect($archivedDocument->archived_at)->not->toBeNull()
        ->and($archivedDocument->retensi_tahun)->toBe(5);

    $admin = User::where('email', 'test@example.com')->firstOrFail();

    actingAs($admin)
        ->get(route('dokumen.index'))
        ->assertOk()
        ->assertSee('120')
        ->assertSee('DOK/SPI/2024/11-042')
        ->assertSee('LHA Operasional Izin Perdagangan Air Bersih');

    actingAs($admin)
        ->get(route('dokumen.arsip'))
        ->assertOk()
        ->assertSee('22 Berkas')
        ->assertSee('Retensi Rata-rata')
        ->assertSee('5');

    seed(DatabaseSeeder::class);

    expect(Dokumen::count())->toBe(120)
        ->and(Dokumen::where('status', 'final')->count())->toBe(98)
        ->and(Dokumen::where('status', 'arsip')->count())->toBe(22);
});
