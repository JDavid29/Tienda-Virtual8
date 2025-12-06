<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\ListaDeDeseo;
use App\Models\Resena;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SingleProductComponent extends Component
{
    public $productId;
    public $product;
    public $quantity = 1;
    public $reviews = [];
    public $relatedProducts = [];
    public $quickViewProduct = null;

    public function mount($productId = null)
    {
        $this->productId = $productId;
        if ($productId) {
            $this->product = Producto::with('category')->find($productId);
            // load reviews from DB (exclude current user's reviews)
            $query = Resena::with('user')->where('producto_id', $productId)->orderBy('created_at', 'desc');
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
            $this->reviews = $query->get();
            // load related products once in the component to avoid recomputing in the view
            $this->loadRelatedProducts();
        }
    }

    protected function loadRelatedProducts()
    {
        $this->relatedProducts = collect();
        if (! empty($this->product) && isset($this->product->category_id)) {
            $this->relatedProducts = Producto::where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->take(15)
                ->get();
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

    public function addToCart($productId = null)
    {
        $prod = $this->product;
        if ($productId) {
            $prod = Producto::find($productId);
        }

        if (! $prod) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Producto no encontrado.',
            ]);
            return;
        }

        $qty = max(1, (int) $this->quantity);

        try {
            if (Auth::check()) {
                \Cart::session(Auth::id())->add([
                    'id' => $prod->id,
                    'name' => $prod->nombre,
                    'price' => $prod->precio,
                    'quantity' => $qty,
                    'attributes' => ['image' => $prod->cover_img ?? null],
                    'associatedModel' => $prod,
                ]);
            } else {
                \Cart::add([
                    'id' => $prod->id,
                    'name' => $prod->nombre,
                    'price' => $prod->precio,
                    'quantity' => $qty,
                    'attributes' => ['image' => $prod->cover_img ?? null],
                    'associatedModel' => $prod,
                ]);
            }

            $this->emit('cartUpdated');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Producto agregado al carrito.',
                'action' => 'cart',
                'productId' => $prod->id,
            ]);
            $this->dispatchBrowserEvent('closeQuickViewModal');
            // refresh related products in case of any change
            $this->loadRelatedProducts();
        } catch (\Throwable $e) {
            Log::error('SingleProduct addToCart failed: '.$e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar el producto al carrito.',
            ]);
        }
    }

    public function addToWishlist($productId = null)
    {
        if (! Auth::check()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Debes iniciar sesión para agregar a la lista de deseos.',
                'action' => 'wishlist',
                'productId' => $productId ?? $this->productId,
            ]);
            return redirect()->route('login.client');
        }
        $pid = $productId ?? $this->productId;
        try {
            ListaDeDeseo::firstOrCreate([
                'user_id' => Auth::id(),
                'producto_id' => $pid,
            ]);
            $this->emit('wishlistUpdated');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Producto agregado a tu lista de deseos.',
                'action' => 'wishlist',
                'productId' => $pid,
            ]);
            // refresh related products
            $this->loadRelatedProducts();
        } catch (\Throwable $e) {
            Log::error('SingleProduct addToWishlist failed: '.$e->getMessage());
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar a la lista de deseos.',
            ]);
        }
    }

    public function openQuickView($productId = null)
    {
        if (! $productId) {
            return;
        }
        $prod = Producto::with('category')->find($productId);
        if (! $prod) return;
        // Ask the QuickView component to load the product and show the modal
        $this->emitTo('quick-view', 'show', $prod->id);
    }
    public function render()
    {
        return view('livewire.single-product-component');
    }
}
