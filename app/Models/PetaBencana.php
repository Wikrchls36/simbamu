<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaBencana extends Model
{
    // Beritahu Laravel untuk menggunakan nama tabel dari migrasi kamu
    protected $table = 'peta_bencanas'; 

    protected $fillable = [
        'kabupaten_kota', 
        'lat_center', 
        'long_center', 
        'geojson', 
        'potensi_banjir', 
        'luas_genangan', 
        'potensi_karhutla', 
        'jumlah_hotspot', 
        'sumber_data', 
        // --- TAMBAHAN BARU DI BAWAH INI ---
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