<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laporan; // Pastikan model Laporan terpanggil

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Jika yang login adalah MDMC Daerah
        if ($user->role === 'daerah') {
            
            // PERBAIKAN: Hitung laporan milik daerah ini saja
            $jumlahLaporan = Laporan::where('user_id', $user->id)->count();
            
            // Kirim variabel jumlahLaporan ke file blade daerah
            return view('pengguna.dashboard', compact('user', 'jumlahLaporan')); 
        }

        // 2. Jika yang login adalah MDMC Wilayah (Admin)
        if ($user->role === 'admin') {
            
            // Hanya hitung user yang rolenya 'daerah'
            $jumlahPengguna = User::where('role', 'daerah')->count();
            
            // Hitung SEMUA laporan dari seluruh daerah di database
            $jumlahLaporan = Laporan::count(); 
            
            return view('admin.dashboard', compact('user', 'jumlahPengguna', 'jumlahLaporan')); 
        }
        
        // Default jika role tidak dikenali
        return abort(403, 'Anda tidak memiliki akses.');
    }
}