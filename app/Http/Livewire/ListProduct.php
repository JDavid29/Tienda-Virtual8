<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ListProduct extends Component
{
    use WithPagination;

    public $categoryId   = null;
    public $categoryName = null;

    public function mount($slug = null, $categoryId = null, $categoryName = null)
    {
        if ($slug) {
            $category = \DB::table('categories')->where('slug', $slug)->first();
            if ($category) {
                $this->categoryId   = $category->id;
                $this->categoryName = $category->name;
            }
        } else {
            $this->categoryId   = $categoryId;
            $this->categoryName = $categoryName;
        }
    }

    public function render()
    {
        $query = Producto::query();

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        $productos = $query->get();

        return view('livewire.list-product', [
            'productos'    => $productos,
            'categoryName' => $this->categoryName,
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
            $this->emit('cartUpdated');
            $this->dispatchBrowserEvent('product-added', ['nombre' => $producto->nombre]);

            session()->flash('message', 'Producto agregado al carrito exitosamente.');
        } else {
            session()->flash('error', 'Producto no encontrado.');
        }
    }
}
