<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            // Menyambungkan laporan dengan ID pengguna (MDMC Daerah)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            
            $table->enum('jenis_bencana', ['Banjir', 'Karhutla']);
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif');
            
            // Kolom ini otomatis membuat 'created_at' (Tanggal Lapor) & 'updated_at' (Waktu Update Terakhir)
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
