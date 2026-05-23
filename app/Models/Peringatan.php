<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peringatan extends Model
{
    use HasFactory;

    
    protected $table = 'peringatan'; 

    protected $fillable = [
        'user_id',
        'tingkat_potensi',
        'jenis_bencana',
        'instruksi',
        'status_konfirmasi',

    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}