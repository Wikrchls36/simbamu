<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\LaporanUpdate;

class LaporanController extends Controller
{
    // 1. Menampilkan Halaman Log Laporan (Semua Daerah)
    public function index()
    {
       
        // Mengurutkan laporan berdasarkan laporan yang paling baru di-update 
        $laporans = Laporan::with(['user', 'updates'])->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan.index', compact('laporans'));
    }

    // 2. Menampilkan Peta Penyebaran Laporan Bencana
    public function peta()
    {
        // Ambil hanya laporan yang berstatus 'Aktif'
        $laporans = Laporan::with('user')->where('status', 'Aktif')->latest()->get();
        return view('admin.laporan.peta', compact('laporans'));
    }

    // 3. Menampilkan Detail SitRep (Fitur Show)
    public function show($id, Request $request)
    {
        // Cari laporan induk beserta data user dan riwayat updates-nya
        $laporan = Laporan::with(['updates', 'user'])->findOrFail($id);
        
        // Pagination
        $sitrepId = $request->query('sitrep');
        if ($sitrepId) {
            $currentSitrep = $laporan->updates->where('id', $sitrepId)->first();
        } else {
            $currentSitrep = $laporan->updates->first(); 
        }

        return view('admin.laporan.show', compact('laporan', 'currentSitrep'));
    }

    // 4. Download PDF untuk Wilayah
    public function downloadPdf($update_id)
    {
        // Ambil data SitRep
        $sitrep = LaporanUpdate::with('laporan.user')->findOrFail($update_id);
        
        // Arahkan ke view PDF 
        return view('pengguna.laporan.pdf', compact('sitrep'));
    }

    // 5. Fungsi Tombol Konfirmasi "Selesai" 
    public function tandaiSelesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Laporan bencana berhasil dikonfirmasi sebagai Selesai / Kondusif.');
    }
}