<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peringatan; 
use Illuminate\Support\Facades\Auth;

class PeringatanController extends Controller
{
    public function index()
    {
        
        $dataPeringatan = Peringatan::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('pengguna.peringatan.index', compact('dataPeringatan'));
    }

    
    public function konfirmasi($id)
{
   
    \Illuminate\Support\Facades\DB::table('peringatan')
        ->where('id', $id)
        ->update([
            'status_konfirmasi' => 'Telah Direspon',
            'updated_at' => now() 
        ]);

    return back()->with('success', 'Peringatan telah berhasil dikonfirmasi!');
}
}