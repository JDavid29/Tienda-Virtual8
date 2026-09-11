<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega `productos.proveedor_id` — relación N:1 hacia `proveedores`.
 *
 * Corre DESPUÉS de create_proveedores_table (timestamp 020000) para que
 * la clave foránea pueda referenciarla.
 *
 * nullOnDelete: si se borra un proveedor, sus productos quedan sin
 * proveedor en vez de borrarse.
 */
class AddProveedorIdToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('proveedor_id')
                  ->nullable()
                  ->after('shop_id')
                  ->constrained('proveedores')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn('proveedor_id');
        });
    }
}
