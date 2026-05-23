<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      
        Schema::table('laporan_updates', function (Blueprint $table) {
            $table->text('wk_latitude')->nullable()->after('wk_lokasi');
            $table->text('wk_longitude')->nullable()->after('wk_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_updates', function (Blueprint $table) {
            $table->dropColumn(['wk_latitude', 'wk_longitude']);
        });
    }
};