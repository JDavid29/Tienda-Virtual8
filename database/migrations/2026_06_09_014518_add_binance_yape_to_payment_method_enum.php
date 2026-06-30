<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddBinanceYapeToPaymentMethodEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM(
            'cash_on_delivery',
            'paypal',
            'stripe',
            'card',
            'binance',
            'yape'
        ) NOT NULL DEFAULT 'cash_on_delivery'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM(
            'cash_on_delivery',
            'paypal',
            'stripe',
            'card'
        ) NOT NULL DEFAULT 'cash_on_delivery'");
    }
}
