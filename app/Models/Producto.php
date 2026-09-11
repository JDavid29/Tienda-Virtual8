<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\Venta;
use App\Models\Resena;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'nombre',
        'descripcion',
        'descripcion_larga',
        'precio',
        'cantidad',
        'referencia',
        'cover_img',
        'images',      // JSON: array de rutas de imágenes adicionales
        'shop_id',
        'proveedor_id',
    ];

    // Voyager guarda multiple_images como JSON; se convierte a array automáticamente
    protected $casts = [
        'images' => 'array',
    ];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Un producto pertenece a un proveedor (proveedor_id ya existe en la tabla)
    public function proveedor()
    {
        return $this->belongsTo(Proveedore::class, 'proveedor_id');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'producto_id');
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'producto_venta', 'producto_id', 'venta_id')
                    ->withPivot( 'precio');
    }
}
