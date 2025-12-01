<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use App\Models\ListaDeDeseo;

use Illuminate\Support\Facades\Log;

/**
 * Componente Livewire: Wishlist
 * Clase: App\Http\Livewire\Wishlist
 * Alias del componente para Blade: `wishlist`
 * Renderiza la vista Livewire `resources/views/livewire/wishlist.blade.php`
 */
class Wishlist extends Component
{
    // Aquí puedes añadir propiedades que la vista necesite, por ejemplo:
    // public $items = [];
    public $items = [];

    public $listeners = ['cartUpdated' => 'updateList'];

    // Método render: devuelve la vista Livewire que representa el wishlist
    public function render()
    {
        // load wishlist items for authenticated user
        if (Auth::check()) {
            $this->items = ListaDeDeseo::where('user_id', Auth::id())
                ->with('producto')
                ->get();
        }

        return view('livewire.wishlist', [
            'items' => $this->items,
        ]);
    }
    public function mount()
    {
        if (! Auth::check()) {
            // redirect to login page if not authenticated
            redirect()->route('login.client');
        }
    }

    public function agregarCarrito($productoId)
    {
        if (! Auth::check()) {
            return redirect()->route('login.client');
        }

        $producto = Producto::find($productoId);
        if (! $producto) {
            session()->flash('error', 'Producto no encontrado.');
            return $this->updateList();
        }

        try {
            // add to cart for current user session
            \Cart::session(Auth::id())->add([
                'id' => $producto->id,
                'name' => $producto->nombre,
                'price' => $producto->precio,
                'quantity' => 1,
                'attributes' => [
                    'image' => $producto->cover_img ?? null,
                ],
                'associatedModel' => $producto,
            ]);

            // remove from wishlist if exists
            ListaDeDeseo::where('user_id', Auth::id())
                ->where('producto_id', $producto->id)
                ->delete();

            session()->flash('message', 'Producto agregado al carrito y eliminado de la lista de deseos.');
            $this->emit('cartUpdated');
        } catch (\Throwable $e) {
            Log::error('Error adding wishlist item to cart: ' . $e->getMessage());
            session()->flash('error', 'No se pudo agregar el producto al carrito.');
        }

        $this->updateList();
    }

    public function deleteItem($wishlistId)
    {
        if (! Auth::check()) {
            return redirect()->route('login.client');
        }

        $wish = ListaDeDeseo::where('id', $wishlistId)->where('user_id', Auth::id())->first();
        if (! $wish) {
            session()->flash('error', 'Elemento no encontrado en tu lista de deseos.');
            return $this->updateList();
        }

        try {
            $wish->delete();
            session()->flash('message', 'Elemento eliminado de la lista de deseos.');
            $this->emit('cartUpdated');
        } catch (\Throwable $e) {
            Log::error('Error deleting wishlist item: ' . $e->getMessage());
            session()->flash('error', 'No se pudo eliminar el elemento.');
        }

        $this->updateList();
    }

    public function updateList()
    {
        if (Auth::check()) {
            $this->items = ListaDeDeseo::where('user_id', Auth::id())->with('producto')->get();
        } else {
            $this->items = collect();
        }
    }

}
