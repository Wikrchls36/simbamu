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
        Schema::table('peringatan', function (Blueprint $table) {
            
            $table->enum('jenis_bencana', ['Banjir', 'Karhutla'])->after('tingkat_potensi');
        });
    }

    public function down()
    {
        Schema::table('peringatan', function (Blueprint $table) {
            $table->dropColumn('jenis_bencana');
        });
    }
};