<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'rating',
        'user_id',
    ];

    public function owner(){
        // owner o dueños
        return $this->belongsTo(User::class, 'user_id');
    }
}
