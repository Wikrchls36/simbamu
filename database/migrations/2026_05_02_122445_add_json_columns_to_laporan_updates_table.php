<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_updates', function (Blueprint $table) {
            // Menambahkan 6 kolom baru dengan tipe TEXT dan boleh kosong (nullable)
            $table->text('waktu_kejadian')->nullable();
            $table->text('respon_muhammadiyah')->nullable();
            $table->text('penerima_manfaat')->nullable();
            $table->text('tim_respon')->nullable();
            $table->text('kebutuhan')->nullable();
            $table->text('contact_person')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_updates', function (Blueprint $table) {
            // Menghapus kolom jika kita melakukan rollback
            $table->dropColumn([
                'waktu_kejadian',
                'respon_muhammadiyah',
                'penerima_manfaat',
                'tim_respon',
                'kebutuhan',
                'contact_person'
            ]);
        });
    }
};