<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('lista_de_deseos')) {
            Schema::create('lista_de_deseos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('producto_id');
                $table->timestamps();
                $table->unique(['user_id', 'producto_id']);
            });

            // Foreign keys are optional; avoid failing if tables or engines differ
            try {
                Schema::table('lista_de_deseos', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Ignore: FKs may already exist or engine may not support
            }
        }
    }

    public function down(): void
    {
        // Do not drop table to avoid data loss
    }
};
