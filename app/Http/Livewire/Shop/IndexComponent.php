<?php

namespace App\Http\Livewire\Shop;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination; //paginacion con livewire y boostrap
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $productos = Producto::paginate(9);
        return view('livewire.shop.index-component', compact('productos'))
        ->extends('layouts.app')
        ->section('content');
    }

    public function addToCart(Producto $producto){
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
        $this->emitTo('shop.cart-component','addToCart');
    }
}
