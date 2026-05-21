<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetaBencana; 

class PetaController extends Controller
{
    public function index()
{
    
    return view('pengguna.peta.index'); 
}

public function lihat(Request $request)
{
    $dataPeta = \App\Models\PetaBencana::all(); 
    $filter = $request->query('filter', 'banjir');

    
    return view('pengguna.peta.lihat', compact('dataPeta', 'filter')); 
}
}