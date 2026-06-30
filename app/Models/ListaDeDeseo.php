<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaDeDeseo extends Model
{
    use HasFactory;

    protected $table = 'lista_de_deseos';

    // Usa timestamps para que Eloquent complete `created_at` y `updated_at`
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'producto_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
