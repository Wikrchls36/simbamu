<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_bencana',
        'status',
    ];

    // Relasi: 1 Laporan dimiliki oleh 1 User (Pelapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}