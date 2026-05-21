<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanUpdate extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi
    protected $guarded = [];

    
    protected $casts = [
        'wk_waktu' => 'array',
        'wk_kejadian' => 'array',
        'wk_lokasi' => 'array',
        'resp_kluster' => 'array',
        'resp_lokasi' => 'array',
        'resp_keterangan' => 'array',
        'pm_kegiatan' => 'array',
        'pm_tanggal' => 'array',
        'pm_jumlah' => 'array',
        'tim_kluster' => 'array',
        'tim_total' => 'array',
        'tim_pulang' => 'array',
        'tim_bertugas' => 'array',
        'keb_item' => 'array',
        'keb_jumlah' => 'array',
        'cp_nama' => 'array',
        'cp_nohp' => 'array',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}