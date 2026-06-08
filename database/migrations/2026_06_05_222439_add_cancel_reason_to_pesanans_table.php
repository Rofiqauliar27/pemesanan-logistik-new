<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->text('cancel_reason')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('cancel_reason');
        });
    }
};