<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika yang login adalah MDMC Daerah
        if ($user->role === 'daerah') {
            // Arahkan ke file blade khusus daerah
            return view('pengguna.dashboard', compact('user')); 
        }

        // Jika yang login adalah MDMC Wilayah (Admin)
       // Jika yang login adalah MDMC Wilayah (Admin)
        if ($user->role === 'admin') {
            
            // PERBAIKAN: Hanya hitung user yang rolenya 'daerah'
            $jumlahPengguna = User::where('role', 'daerah')->count();
            
            $jumlahLaporan = 0; 
            
            return view('admin.dashboard', compact('user', 'jumlahPengguna', 'jumlahLaporan')); 
        
        }
        // Default jika role tidak dikenali
        return abort(403, 'Anda tidak memiliki akses.');
    }
}