<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peta_bencana', function (Blueprint $table) {
            // Tambahan Data Banjir
            $table->string('tahun_banjir')->nullable();
            $table->integer('jiwa_terdampak_banjir')->default(0);
            $table->integer('rumah_warga_banjir')->default(0);
            $table->integer('rumah_ibadah_banjir')->default(0);
            $table->integer('faskes_banjir')->default(0);
            $table->integer('fasdik_banjir')->default(0);

            // Tambahan Data Karhutla
            $table->string('tahun_karhutla')->nullable();
            $table->integer('jiwa_terdampak_karhutla')->default(0);
            $table->double('luas_terbakar_karhutla')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('peta_bencanas', function (Blueprint $table) {
            $table->dropColumn([
                'tahun_banjir', 'jiwa_terdampak_banjir', 'rumah_warga_banjir', 'rumah_ibadah_banjir', 
                'faskes_banjir', 'fasdik_banjir',
                'tahun_karhutla', 'jiwa_terdampak_karhutla', 'luas_terbakar_karhutla'
            ]);
        });
    }
};