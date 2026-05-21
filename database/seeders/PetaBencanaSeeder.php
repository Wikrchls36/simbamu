<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PetaBencanaSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar 14 Kabupaten/Kota di Kalimantan Barat
        $daerah = [
            'Kota Pontianak', 
            'Kabupaten Kubu Raya', 
            'Kabupaten Mempawah',
            'Kota Singkawang', 
            'Kabupaten Sambas', 
            'Kabupaten Bengkayang',
            'Kabupaten Landak', 
            'Kabupaten Sanggau', 
            'Kabupaten Sekadau',
            'Kabupaten Sintang', 
            'Kabupaten Melawi', 
            'Kabupaten Kapuas Hulu',
            'Kabupaten Ketapang', 
            'Kabupaten Kayong Utara'
        ];

        // Memasukkan setiap daerah ke tabel database
        foreach ($daerah as $nama) {
            DB::table('peta_bencana')->insert([
                'kabupaten_kota' => $nama,
                'potensi_banjir' => 'Rendah',
                'luas_genangan' => 0,
                'potensi_karhutla' => 'Rendah',
                'jumlah_hotspot' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}