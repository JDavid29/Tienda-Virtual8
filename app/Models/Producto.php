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
        'precio',
        'cover_img',
        'shop_id',
    ];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
