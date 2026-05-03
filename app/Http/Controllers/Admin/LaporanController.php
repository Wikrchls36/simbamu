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
        // Ambil SEMUA laporan beserta nama pelapornya (user) dan relasi updates-nya
        // Diurutkan berdasarkan laporan yang paling baru di-update (updated_at)
        $laporans = Laporan::with(['user', 'updates'])->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan.index', compact('laporans'));
    }

    // 2. Menampilkan Peta Penyebaran Laporan Bencana
    public function peta()
    {
        // Ambil HANYA laporan yang berstatus 'Aktif'
        $laporans = Laporan::with('user')->where('status', 'Aktif')->latest()->get();
        return view('admin.laporan.peta', compact('laporans'));
    }

    // 3. Menampilkan Detail SitRep (Fitur Show)
    public function show($id, Request $request)
    {
        // Cari laporan induk beserta data user dan riwayat updates-nya
        $laporan = Laporan::with(['updates', 'user'])->findOrFail($id);
        
        // Logika Pagination: Tampilkan SitRep sesuai pilihan, atau yang pertama jika tidak ada pilihan
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
        
        // Arahkan ke view PDF milik pengguna (karena formatnya sama)
        return view('pengguna.laporan.pdf', compact('sitrep'));
    }

    // 5. Fungsi Tombol Konfirmasi "Selesai" (Centang Hijau)
    public function tandaiSelesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Laporan bencana berhasil dikonfirmasi sebagai Selesai / Kondusif.');
    }
}