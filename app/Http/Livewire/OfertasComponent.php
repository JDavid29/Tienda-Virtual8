<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;

class OfertasComponent extends Component
{
    use WithPagination;

    public function render()
    {
        // Muestra los productos más recientes como "ofertas del día"
        // Cuando se agregue campo descuento, filtrar por: ->where('descuento', '>', 0)
        $productos = Producto::orderBy('created_at', 'desc')
            ->paginate(12);

        return view('livewire.ofertas-component', compact('productos'))
            ->extends('layouts.toolbar')
            ->section('content');
    }

    public function agregarCarrito($productoId)
    {
        $producto = Producto::find($productoId);
        if (!$producto) {
            session()->flash('error', 'Producto no encontrado.');
            return;
        }

        try {
            $cartData = [
                'id'         => $producto->id,
                'name'       => $producto->nombre,
                'price'      => $producto->precio,
                'quantity'   => 1,
                'attributes' => ['image' => $producto->cover_img],
            ];

            auth()->check()
                ? \Cart::session(auth()->id())->add($cartData)
                : \Cart::add($cartData);
        } catch (\Throwable $e) {
            session()->flash('error', 'No se pudo agregar el producto.');
            return;
        }

        $this->emit('cartUpdated');
        $this->dispatchBrowserEvent('product-added', ['nombre' => $producto->nombre]);
        session()->flash('message', 'Producto agregado al carrito.');
    }
}
