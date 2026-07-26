<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE barangs MODIFY harga BIGINT UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE barangs MODIFY harga DECIMAL(15,2) NOT NULL DEFAULT 0');
    }
};