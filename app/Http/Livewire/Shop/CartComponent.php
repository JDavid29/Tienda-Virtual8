<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartComponent extends Component
{


    public function render()
    {
        return view('livewire.shop.cart.index-component');
    }
}
