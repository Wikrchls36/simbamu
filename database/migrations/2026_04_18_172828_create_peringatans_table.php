<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan namanya 'peringatan' sesuai dengan Model kita
        Schema::create('peringatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tingkat_potensi'); 
            $table->text('instruksi');
            $table->string('status_konfirmasi')->default('Belum Direspon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peringatan');
    }
};