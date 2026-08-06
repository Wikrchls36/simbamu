<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RekapitulasiController;
use App\Http\Controllers\Pengguna\DashboardController as PenggunaDashboard;
use App\Http\Controllers\Pengguna\ProfileController as PenggunaProfile;
use App\Http\Controllers\Pengguna\LaporanController as PenggunaLaporan;
use App\Http\Controllers\Pengguna\RekapitulasiController as PenggunaRekapitulasi;


//  RUTE PUBLIK 

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// RUTE ADMIN 

Route::middleware('auth')->group(function () {
    
    // Dashboard & Profil Admin
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/profile/password', [ProfileController::class, 'editPassword']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    // Manajemen Pengguna (Admin)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Laporan Bencana (Admin)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index'); 
    Route::get('/laporan/peta', [LaporanController::class, 'peta'])->name('admin.laporan.peta');
    Route::get('/laporan/{id}/detail', [LaporanController::class, 'show'])->name('admin.laporan.show');
    Route::get('/laporan/{id}/pdf', [LaporanController::class, 'downloadPdf'])->name('admin.laporan.pdf');
    Route::patch('/laporan/{id}/selesai', [LaporanController::class, 'tandaiSelesai'])->name('admin.laporan.selesai');
    
    
    // Rekapitulasi Laporan (Admin)
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])->name('admin.rekapitulasi.index');
    Route::get('/rekapitulasi/cetak', [RekapitulasiController::class, 'cetak'])->name('admin.rekapitulasi.cetak');
}); 


// RUTE PENGGUNA 

Route::prefix('pengguna')->middleware('auth')->group(function () {
    
    // Dashboard & Profil Pengguna
    Route::get('/dashboard', [PenggunaDashboard::class, 'index'])->name('pengguna.dashboard');
    Route::get('/profile', [PenggunaProfile::class, 'index'])->name('pengguna.profile.index');
    Route::post('/profile', [PenggunaProfile::class, 'update'])->name('pengguna.profile.update');
    
    // Laporan Bencana (Pengguna)
    Route::get('/laporan', [PenggunaLaporan::class, 'index'])->name('pengguna.laporan.index');
    Route::get('/laporan/peta', [PenggunaLaporan::class, 'peta'])->name('pengguna.laporan.peta');
    Route::get('/laporan/create', [PenggunaLaporan::class, 'create'])->name('pengguna.laporan.create');
    Route::post('/laporan/store', [PenggunaLaporan::class, 'store'])->name('pengguna.laporan.store');
    Route::get('/laporan/{id}/detail', [PenggunaLaporan::class, 'show'])->name('pengguna.laporan.show');
    Route::get('/laporan/{id}/update', [PenggunaLaporan::class, 'createUpdate'])->name('pengguna.laporan.update_create');
    Route::post('/laporan/{id}/update', [PenggunaLaporan::class, 'storeUpdate'])->name('pengguna.laporan.store_update');
    Route::patch('/laporan/{id}/batal', [PenggunaLaporan::class, 'tandaiBatal'])->name('pengguna.laporan.batal');
    Route::get('/laporan/sitrep/{update_id}/pdf', [PenggunaLaporan::class, 'downloadPdf'])->name('pengguna.laporan.pdf');

    // Rekapitulasi Laporan (Pengguna)
    Route::get('/rekapitulasi', [PenggunaRekapitulasi::class, 'index'])->name('pengguna.rekapitulasi.index');
    Route::get('/rekapitulasi/cetak', [PenggunaRekapitulasi::class, 'cetak'])->name('pengguna.rekapitulasi.cetak');
    
});