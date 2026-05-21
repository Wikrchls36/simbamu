<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetaBencana;

class PetaController extends Controller
{
    // Halaman Peta Bencana //
    public function index()
    {
        
        return view('admin.peta.index'); 
    }

    // Fungsi Untuk Menampilkan Peta Leaflet
    public function lihat(\Illuminate\Http\Request $request)
    {
        $dataPeta = \App\Models\PetaBencana::all(); 
        $filter = $request->query('filter', 'banjir');

      
        return view('admin.peta.lihat', compact('dataPeta', 'filter')); 
    }
    // Menampilkan halaman kelola data
    public function kelola()
    {
        
        $dataPeta = \App\Models\PetaBencana::all(); 
        return view('admin.peta.kelola', compact('dataPeta'));
    }

    // Memproses data yang dikirim dari form Edit 
    public function update(Request $request, $id)
    {
        $peta = \App\Models\PetaBencana::findOrFail($id);
        
        $peta->update([
            // Banjir 
            'luas_genangan' => $request->luas_genangan,
            'potensi_banjir' => $request->potensi_banjir,
            'tahun_banjir' => $request->tahun_banjir,
            'jiwa_terdampak_banjir' => $request->jiwa_terdampak_banjir,
            'rumah_warga_banjir' => $request->rumah_warga_banjir,
            'rumah_ibadah_banjir' => $request->rumah_ibadah_banjir,
            'faskes_banjir' => $request->faskes_banjir,
            'fasdik_banjir' => $request->fasdik_banjir,

            // Karhutla 
            'jumlah_hotspot' => $request->jumlah_hotspot,
            'potensi_karhutla' => $request->potensi_karhutla,
            'tahun_karhutla' => $request->tahun_karhutla,
            'jiwa_terdampak_karhutla' => $request->jiwa_terdampak_karhutla,
            'luas_terbakar_karhutla' => $request->luas_terbakar_karhutla,

            // Sumber Informasi
            'sumber_data' => $request->sumber_data,
        ]);

        return redirect()->back()->with('success', 'Data ' . $peta->kabupaten_kota . ' berhasil diperbarui!');
    }
}