<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peringatan; // Pastikan Model Peringatan sudah ada
use Illuminate\Support\Facades\Auth;

class PeringatanController extends Controller
{
    public function index()
    {
        // Mengambil data peringatan khusus untuk user yang sedang login
        $dataPeringatan = Peringatan::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('pengguna.peringatan.index', compact('dataPeringatan'));
    }

    // Fungsi tambahan jika nanti ingin konfirmasi pesan
    public function konfirmasi($id)
{
    // Jalur Tol yang sudah disempurnakan
    \Illuminate\Support\Facades\DB::table('peringatan')
        ->where('id', $id)
        ->update([
            'status_konfirmasi' => 'Telah Direspon',
            'updated_at' => now() // <--- Tambahkan ini agar waktu update tercatat
        ]);

    return back()->with('success', 'Peringatan telah berhasil dikonfirmasi!');
}
}