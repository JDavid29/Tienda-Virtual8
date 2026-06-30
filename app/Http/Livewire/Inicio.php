<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\DB;

class Inicio extends Component
{
    public function agregarCarrito($productoId)
    {
        $prod = Producto::find($productoId);
        if (!$prod) return;

        $item = [
            'id'         => $prod->id,
            'name'       => $prod->nombre,
            'price'      => (float) $prod->precio,
            'quantity'   => 1,
            'attributes' => ['image' => $prod->cover_img],
        ];

        if (auth()->check()) {
            Cart::session(auth()->id())->add($item);
        } else {
            Cart::add($item);
        }

        $this->emit('cartUpdated');
        $this->dispatchBrowserEvent('product-added', ['nombre' => $prod->nombre]);
    }

    public function buyNow($productoId)
    {
        $this->agregarCarrito($productoId);
        return redirect()->route('cart');
    }

    public function render()
    {
        // Slider: 3 productos más recientes
        $sliderProducts = Producto::latest()->take(3)->get();

        // Nuevas llegadas: 8 productos más recientes
        $newProducts = Producto::latest()->take(8)->get();

        // Más vendidos: 8 productos con mayor precio (proxy mientras no haya campo ventas)
        $bestSellers = Producto::orderByDesc('precio')->take(8)->get();

        // Destacados: 8 productos aleatorios
        $featuredProducts = Producto::inRandomOrder()->take(8)->get();

        // Secciones por categoría
        $categories = DB::table('categories')->orderBy('order')->get();
        $categoryGroups = collect();
        foreach ($categories as $cat) {
            $prods = Producto::where('category_id', $cat->id)->latest()->take(8)->get();
            if ($prods->count()) {
                $categoryGroups->push(['category' => $cat, 'products' => $prods]);
            }
        }

        return view('livewire.index4', compact(
            'sliderProducts',
            'newProducts',
            'bestSellers',
            'featuredProducts',
            'categoryGroups'
        ));
    }
}
