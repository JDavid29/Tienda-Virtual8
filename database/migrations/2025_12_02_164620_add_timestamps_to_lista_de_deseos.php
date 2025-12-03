<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('lista_de_deseos')) {
            // If table doesn't exist, create it with timestamps
            Schema::create('lista_de_deseos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('producto_id');
                $table->timestamps();
                $table->unique(['user_id', 'producto_id']);
            });
            return;
        }

        // Add timestamps if missing
        if (! Schema::hasColumn('lista_de_deseos', 'created_at') || ! Schema::hasColumn('lista_de_deseos', 'updated_at')) {
            Schema::table('lista_de_deseos', function (Blueprint $table) {
                if (! Schema::hasColumn('lista_de_deseos', 'created_at')) {
                    $table->timestamp('created_at')->nullable()->after('producto_id');
                }
                if (! Schema::hasColumn('lista_de_deseos', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->after('created_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lista_de_deseos')) {
            Schema::table('lista_de_deseos', function (Blueprint $table) {
                if (Schema::hasColumn('lista_de_deseos', 'updated_at')) {
                    try { $table->dropColumn('updated_at'); } catch (\Throwable $e) {}
                }
                if (Schema::hasColumn('lista_de_deseos', 'created_at')) {
                    try { $table->dropColumn('created_at'); } catch (\Throwable $e) {}
                }
            });
        }
    }
};
