<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\ListaDeDeseo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SingleProductComponent extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;

    public function mount($productId = null)
    {
        $this->productId = $productId;
        if ($productId) {
            $this->product = Producto::with('category')->find($productId);
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

    public function addToCart()
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para agregar al carrito.',
                'action' => 'cart',
                'productId' => $this->productId,
            ]);
            return redirect()->route('login.client');
        }

        if (! $this->product) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Producto no encontrado.',
            ]);
            return;
        }

        $qty = max(1, (int) $this->quantity);

        try {
            \Cart::session(Auth::id())->add([
                'id' => $this->product->id,
                'name' => $this->product->nombre,
                'price' => $this->product->precio,
                'quantity' => $qty,
                'attributes' => ['image' => $this->product->cover_img ?? null],
                'associatedModel' => $this->product,
            ]);

            $this->emit('cartUpdated');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Producto agregado al carrito.',
                'action' => 'cart',
                'productId' => $this->productId,
            ]);
            $this->dispatchBrowserEvent('closeQuickViewModal');
        } catch (\Throwable $e) {
            Log::error('SingleProduct addToCart failed: '.$e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar el producto al carrito.',
            ]);
        }
    }

    public function addToWishlist()
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para agregar a la lista de deseos.',
                'action' => 'wishlist',
                'productId' => $this->productId,
            ]);
            return redirect()->route('login.client');
        }

        try {
            ListaDeDeseo::firstOrCreate([
                'user_id' => Auth::id(),
                'producto_id' => $this->productId,
            ]);
            $this->emit('wishlistUpdated');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Producto agregado a tu lista de deseos.',
                'action' => 'wishlist',
                'productId' => $this->productId,
            ]);
        } catch (\Throwable $e) {
            Log::error('SingleProduct addToWishlist failed: '.$e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar a la lista de deseos.',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.single-product-component');
    }
}
