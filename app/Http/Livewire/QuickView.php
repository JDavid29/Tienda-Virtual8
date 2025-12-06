<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\ListaDeDeseo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuickView extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;

    protected $listeners = ['show'];

    public function show($id)
    {
        $this->productId = $id;
        $this->product = Producto::with('category')->find($id);
        $this->quantity = 1;
        $this->dispatchBrowserEvent('openQuickViewModal');
    }

    public function addToCart()
    {
        $productId = $this->productId;
        $producto = Producto::find($productId);
        if (! $producto) {
            session()->flash('error', 'Producto no encontrado.');
            return;
        }

        $qty = max(1, (int) $this->quantity);

        try {
            if (Auth::check()) {
                \Cart::session(Auth::id())->add([
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $producto->precio,
                    'quantity' => $qty,
                    'attributes' => ['image' => $producto->cover_img ?? null],
                    'associatedModel' => $producto,
                ]);
            } else {
                \Cart::add([
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $producto->precio,
                    'quantity' => $qty,
                    'attributes' => ['image' => $producto->cover_img ?? null],
                    'associatedModel' => $producto,
                ]);
            }

            session()->flash('message', 'Producto agregado al carrito.');
            $this->emit('cartUpdated');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Producto agregado al carrito.',
                'action' => 'cart',
                'productId' => $productId,
            ]);
        } catch (\Throwable $e) {
            Log::error('QuickView addToCart failed: '.$e->getMessage());
            session()->flash('error', 'No se pudo agregar el producto al carrito.');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar el producto al carrito.',
                'action' => 'cart',
                'productId' => $productId,
            ]);
        }
    }

    public function increaseQuantity()
    {
        $this->quantity = max(1, (int) $this->quantity + 1);
    }

    public function decreaseQuantity()
    {
        $this->quantity = max(1, (int) $this->quantity - 1);
    }

    public function addToWishlist()
    {
        $productId = $this->productId;
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para agregar a la lista de deseos.',
                'action' => 'wishlist',
                'productId' => $productId,
            ]);
            return redirect()->route('login.client');
        }

        try {
            ListaDeDeseo::firstOrCreate([
                'user_id' => Auth::id(),
                'producto_id' => $productId,
            ]);
            session()->flash('message', 'Producto agregado a tu lista de deseos.');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Producto agregado a tu lista de deseos.',
                'action' => 'wishlist',
                'productId' => $productId,
            ]);
            $this->emit('wishlistUpdated');
        } catch (\Throwable $e) {
            Log::error('QuickView addToWishlist failed: '.$e->getMessage());
            session()->flash('error', 'No se pudo agregar a la lista de deseos.');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar a la lista de deseos.',
                'action' => 'wishlist',
                'productId' => $productId,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.quick-view');
    }
}
