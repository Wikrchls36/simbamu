<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetaBencana; // Menggunakan model yang sama dengan Admin

class PetaController extends Controller
{
    public function index()
{
    // Ini akan mencari file di resources/views/pengguna/peta/index.blade.php
    return view('pengguna.peta.index'); 
}

public function lihat(Request $request)
{
    $dataPeta = \App\Models\PetaBencana::all(); 
    $filter = $request->query('filter', 'banjir');

    // Nanti kita buat file ini di resources/views/pengguna/peta/lihat.blade.php
    return view('pengguna.peta.lihat', compact('dataPeta', 'filter')); 
}
}