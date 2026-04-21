<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('regency')->nullable()->after('email');
            $table->string('no_whatsapp')->nullable()->after('regency');
            $table->decimal('latitude', 10, 8)->nullable()->after('no_whatsapp');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['regency', 'no_whatsapp', 'latitude', 'longitude']);
        });
    }
};