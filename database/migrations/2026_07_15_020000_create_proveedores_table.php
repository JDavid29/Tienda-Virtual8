<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crea la tabla `proveedores`.
 *
 * Originalmente esta tabla se creó a mano desde el panel de Voyager,
 * por lo que no existía migración. Esta la formaliza para que un
 * `migrate:fresh` reproduzca el esquema correcto.
 *
 * Un proveedor agrupa productos (relación 1:N con `productos.proveedor_id`).
 */
class CreateProveedoresTable extends Migration
{
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            // nullable: en el alta rápida de Voyager solo el nombre es obligatorio
            $table->string('nombre')->nullable();
            $table->string('contacto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
