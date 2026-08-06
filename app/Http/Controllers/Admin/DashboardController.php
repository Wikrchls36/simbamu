<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Laporan; 
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $filterTahun = $request->query('tahun', date('Y'));
        $filterBulan = $request->query('bulan', 'all');
        
        $queryLaporan = Laporan::query();
        $queryLaporan->whereYear('created_at', $filterTahun);

        if ($filterBulan !== 'all') {
            $queryLaporan->whereMonth('created_at', $filterBulan);
        }

        $jumlahPengguna = User::where('role', 'daerah')->count();
        $jumlahLaporan = (clone $queryLaporan)->count(); 

        $labelBencana = ['Banjir', 'Karhutla'];
        $dataBencana = [
            (clone $queryLaporan)->where('jenis_bencana', 'Banjir')->count(),
            (clone $queryLaporan)->where('jenis_bencana', 'Karhutla')->count()
        ];

        $labelStatus = ['Aktif', 'Selesai'];
        $dataStatus = [
            (clone $queryLaporan)->where('status', 'Aktif')->count(),
            (clone $queryLaporan)->where('status', 'Selesai')->count()
        ];
        
        $topDaerah = User::where('role', 'daerah')
            ->withCount(['laporans' => function ($query) use ($filterTahun, $filterBulan) {
                $query->whereYear('created_at', $filterTahun);
                      
                if ($filterBulan !== 'all') {
                    $query->whereMonth('created_at', $filterBulan);
                }
            }])
            ->orderByDesc('laporans_count')
            ->take(3)
            ->get();
        
        return view('admin.dashboard', compact(
            'user', 'jumlahPengguna', 'jumlahLaporan', 
            'labelBencana', 'dataBencana', 
            'labelStatus', 'dataStatus',
            'topDaerah', 'filterTahun', 'filterBulan' 
        )); 
    }
}