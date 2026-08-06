<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;

class RekapitulasiController extends Controller
{
   
    public function index(Request $request)
    {
       
        $filterStatus = $request->query('status', 'Semua');
        $filterJenis = $request->query('jenis_bencana', 'Semua');
        $filterTahun = $request->query('tahun', date('Y'));
        $filterBulan = $request->query('bulan', 'all');

       
        $queryLaporan = Laporan::with(['updates' => function($q) {
            $q->orderBy('id', 'desc');
        }])->where('user_id', Auth::id())
          ->whereYear('created_at', $filterTahun);

        if ($filterBulan !== 'all') {
            $queryLaporan->whereMonth('created_at', $filterBulan);
        }
        
        if ($filterJenis !== 'Semua') {
            $queryLaporan->where('jenis_bencana', $filterJenis);
        }
        
        
        if ($filterStatus !== 'Semua') {
            $queryLaporan->where('status', $filterStatus);
        }

        $laporans = $queryLaporan->get();
        $totalKejadian = $laporans->count();
        $totalMeninggal = 0; $totalLuka = 0; $totalHilang = 0;
        $totalPengungsi = 0; $totalTerdampak = 0; $totalRelawan = 0;

        foreach ($laporans as $laporan) {
            $sitrepTerbaru = $laporan->updates->first();
            if ($sitrepTerbaru) {
                $totalMeninggal += (int) ($sitrepTerbaru->dampak_meninggal ?? 0);
                $totalLuka += (int) ($sitrepTerbaru->dampak_luka ?? 0);
                $totalHilang += (int) ($sitrepTerbaru->dampak_hilang ?? 0);
                $totalPengungsi += (int) ($sitrepTerbaru->dampak_pengungsi ?? 0);
                $totalTerdampak += (int) ($sitrepTerbaru->dampak_terdampak ?? 0);
                $totalRelawan += (int) ($sitrepTerbaru->tim_total_semua ?? 0);
            }
        }

        return view('pengguna.rekapitulasi.index', compact(
            'laporans', 'totalKejadian', 'totalMeninggal', 'totalLuka', 'totalHilang',
            'totalPengungsi', 'totalTerdampak', 'totalRelawan',
            'filterStatus', 'filterTahun', 'filterBulan', 'filterJenis'
        ));
    }

    
    public function cetak(Request $request)
    {
        
        $filterStatus = $request->query('status', 'Semua');
        $filterJenis = $request->query('jenis_bencana', 'Semua');
        $filterTahun = $request->query('tahun', date('Y'));
        $filterBulan = $request->query('bulan', 'all');

        
        $queryLaporan = Laporan::with(['updates' => function($q) {
            $q->orderBy('id', 'desc');
        }])->where('user_id', Auth::id())
          ->whereYear('created_at', $filterTahun);

        if ($filterBulan !== 'all') {
            $queryLaporan->whereMonth('created_at', $filterBulan);
        }
        
        if ($filterJenis !== 'Semua') {
            $queryLaporan->where('jenis_bencana', $filterJenis);
        }
        
      
        if ($filterStatus !== 'Semua') {
            $queryLaporan->where('status', $filterStatus);
        }

        $laporans = $queryLaporan->get();

        return view('pengguna.rekapitulasi.cetak', compact(
            'laporans', 'filterStatus', 'filterTahun', 'filterBulan', 'filterJenis'
        ));
    }
}