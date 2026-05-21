<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_updates', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel laporans utama
            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade');
            $table->integer('update_ke')->default(1);
            $table->date('tanggal_sitrep');

            // A. Waktu & Dampak
            $table->json('wk_waktu')->nullable();
            $table->json('wk_kejadian')->nullable();
            $table->json('wk_lokasi')->nullable();
            
            $table->integer('dampak_meninggal')->default(0);
            $table->integer('dampak_luka')->default(0);
            $table->integer('dampak_hilang')->default(0);
            $table->integer('dampak_pengungsi')->default(0);
            $table->integer('dampak_terdampak')->default(0);
            
            $table->text('dampak_material')->nullable();
            $table->text('lokasi_poskor')->nullable();
            $table->text('lokasi_pos_pelayanan')->nullable();

            // B & C. Kronologi & Situasi
            $table->text('kronologi')->nullable();
            $table->text('situasi_terkini')->nullable();

            // D. Respon Muhammadiyah
            $table->json('resp_kluster')->nullable();
            $table->json('resp_lokasi')->nullable();
            $table->json('resp_keterangan')->nullable();

            // E. Penerima Manfaat
            $table->json('pm_kegiatan')->nullable();
            $table->json('pm_tanggal')->nullable();
            $table->json('pm_jumlah')->nullable();

            // F. Tim Respon MDMC
            $table->json('tim_kluster')->nullable();
            $table->json('tim_total')->nullable();
            $table->json('tim_pulang')->nullable();
            $table->json('tim_bertugas')->nullable();
            
            $table->integer('tim_total_semua')->default(0);
            $table->integer('tim_laki')->default(0);
            $table->integer('tim_perempuan')->default(0);
            $table->text('asal_instansi')->nullable();

            // G. Kebutuhan
            $table->json('keb_item')->nullable();
            $table->json('keb_jumlah')->nullable();

            // H & I. Sumber Informasi & Kontak
            $table->text('sumber_informasi')->nullable();
            $table->json('cp_nama')->nullable();
            $table->json('cp_nohp')->nullable();

            // J & K. Rekening Donasi & Penutup
            $table->text('rekening_donasi')->nullable();
            $table->string('penutup_lokasi')->nullable();
            $table->date('penutup_tanggal')->nullable();
            $table->string('penutup_nama_tim')->nullable();
            
            // Lampiran Foto/PDF
            $table->longText('foto_dokumentasi')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_updates');
    }
};