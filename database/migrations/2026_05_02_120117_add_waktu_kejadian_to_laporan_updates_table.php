<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_updates', function (Blueprint $table) {
            // Kita gunakan tipe data text/json karena isinya array
            $table->text('waktu_kejadian')->nullable()->after('tanggal_sitrep'); 
        });
    }

    public function down()
    {
        Schema::table('laporan_updates', function (Blueprint $table) {
            $table->dropColumn('waktu_kejadian');
        });
    }
};