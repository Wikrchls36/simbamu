<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanController extends Controller
{
    // Menampilkan Halaman Laporan
    public function index()
    {
        // Ambil semua laporan beserta nama pelapornya
        // Diurutkan berdasarkan laporan yang paling baru di-update (updated_at)
        $laporans = Laporan::with('user')->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan.index', compact('laporans'));
    }

    // Menampilkan Peta Penyebaran Laporan Bencana
    public function peta()
    {
        // Ambil HANYA laporan yang berstatus 'Aktif'
        $laporans = Laporan::with('user')->where('status', 'Aktif')->latest()->get();
        return view('admin.laporan.peta', compact('laporans'));
    }
    // Fungsi Tombol Konfirmasi "Selesai" (Centang Hijau)
    public function tandaiSelesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Laporan bencana berhasil dikonfirmasi sebagai Selesai / Kondusif.');
    }
}