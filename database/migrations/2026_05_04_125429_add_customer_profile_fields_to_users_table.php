<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telepon')->nullable()->after('email');
            $table->text('alamat_lengkap')->nullable()->after('telepon');
            $table->string('provinsi')->nullable()->after('alamat_lengkap');
            $table->string('kabupaten')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kabupaten');
            $table->string('kelurahan')->nullable()->after('kecamatan');
            $table->string('kode_pos')->nullable()->after('kelurahan');
            $table->text('google_maps_link')->nullable()->after('kode_pos');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telepon',
                'alamat_lengkap',
                'provinsi',
                'kabupaten',
                'kecamatan',
                'kelurahan',
                'kode_pos',
                'google_maps_link',
            ]);
        });
    }
};