<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SuratKeputusanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KategoriDokumenController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // DOKUMEN
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
    Route::get('/dokumen/arsip', [DokumenController::class, 'arsip'])->name('dokumen.arsip');
    Route::get('/dokumen/{dokumen}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::post('/dokumen/{dokumen}/restore', [DokumenController::class, 'restore'])->name('dokumen.restore');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');

    // SURAT KEPUTUSAN
    Route::get('/sk', [SuratKeputusanController::class, 'index'])->name('sk.index');
    Route::get('/sk/create', [SuratKeputusanController::class, 'create'])->name('sk.create');
    Route::post('/sk', [SuratKeputusanController::class, 'store'])->name('sk.store');
    Route::get('/sk/arsip', [SuratKeputusanController::class, 'arsip'])->name('sk.arsip');

    // LAPORAN
    Route::get('/laporan/dokumen', [LaporanController::class, 'dokumen'])->name('laporan.dokumen');
    Route::get('/laporan/sk', [LaporanController::class, 'sk'])->name('laporan.sk');

    // KATEGORI DOKUMEN (KHUSUS ADMIN)
    Route::middleware('admin')->group(function () {
        Route::resource('kategori', KategoriDokumenController::class)
            ->except(['show'])
            ->names('kategori');
    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});
