<?php

namespace App\Http\Livewire\Shop\Cart;

use Livewire\Component;

class IndexComponent extends Component
{
    public function render()
    {
        $cart_items = \Cart::session(auth()->id())->getContent();
        return view('livewire.shop.cart.index-component', compact('cart_items'))
        ->extends('layouts.app')
        ->section('content');
    }

    public function update_quantity($itemId, $quantity){
        \Cart::session(auth()->id())->update($itemId,[
            'quantity' => array(
            'relative' => false,
            'value' => $quantity
            ),
        ]);
    }

    public function delete_item($itemId){
        // delete an item on cart
        \Cart::session(auth()->id())->remove($itemId);
        //Notificacion
    }
}
