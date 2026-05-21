<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaBencana extends Model
{
    
    protected $table = 'peta_bencana'; 

    protected $fillable = [
        'kabupaten_kota', 
        'potensi_banjir', 
        'luas_genangan', 
        'potensi_karhutla', 
        'jumlah_hotspot', 
        'sumber_data', 
        'tahun_banjir', 
        'jiwa_terdampak_banjir',
        'rumah_warga_banjir',
        'rumah_ibadah_banjir', 
        'faskes_banjir', 
        'fasdik_banjir',
        'tahun_karhutla', 
        'jiwa_terdampak_karhutla', 
        'luas_terbakar_karhutla'
    ];
}