<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PetaController;
use App\Http\Controllers\Admin\PeringatanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Pengguna\DashboardController as PenggunaDashboard;
use App\Http\Controllers\Pengguna\PetaController as PenggunaPeta;
use App\Http\Controllers\Pengguna\PeringatanController as PenggunaPeringatan;
use App\Http\Controllers\Pengguna\LaporanController as PenggunaLaporan;

// --- RUTE PUBLIK ---
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- GRUP RUTE ADMIN (WAJIB LOGIN) ---
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

    // Manajemen Peta (Admin)
    Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
    Route::get('/peta/lihat', [PetaController::class, 'lihat'])->name('peta.lihat');
    Route::get('/peta/kelola', [PetaController::class, 'kelola'])->name('peta.kelola');
    Route::put('/peta/update/{id}', [PetaController::class, 'update'])->name('peta.update');

    // Peringatan Bencana (Admin Kirim)
    Route::get('/peringatan', [PeringatanController::class, 'index'])->name('peringatan.index');
    Route::post('/peringatan', [PeringatanController::class, 'store'])->name('peringatan.store');

    // Laporan Bencana (Admin)
    Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan.index'); 
    Route::get('/laporan/peta', [App\Http\Controllers\Admin\LaporanController::class, 'peta'])->name('admin.laporan.peta');
    Route::get('/laporan/{id}/detail', [App\Http\Controllers\Admin\LaporanController::class, 'show'])->name('admin.laporan.show');
    Route::get('/laporan/{id}/pdf', [App\Http\Controllers\Admin\LaporanController::class, 'downloadPdf'])->name('admin.laporan.pdf');
    Route::patch('/laporan/{id}/selesai', [App\Http\Controllers\Admin\LaporanController::class, 'tandaiSelesai'])->name('admin.laporan.selesai');

}); // 


// --- GRUP RUTE PENGGUNA / MDMC DAERAH ---
Route::prefix('pengguna')->middleware('auth')->group(function () {
    
    // Dashboard Pengguna
    Route::get('/dashboard', [PenggunaDashboard::class, 'index'])->name('pengguna.dashboard');
    
    // Peta Pengguna
    Route::get('/peta', [PenggunaPeta::class, 'index'])->name('pengguna.peta.index');
    Route::get('/peta/lihat', [PenggunaPeta::class, 'lihat'])->name('pengguna.peta.lihat');

    // Peringatan Pengguna
    Route::get('/peringatan', [PenggunaPeringatan::class, 'index'])->name('pengguna.peringatan.index');
    Route::post('/peringatan/konfirmasi/{id}', [PenggunaPeringatan::class, 'konfirmasi'])->name('pengguna.peringatan.konfirmasi');
  
    // Rute Dashboard & Log Laporan
    Route::get('/laporan', [PenggunaLaporan::class, 'index'])->name('pengguna.laporan.index');

    // Rute Buat Laporan Baru
    Route::get('/laporan/create', [PenggunaLaporan::class, 'create'])->name('pengguna.laporan.create');
    Route::post('/laporan/store', [PenggunaLaporan::class, 'store'])->name('pengguna.laporan.store');

    // Rute Peta (Pindahkan ke /laporan/peta agar seragam)
    Route::get('/laporan/peta', [PenggunaLaporan::class, 'peta'])->name('pengguna.laporan.peta');

    Route::get('/laporan/{id}/update', [App\Http\Controllers\Pengguna\LaporanController::class, 'createUpdate'])->name('pengguna.laporan.update_create');
    Route::post('/laporan/{id}/update', [App\Http\Controllers\Pengguna\LaporanController::class, 'storeUpdate'])->name('pengguna.laporan.update_store');

    // Rute untuk melihat halaman detail laporan & pagination SitRep
    Route::get('/laporan/{id}/detail', [App\Http\Controllers\Pengguna\LaporanController::class, 'show'])->name('pengguna.laporan.show');

    // Rute asli untuk mendownload PDF
    Route::get('/laporan/sitrep/{update_id}/pdf', [App\Http\Controllers\Pengguna\LaporanController::class, 'downloadPdf'])->name('pengguna.laporan.pdf');
    
    // Route untuk menampilkan form update
    Route::get('/pengguna/laporan/{id}/update', [LaporanController::class, 'createUpdate'])->name('pengguna.laporan.update_create');

    // Route untuk memproses form update (POST)
    Route::post('/laporan/{id}/update', [App\Http\Controllers\Pengguna\LaporanController::class, 'storeUpdate'])->name('pengguna.laporan.store_update');


    });