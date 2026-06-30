<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('binance_order_id')->nullable()->after('stripe_session_id');
            $table->string('binance_prepay_id')->nullable()->after('binance_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['binance_order_id', 'binance_prepay_id']);
        });
    }
};
