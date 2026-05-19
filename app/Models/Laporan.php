<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'jenis_bencana', 'latitude', 'longitude', 'status'];

    public function user() { return $this->belongsTo(User::class); }
    public function updates() { return $this->hasMany(LaporanUpdate::class)->orderBy('update_ke', 'desc'); }

    
}