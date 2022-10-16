<?php

namespace App\Http\Livewire\Shop;

use App\Models\Producto;
use Livewire\Component;

class IndexComponent extends Component
{
    public function render()
    {
        $productos = Producto::all();
        return view('livewire.shop.index-component', compact('productos'))
        ->extends('layouts.app')
        ->section('content');
    }

    public function add_to_cart(Producto $producto){
        // dd($producto); //observacion pendiente por los datos a mirar al presionar button

        // add the product to cart
        \Cart::session(auth()->id())->add(array(
        'id' => $producto->id,
        'name' => $producto->nombre,
        'price' => $producto->precio,
        'quantity' => 1,
        'attributes' => array(),
        'associatedModel' => $producto
        ));

        $this->emit('message', 'El Producto se ha agregado correctamente.');
        $this->emitTo('shop.cart-component','add_to_cart');
    }
}
