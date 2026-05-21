<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Laporan; 
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller
{
    public function index()
    {
       
        // Jumlah Laporan
        $jumlahLaporan = Laporan::where('user_id', Auth::id())->count();

        // Mengirim data jumlah laporan ke halaman dashboard
        return view('pengguna.dashboard', compact('jumlahLaporan'));
    }
}