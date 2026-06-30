<?php

namespace App\Http\Livewire;

use Darryldecode\Cart\Facades\CartFacade as Cart;
use Livewire\Component;
use App\Models\Producto;
use App\Models\ListaDeDeseo;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ComponentShop4Column extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 12;
    public $sort = 'trending';

    public function getProductsQueryProperty()
    {
        $query = Producto::with('category')
            ->withAvg('resenas', 'calificacion')
            ->whereHas('category', function ($q) {
                $q->where('slug', 'accesorios')->orWhere('name', 'accesorios');
            });

        switch ($this->sort) {
            case 'name_asc':
                $query->orderBy('nombre', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nombre', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'rating_asc':
                $query->orderBy('resenas_avg_calificacion', 'asc');
                break;
            case 'rating_desc':
                $query->orderBy('resenas_avg_calificacion', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'trending':
            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        return $this->productsQuery->paginate($this->perPage);
    }

    public function getTotalProductsProperty()
    {
        return $this->productsQuery->count();
    }

    public function viewProduct($productId)
    {
        return redirect()->route('single-product', ['id' => $productId]);
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

    public function render()
    {
        return view('livewire.component-shop4-column', [
            'products' => $this->products,
            'totalProducts' => $this->totalProducts,
        ]);
    }
}
