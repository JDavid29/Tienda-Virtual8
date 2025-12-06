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
            try {
                if (auth()->check()) {
                    \Cart::session(auth()->id())->add([
                        'id' => $producto->id,
                        'name' => $producto->nombre,
                        'price' => $producto->precio,
                        'quantity' => 1,
                        'attributes' => ['image' => $producto->cover_img],
                        'associatedModel' => $producto
                    ]);
                } else {
                    \Cart::add([
                        'id' => $producto->id,
                        'name' => $producto->nombre,
                        'price' => $producto->precio,
                        'quantity' => 1,
                        'attributes' => ['image' => $producto->cover_img],
                        'associatedModel' => $producto
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error('ListProduct agregarCarrito failed: '.$e->getMessage());
                session()->flash('error', 'No se pudo agregar el producto al carrito.');
                return;
            }


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
