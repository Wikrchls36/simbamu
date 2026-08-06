<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;

class RekapitulasiController extends Controller
{
    private function getRekapData(Request $request)
    {
        $filterStatus = $request->query('status', 'Semua');
        $filterJenis = $request->query('jenis_bencana', 'Semua');
        $filterTahun = $request->query('tahun', date('Y'));
        $filterBulan = $request->query('bulan', 'all');

        $queryLaporan = Laporan::with(['updates' => function($q) {
            $q->orderBy('id', 'desc');
        }, 'user'])->whereYear('created_at', $filterTahun);

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
        
        $rincianDaerah = [];

        foreach ($laporans as $laporan) {
            $sitrepTerbaru = $laporan->updates->first();
            $namaDaerah = $laporan->user->name ?? 'Daerah Tidak Diketahui';
            
            if (!isset($rincianDaerah[$namaDaerah])) {
                $rincianDaerah[$namaDaerah] = [
                    'jumlah_kejadian' => 0,
                    'meninggal' => 0, 'luka' => 0, 'hilang' => 0, 
                    'pengungsi' => 0, 'terdampak' => 0, 'relawan' => 0
                ];
            }

            $rincianDaerah[$namaDaerah]['jumlah_kejadian'] += 1;

            if ($sitrepTerbaru) {
                
                $totalMeninggal += (int) ($sitrepTerbaru->dampak_meninggal ?? 0);
                $totalLuka += (int) ($sitrepTerbaru->dampak_luka ?? 0);
                $totalHilang += (int) ($sitrepTerbaru->dampak_hilang ?? 0);
                $totalPengungsi += (int) ($sitrepTerbaru->dampak_pengungsi ?? 0);
                $totalTerdampak += (int) ($sitrepTerbaru->dampak_terdampak ?? 0);
                $totalRelawan += (int) ($sitrepTerbaru->tim_total_semua ?? 0);
                
              
                $rincianDaerah[$namaDaerah]['meninggal'] += (int) ($sitrepTerbaru->dampak_meninggal ?? 0);
                $rincianDaerah[$namaDaerah]['luka'] += (int) ($sitrepTerbaru->dampak_luka ?? 0);
                $rincianDaerah[$namaDaerah]['hilang'] += (int) ($sitrepTerbaru->dampak_hilang ?? 0);
                $rincianDaerah[$namaDaerah]['pengungsi'] += (int) ($sitrepTerbaru->dampak_pengungsi ?? 0);
                $rincianDaerah[$namaDaerah]['terdampak'] += (int) ($sitrepTerbaru->dampak_terdampak ?? 0);
                $rincianDaerah[$namaDaerah]['relawan'] += (int) ($sitrepTerbaru->tim_total_semua ?? 0);
            }
        }

        return compact(
            'laporans', 'totalKejadian', 'totalMeninggal', 'totalLuka', 'totalHilang', 
            'totalPengungsi', 'totalTerdampak', 'totalRelawan',
            'filterStatus', 'filterTahun', 'filterBulan', 'filterJenis', 'rincianDaerah'
        );
    }

    
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return abort(403, 'Anda tidak memiliki akses.');
        }

        $rekapData = $this->getRekapData($request);
        $user = Auth::user();

        return view('admin.rekapitulasi.index', array_merge(['user' => $user], $rekapData));
    }

    
    public function cetak(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return abort(403, 'Anda tidak memiliki akses.');
        }

        $rekapData = $this->getRekapData($request);
        $user = Auth::user();

        return view('admin.rekapitulasi.cetak', array_merge(['user' => $user], $rekapData));
    }
}