<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Proveedore extends Model
{
    protected $fillable = ['nombre', 'contacto'];

    // Un proveedor puede tener muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'proveedor_id');
    }
}
