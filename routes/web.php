
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SuratKeputusanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KategoriDokumenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

/*
|--------------------------------------------------------------------------
| PENGGUNA YANG SUDAH LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        $role = strtolower(trim(auth()->user()->role));

        if ($role === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        if ($role === 'staf_spi') {
            return redirect()->route('dashboard.staf');
        }

        abort(403, 'Role pengguna tidak memiliki akses dashboard.');
    })->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'index'])
            ->name('dashboard.admin');
    });

    Route::middleware('role:staf_spi')->group(function () {
        Route::get('/dashboard/staf', [DashboardController::class, 'staf'])
            ->name('dashboard.staf');
    });

    /*
    |--------------------------------------------------------------------------
    | DOKUMEN, SURAT KEPUTUSAN, DAN LAPORAN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin,staf_spi')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DOKUMEN
        |--------------------------------------------------------------------------
        */

        Route::get('/dokumen', [DokumenController::class, 'index'])
            ->name('dokumen.index');

        Route::get('/dokumen/export', [DokumenController::class, 'export'])
            ->name('dokumen.export');

        Route::get('/dokumen/create', [DokumenController::class, 'create'])
            ->name('dokumen.create');

        Route::post('/dokumen', [DokumenController::class, 'store'])
            ->name('dokumen.store');

        /*
        |--------------------------------------------------------------------------
        | ARSIP DOKUMEN
        |--------------------------------------------------------------------------
        */

        Route::get('/dokumen/arsip', [DokumenController::class, 'arsip'])
            ->name('dokumen.arsip');

        Route::get('/dokumen/arsip/export', [DokumenController::class, 'exportArsip'])
            ->name('dokumen.arsip.export');

        Route::get('/dokumen/arsip/bundle', [DokumenController::class, 'downloadBundle'])
            ->name('dokumen.arsip.bundle');

        /*
        |--------------------------------------------------------------------------
        | PREVIEW DOKUMEN
        |--------------------------------------------------------------------------
        */

        Route::get('/dokumen/{dokumen}/preview', [
            DokumenController::class,
            'preview'
        ])->name('dokumen.preview');

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD DOKUMEN
        |--------------------------------------------------------------------------
        */

        Route::get('/dokumen/{dokumen}/download', [
            DokumenController::class,
            'download'
        ])->name('dokumen.download');

        /*
        |--------------------------------------------------------------------------
        | AKSI DOKUMEN
        |--------------------------------------------------------------------------
        */

        Route::post('/dokumen/{dokumen}/archive', [
            DokumenController::class,
            'archive'
        ])->name('dokumen.archive');

        Route::post('/dokumen/{dokumen}/restore', [
            DokumenController::class,
            'restore'
        ])->name('dokumen.restore');

        Route::delete('/dokumen/{dokumen}', [
            DokumenController::class,
            'destroy'
        ])->name('dokumen.destroy');

        /*
        |--------------------------------------------------------------------------
        | DETAIL DOKUMEN
        |--------------------------------------------------------------------------
        */

        Route::get('/dokumen/{dokumen}', [
            DokumenController::class,
            'show'
        ])->name('dokumen.show');

        /*
        |--------------------------------------------------------------------------
        | SURAT KEPUTUSAN
        |--------------------------------------------------------------------------
        */

        Route::get('/sk', [SuratKeputusanController::class, 'index'])
            ->name('sk.index');

        Route::get('/sk/create', [SuratKeputusanController::class, 'create'])
            ->name('sk.create');

        Route::post('/sk', [SuratKeputusanController::class, 'store'])
            ->name('sk.store');

        /*
        |--------------------------------------------------------------------------
        | ARSIP SURAT KEPUTUSAN
        |--------------------------------------------------------------------------
        */

        Route::get('/sk/arsip', [SuratKeputusanController::class, 'arsip'])
            ->name('sk.arsip');

        Route::get('/sk/arsip/export', [SuratKeputusanController::class, 'exportArsip'])
            ->name('sk.arsip.export');

        Route::get('/sk/arsip/bundle', [SuratKeputusanController::class, 'downloadBundle'])
            ->name('sk.arsip.bundle');

        /*
        |--------------------------------------------------------------------------
        | AKSI SURAT KEPUTUSAN
        |--------------------------------------------------------------------------
        */

        Route::get('/sk/{suratKeputusan}/download', [
            SuratKeputusanController::class,
            'download'
        ])->name('sk.download');

        Route::post('/sk/{suratKeputusan}/archive', [
            SuratKeputusanController::class,
            'simpanArsip'
        ])->name('sk.simpanArsip');

        Route::delete('/sk/{suratKeputusan}', [
            SuratKeputusanController::class,
            'destroy'
        ])->name('sk.destroy');

        Route::get('/sk/{suratKeputusan}', [
            SuratKeputusanController::class,
            'show'
        ])->name('sk.show');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan/dokumen', [LaporanController::class, 'dokumen'])
            ->name('laporan.dokumen');

        Route::get('/laporan/sk', [LaporanController::class, 'sk'])
            ->name('laporan.sk');
    });

    /*
    |--------------------------------------------------------------------------
    | MASTER DATA KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::resource('kategori', KategoriDokumenController::class)
            ->except(['show'])
            ->names('kategori');

        Route::resource('pengguna', UserController::class)
            ->except(['show'])
            ->names('pengguna');

        Route::get('/log-aktivitas', [
            LogAktivitasController::class,
            'index'
        ])->name('log-aktivitas.index');

        Route::get('/profil', [ProfilController::class, 'index'])
            ->name('profil.index');

        Route::put('/profil', [ProfilController::class, 'update'])
            ->name('profil.update');
    });

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
