<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cover_img',
        'shop_id',
    ];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
