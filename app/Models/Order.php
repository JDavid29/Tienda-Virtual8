<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'item_count',
        'is_paid',
        'payment_method',
        'shipping_fullname',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'shipping_phone',
        'notes',
        'billing_fullname',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zipcode',
        'billing_phone',
        'total',
        'paypal_order_id',
        'stripe_session_id',
        'binance_order_id',
        'binance_prepay_id',
    ];

    public function items(){
        return $this->belongsToMany(Producto::class, 'order_items', 'order_id', 'product_id')
        ->withPivot('price','quantity');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
