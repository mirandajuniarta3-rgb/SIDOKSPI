<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('guests can view the login page', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Selamat Datang')
        ->assertSee(route('login.store'));
});

test('users can log in with their email and password', function () {
    $user = User::factory()->create([
        'email' => 'auditor@example.com',
        'password_hash' => Hash::make('secret-password'),
        'status' => 'aktif',
    ]);

    $this->post(route('login.store'), [
        'identifier' => $user->email,
        'password' => 'secret-password',
    ])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('success');

    $this->assertAuthenticatedAs($user);
});

test('users can log in with their NIK', function () {
    $user = User::factory()->create([
        'nip' => 'SPI-2026001',
        'password_hash' => Hash::make('secret-password'),
        'status' => 'aktif',
    ]);

    $this->post(route('login.store'), [
        'identifier' => $user->nip,
        'password' => 'secret-password',
    ])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('inactive accounts cannot log in', function () {
    $user = User::factory()->create([
        'password_hash' => Hash::make('secret-password'),
        'status' => 'nonaktif',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'identifier' => $user->email,
            'password' => 'secret-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('identifier');

    $this->assertGuest();
});

test('authenticated users can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'))
        ->assertSessionHas('success');

    $this->assertGuest();
});
