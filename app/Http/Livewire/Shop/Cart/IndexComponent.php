<?php

namespace App\Http\Livewire\Shop\Cart;

use Livewire\Component;

use Darryldecode\Cart\Facades\CartFacade as Cart;


class IndexComponent extends Component
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
            $this->cartItems = Cart::session(auth()->id())->getContent()->values()->toArray();
            $this->cartTotal = Cart::session(auth()->id())->getTotal();
        } else {
            $this->cartItems = Cart::getContent();
            $this->cartTotal = Cart::getTotal();
        }
    }

    public function render()
    {
        return view('livewire.shop.cart.index-component')
        ->extends('layouts.toolbar')
        ->section('content');
    }

    public function updateQuantity($itemId, $quantity){
        \Cart::session(auth()->id())->update($itemId,[
            'quantity' => array(
            'relative' => false,
            'value' => $quantity
            ),
        ]);
    }

    public function deleteItem($itemId){
        // delete an item on cart
        \Cart::session(auth()->id())->remove($itemId);
        //Notificacion
        $this->updateCart();
        $this->emit("updateCart");
    }
}
