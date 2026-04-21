<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peringatan extends Model
{
    use HasFactory;

    // Menyesuaikan nama tabel agar Laravel tidak mencari tabel 'peringatans'
    protected $table = 'peringatan'; 

    protected $fillable = [
        'user_id',
        'tingkat_potensi',
        'instruksi',
        'status_konfirmasi',
    ];

    // Relasi: 1 Peringatan dimiliki oleh 1 User (MDMC Daerah)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}