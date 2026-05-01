<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('id');
            $table->string('snap_token')->nullable()->after('order_id');
            $table->string('payment_status')->default('belum_bayar')->after('status');
            $table->string('payment_type')->nullable()->after('payment_status');
            $table->string('transaction_status')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'snap_token',
                'payment_status',
                'payment_type',
                'transaction_status',
            ]);
        });
    }
};