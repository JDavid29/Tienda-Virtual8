<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartComponent extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;

    protected $listeners = ['addToCart' => 'updateCart', 'cartUpdated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        if (auth()->check()) {
            $this->cartItems = Cart::session(auth()->id())->getContent();
            $this->cartTotal = Cart::session(auth()->id())->getTotal();
        } else {
            $this->cartItems = Cart::getContent();
            $this->cartTotal = Cart::getTotal();
        }
    }

    public function render()
    {
        return view('livewire.shop.cart-component');
    }
}
