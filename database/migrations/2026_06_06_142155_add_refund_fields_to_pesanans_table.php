<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('pesanans', function (Blueprint $table) {

        $table->string('refund_bank')->nullable();

        $table->string('refund_account_number')->nullable();

        $table->string('refund_account_name')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('pesanans', function (Blueprint $table) {

        $table->dropColumn([
            'refund_bank',
            'refund_account_number',
            'refund_account_name'
        ]);

    });
}
};
