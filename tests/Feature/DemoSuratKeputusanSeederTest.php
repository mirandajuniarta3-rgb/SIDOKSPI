<?php

use App\Models\SuratKeputusan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('database seeder creates an idempotent SK register matching the sample design', function () {
    seed(DatabaseSeeder::class);

    expect(SuratKeputusan::count())->toBe(432)
        ->and(SuratKeputusan::where('status', 'final')->count())->toBe(398)
        ->and(SuratKeputusan::where('status', 'arsip')->count())->toBe(34);

    $admin = User::where('email', 'test@example.com')->firstOrFail();
    actingAs($admin)
        ->get(route('sk.index'))
        ->assertOk()
        ->assertSee('432')
        ->assertSee('398')
        ->assertSee('34')
        ->assertSee('188.4/045/SK-DIR/2024')
        ->assertSee('188.4/088/SK-DIR/2022')
        ->assertSee('188.4/004/SK-DIR/2021');

    seed(DatabaseSeeder::class);

    expect(SuratKeputusan::count())->toBe(432)
        ->and(SuratKeputusan::where('status', 'final')->count())->toBe(398)
        ->and(SuratKeputusan::where('status', 'arsip')->count())->toBe(34);
});
