<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PetaBencana;

class PetaController extends Controller
{
   // Jangan lupa pastikan fungsi ini menerima (Request $request) di dalam kurungnya
    // Fungsi ini HANYA untuk menampilkan Menu 2 Tombol
    public function index()
    {
        // Pastikan 'admin.peta.index' adalah nama file blade yang isinya 2 kotak menu
        return view('admin.peta.index'); 
    }

    // Fungsi ini KHUSUS untuk menampilkan Peta Leaflet
    public function lihat(\Illuminate\Http\Request $request)
    {
        $dataPeta = \App\Models\PetaBencana::all(); 
        $filter = $request->query('filter', 'banjir');

        // Pastikan 'admin.peta.lihat' adalah nama file blade peta kamu
        return view('admin.peta.lihat', compact('dataPeta', 'filter')); 
    }
    // Menampilkan halaman tabel kelola data
    public function kelola()
    {
        // Mengambil semua data dari tabel peta_bencanas
        $dataPeta = \App\Models\PetaBencana::all(); 
        return view('admin.peta.kelola', compact('dataPeta'));
    }

    // Memproses data yang dikirim dari form Edit (Modal)
    public function update(Request $request, $id)
    {
        $peta = \App\Models\PetaBencana::findOrFail($id);
        
        $peta->update([
            // Banjir Inti
            'luas_genangan' => $request->luas_genangan,
            'potensi_banjir' => $request->potensi_banjir,
            // Detail Banjir
            'tahun_banjir' => $request->tahun_banjir,
            'jiwa_terdampak_banjir' => $request->jiwa_terdampak_banjir,
            'rumah_warga_banjir' => $request->rumah_warga_banjir,
            'rumah_ibadah_banjir' => $request->rumah_ibadah_banjir,
            'faskes_banjir' => $request->faskes_banjir,
            'fasdik_banjir' => $request->fasdik_banjir,

            // Karhutla Inti
            'jumlah_hotspot' => $request->jumlah_hotspot,
            'potensi_karhutla' => $request->potensi_karhutla,
            // Detail Karhutla
            'tahun_karhutla' => $request->tahun_karhutla,
            'jiwa_terdampak_karhutla' => $request->jiwa_terdampak_karhutla,
            'luas_terbakar_karhutla' => $request->luas_terbakar_karhutla,

            // Sumber Informasi
            'sumber_data' => $request->sumber_data,
        ]);

        return redirect()->back()->with('success', 'Data ' . $peta->kabupaten_kota . ' berhasil diperbarui!');
    }
}