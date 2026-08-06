<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Laporan; 
use Illuminate\Support\Facades\Auth; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $queryLaporan = Laporan::where('user_id', $user->id);
        $jumlahLaporan = (clone $queryLaporan)->count();
        $laporanBanjir = (clone $queryLaporan)->where('jenis_bencana', 'Banjir')->count();
        $laporanKarhutla = (clone $queryLaporan)->where('jenis_bencana', 'Karhutla')->count();
        $laporanAktif = (clone $queryLaporan)->where('status', 'Aktif')->count();
        $laporanSelesai = (clone $queryLaporan)->where('status', 'Selesai')->count();

        return view('pengguna.dashboard', compact(
            'user', 'jumlahLaporan', 'laporanBanjir', 'laporanKarhutla', 'laporanAktif', 'laporanSelesai'
        ));
    }
}