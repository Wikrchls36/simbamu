<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\LaporanUpdate;

class LaporanController extends Controller
{
    // 1. Menampilkan Halaman Log Laporan 
    public function index()
    {
        
        $laporans = Laporan::with(['user', 'updates'])->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan.index', compact('laporans'));
    }

    // 2. Menampilkan Peta Penyebaran Laporan Bencana
    public function peta()
    {
        
        $semuaLaporan = Laporan::with(['user', 'updates' => function($query) {
            $query->orderBy('id', 'asc');
        }])->where('status', 'Aktif')->get();
        
        return view('admin.laporan.peta', compact('semuaLaporan'));
    }

    // 3. Menampilkan Detail SitRep 
    public function show($id, Request $request)
    {
       
        $laporan = Laporan::with(['user', 'updates' => function($query) {
            $query->orderBy('id', 'asc');
        }])->findOrFail($id);
        
        // Pagination
        $sitrepId = $request->query('sitrep');
        if ($sitrepId) {
            $currentSitrep = $laporan->updates->where('id', $sitrepId)->first();
        } else {
            $currentSitrep = $laporan->updates->first(); 
        }

        return view('admin.laporan.show', compact('laporan', 'currentSitrep'));
    }

    // 4. DOWNLOAD PDF
    public function downloadPdf($update_id)
    {
        
        $sitrep = LaporanUpdate::with('laporan.user')->findOrFail($update_id);
        
       
        return view('pengguna.laporan.pdf', compact('sitrep'));
    }

    // 5. Fungsi Tombol Konfirmasi 
    public function tandaiSelesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Laporan bencana berhasil dikonfirmasi sebagai Selesai / Kondusif.');
    }
}