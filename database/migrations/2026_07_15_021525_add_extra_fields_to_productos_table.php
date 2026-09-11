<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega a `productos` los campos que el panel admin y la ficha de
 * producto ya usaban pero que se habían creado a mano (sin migración):
 *
 *   - descripcion_larga : descripción extendida (HTML del editor)
 *   - cantidad          : stock disponible (se edita inline en el browse)
 *   - referencia        : enlace/nota de origen del producto (texto libre)
 *
 * Todos nullable / con default para no romper el ProductoFactory,
 * que solo rellena nombre, descripcion, precio y category_id.
 */
class AddExtraFieldsToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->text('descripcion_larga')->nullable()->after('descripcion');
            $table->integer('cantidad')->default(0)->after('precio');
            $table->text('referencia')->nullable()->after('cantidad');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['descripcion_larga', 'cantidad', 'referencia']);
        });
    }
}
