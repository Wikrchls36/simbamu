<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan; // Pastikan model Laporan sudah ada
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Menampilkan riwayat laporan yang pernah dikirim daerah ini
    public function index()
    {
        $dataLaporan = Laporan::where('user_id', Auth::id())
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('pengguna.laporan.index', compact('dataLaporan'));
    }

    // Menampilkan halaman form tambah laporan
    public function create()
    {
        return view('pengguna.laporan.create');
    }

    // Memproses data dari form dan menyimpan ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required',
            'lokasi' => 'required|string',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $laporan = new Laporan();
        $laporan->user_id = Auth::id();
        $laporan->judul = $request->judul;
        $laporan->kategori = $request->kategori;
        $laporan->lokasi = $request->lokasi;
        $laporan->deskripsi = $request->deskripsi;
        $laporan->status = 'Menunggu Respon'; // Status awal default

        // Proses Upload Foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('uploads/laporan'), $nama_file);
            $laporan->foto = $nama_file;
        }

        $laporan->save();

        return redirect()->route('pengguna.laporan.index')->with('success', 'Laporan berhasil dikirim ke MDMC Wilayah!');
    }
}