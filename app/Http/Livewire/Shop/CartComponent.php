<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;

class CartComponent extends Component
{
    public $cart;

    protected $listeners = ['addToCart'];

    public function addToCart(){}

    public function render()
    {
        return view('livewire.shop.cart-component');
    }
}
