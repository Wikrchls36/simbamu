<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peringatan
        Schema::create('peringatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tingkat_potensi', ['Monitoring', 'Siaga', 'Waspada']);
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