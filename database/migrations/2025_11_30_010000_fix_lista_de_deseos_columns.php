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
        // If table does not exist, create it with proper columns
        if (! Schema::hasTable('lista_de_deseos')) {
            Schema::create('lista_de_deseos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('producto_id');
                $table->timestamps();

                $table->unique(['user_id', 'producto_id']);
            });
            return;
        }

        // If table exists, add missing columns
        Schema::table('lista_de_deseos', function (Blueprint $table) {
            if (! Schema::hasColumn('lista_de_deseos', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id')->nullable();
            }
            if (! Schema::hasColumn('lista_de_deseos', 'producto_id')) {
                $table->unsignedBigInteger('producto_id')->after('user_id')->nullable();
            }
        });

        // Attempt to add foreign keys if possible (silently skip on failure)
        try {
            Schema::table('lista_de_deseos', function (Blueprint $table) {
                // only add foreign key if not exists (MySQL will error if duplicate)
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = array_map(function($i){ return $i->getName(); }, $sm->listTableIndexes('lista_de_deseos'));
                if (! in_array('lista_de_deseos_user_id_foreign', $indexes) && Schema::hasColumn('lista_de_deseos', 'user_id')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (! in_array('lista_de_deseos_producto_id_foreign', $indexes) && Schema::hasColumn('lista_de_deseos', 'producto_id')) {
                    $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
                }
            });
        } catch (\Throwable $e) {
            // ignore: foreign key creation may fail if doctrine not available or keys already exist
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Do not drop table automatically to avoid data loss; remove columns if they were added by this migration
        if (Schema::hasTable('lista_de_deseos')) {
            Schema::table('lista_de_deseos', function (Blueprint $table) {
                if (Schema::hasColumn('lista_de_deseos', 'producto_id')) {
                    // drop foreign key if exists
                    try { $table->dropForeign(['producto_id']); } catch (\Throwable $e) {}
                    try { $table->dropColumn('producto_id'); } catch (\Throwable $e) {}
                }
                if (Schema::hasColumn('lista_de_deseos', 'user_id')) {
                    try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
                    try { $table->dropColumn('user_id'); } catch (\Throwable $e) {}
                }
            });
        }
    }
};
