<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\LaporanBencana; // Pastikan Model ini sudah ada
use Illuminate\Support\Facades\Auth; // Wajib untuk mengambil data user yang login

class DashboardController extends Controller
{
    public function index()
    {
        // Logika: Hitung laporan yang kolom 'user_id'-nya sama dengan ID user yang sedang login
        // Inilah yang membuat Sambas tampil 2 dan Sintang tampil 3 secara otomatis
       $jumlahLaporan = 0;

        // Kirim datanya ke file blade
        return view('pengguna.dashboard', compact('jumlahLaporan'));
    }
}