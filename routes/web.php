<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\PeringatanController;
use App\Http\Controllers\LaporanController;



// Rute Publik
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Rute Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Wajib Login
Route::middleware('auth')->group(function () {
    
// Halaman Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);
    
// Rute Profil
Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index']);
Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);

// Rute Manajemen Pengguna
Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create']);
Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);

// Rute Hapus Akun User
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy']);

// Rute Menampilkan Halaman Edit Data User
Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit']);

// Rute Untuk Memproses Perubahan Data User
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/password', [ProfileController::class, 'editPassword']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

// Rute Peta
Route::middleware('auth')->group(function () {
    
    // 1. Rute untuk Halaman Menu Utama Peta (Halaman 2 Tombol)
    Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');

    // 2. Rute untuk Menampilkan Peta Leaflet (Visualisasi)
    // (Nama fungsi disesuaikan menjadi 'lihat' sesuai Controller yang baru)
    Route::get('/peta/lihat', [PetaController::class, 'lihat'])->name('peta.lihat');

    // 3. Rute khusus untuk Kelola Data Peta
    Route::get('/peta/kelola', [PetaController::class, 'kelola'])->name('peta.kelola');
    
    // Rute 'edit' dihapus karena kita sudah menggunakan Modal (Popup) di halaman Kelola
    Route::put('/peta/update/{id}', [PetaController::class, 'update'])->name('peta.update');
    
});

Route::get('/peringatan', [PeringatanController::class, 'index'])->name('peringatan.index');
Route::post('/peringatan', [PeringatanController::class, 'store'])->name('peringatan.store');

// Rute Laporan //
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::patch('/laporan/{id}/selesai', [LaporanController::class, 'tandaiSelesai'])->name('laporan.selesai');
Route::get('/laporan/peta', [LaporanController::class, 'peta'])->name('laporan.peta');

});