<?php

namespace App\Http\Livewire;

use Darryldecode\Cart\Facades\CartFacade as Cart;
use Livewire\Component;
use App\Models\Producto;
use App\Models\ListaDeDeseo;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ComponentShop3Column extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // number of items per page
    public $perPage = 12;

    // allow resetting page when filters change in future
    protected $queryString = ['page'];

    public function updating($name, $value)
    {
        // if we later add filters, reset page on update
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function getProductsQueryProperty()
    {
        // find smartwatch category id
        $cat = DB::table('categories')->where('slug', 'smartwatch')->orWhere('name', 'Smartwatch')->first();
        if (! $cat) {
            return Producto::query()->whereRaw('0 = 1');
        }

        return Producto::where('category_id', $cat->id)->with('category')->orderBy('created_at', 'desc');
    }

    public function getProductsProperty()
    {
        return $this->productsQuery->paginate($this->perPage);
    }

    public function addToCart($productId)
    {
        $producto = Producto::find($productId);
        if (! $producto) {
            session()->flash('error', 'Producto no encontrado.');
            return;
        }



        try {
            if (Auth::check()) {
                Cart::session(Auth::id())->add([
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $producto->precio,
                    'quantity' => 1,
                    'attributes' => ['image' => $producto->cover_img ?? null],
                    'associatedModel' => $producto,
                ]);
            } else {
                Cart::add([
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $producto->precio,
                    'quantity' => 1,
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
            Log::error('Add to cart failed: '.$e->getMessage());
            session()->flash('error', 'No se pudo agregar el producto al carrito.');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar el producto al carrito.',
                'action' => 'cart',
                'productId' => $productId,
            ]);
        }
    }

    public function addToWishlist($productId)
    {
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
            // Inform toolbar and other listeners that wishlist changed
            $this->emit('wishlistUpdated');
        } catch (\Throwable $e) {
            Log::error('Add to wishlist failed: '.$e->getMessage());
            session()->flash('error', 'No se pudo agregar a la lista de deseos.');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'No se pudo agregar a la lista de deseos.',
                'action' => 'wishlist',
                'productId' => $productId,
            ]);
        }
    }

    /**
     * Navigate to single product view.
     * Using Livewire redirect so it works both in SPA flows and normal requests.
     */
    public function viewProduct($productId)
    {
        return redirect()->route('single-product', ['id' => $productId]);
    }

    public function render()
    {
        return view('livewire.component-shop3-column', [
            'products' => $this->products,
        ]);
    }
}
