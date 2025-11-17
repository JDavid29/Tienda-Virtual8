<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ListProduct extends Component
{
    use WithPagination;

    public function render()
    {
        // retornar a la vista list-product.blade.php o los productos
        $productos=Producto::all();
        return view('livewire.list-product', [
            'productos' => $productos,

        ])->extends("layouts.toolbar")->section("content");
    }

    public function agregarCarrito($productoId)
    {
        $producto = Producto::find($productoId);

        if ($producto) {
            \Cart::session(auth()->id())->add(array(
                'id' => $producto->id,
                'name' => $producto->nombre,
                'price' => $producto->precio,
                'quantity' => 1,
                'attributes' => array([
                    'image' => $producto->cover_img, // campo imagen de la tabla productos
                ]),
                'associatedModel' => $producto
            ));


            /*$this->dispatchBrowserEvent('cart-debug', [
            'items' => \Cart::getContent()->toArray(),
            'total' => \Cart::getTotal(),
            ]);*/
            $this->emit('productAdded');

            session()->flash('message', 'Producto agregado al carrito exitosamente.');
        } else {
            session()->flash('error', 'Producto no encontrado.');
        }
    }
}
