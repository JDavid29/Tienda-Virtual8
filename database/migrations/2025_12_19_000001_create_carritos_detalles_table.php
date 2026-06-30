<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Placeholder migration to unblock overall migrations.
 * If `carritos_detalles` is needed later, replace with actual schema.
 */
return new class extends Migration
{
    public function up()
    {
        // No-op: intentionally left blank
        // Schema::create('carritos_detalles', function (Blueprint $table) { /* ... */ });
    }

    public function down()
    {
        // No-op: intentionally left blank
        // Schema::dropIfExists('carritos_detalles');
    }
};
