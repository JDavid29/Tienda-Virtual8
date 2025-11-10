<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $productos = Producto::paginate(9);
        return view('livewire.shop.index-component', compact('productos'))
        ->extends('layouts.app')
        ->section('content');
    }

    public function addToCart($productoId)
{
    try {
        $producto = Producto::find($productoId);

        if (!$producto) {
            session()->flash('error', 'Producto no encontrado');
            return;
        }

        // Agregar al carrito
        \Cart::add([
            'id' => $producto->id,
            'name' => $producto->nombre,
            'price' => $producto->precio,
            'quantity' => 1,
            'attributes' => [
                'cover_img' => $producto->cover_img ?? 'default.jpg',
            ]
        ]);

        // Emitir eventos para actualizar el toolbar
        $this->emit('productAdded');
        $this->emitTo('toolbar-component', 'cartUpdated');

        session()->flash('success', 'Producto agregado al carrito');

    } catch (\Exception $e) {
        session()->flash('error', 'Error al agregar producto: ' . $e->getMessage());
    }
}
}
