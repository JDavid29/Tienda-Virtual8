<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;


class Venta extends Model
{
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_venta', 'venta_id', 'producto_id')
                    ->withPivot( 'precio');
    }
}
