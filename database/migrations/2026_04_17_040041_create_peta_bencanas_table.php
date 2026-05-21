<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peta_bencanas', function (Blueprint $table) {
            $table->id();
            
            // Info Wilayah
            $table->string('kabupaten_kota'); 
           

            // Analisis Potensi Banjir
            $table->enum('potensi_banjir', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->double('luas_genangan')->default(0); 

            // Analisis Potensi Karhutla
            $table->enum('potensi_karhutla', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->integer('jumlah_hotspot')->default(0);

            // Sumber Data (BPBD/BMKG/Lainnya)
            $table->string('sumber_data')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peta_bencanas');
    }
};