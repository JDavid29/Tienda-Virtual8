<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Placeholder migration to unblock overall migrations.
 * If a persistent `carritos` table is required later, replace with real schema.
 */
return new class extends Migration
{
    public function up()
    {
        // No-op: intentionally left blank
        // Schema::create('carritos', function (Blueprint $table) { /* ... */ });
    }

    public function down()
    {
        // No-op: intentionally left blank
        // Schema::dropIfExists('carritos');
    }
};
