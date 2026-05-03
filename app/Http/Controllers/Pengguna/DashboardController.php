<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Laporan; // <-- Kita sesuaikan dengan nama model aslimu
use Illuminate\Support\Facades\Auth; // Wajib untuk mengambil data user yang login

class DashboardController extends Controller
{
    public function index()
    {
       
        // Jumlah Laporan
        $jumlahLaporan = Laporan::where('user_id', Auth::id())->count();

        // Kirim datanya ke file blade
        return view('pengguna.dashboard', compact('jumlahLaporan'));
    }
}