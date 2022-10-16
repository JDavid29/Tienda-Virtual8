<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;

class CartComponent extends Component
{
    public $cart;

    protected $listeners = ['add_to_cart'];

    public function add_to_cart(){}

    public function render()
    {
        return view('livewire.shop.cart-component');
    }
}
