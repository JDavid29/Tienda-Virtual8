<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Producto;

class Inicio extends Component
{
    public function render()
    {
        // Cargar hasta 5 productos recientes: 3 para el slider y 2 para los banners
        $products = Producto::orderBy('created_at', 'desc')->take(5)->get();
        $sliderProducts = $products->slice(0, 3);
        $bannerProducts = $products->slice(3, 2);

        // Un producto por categoría para el carrusel dinámico de "product-area"
        $categorySlugs = [
            'portatil',
            'licencias',
            'componentes',
            'perifericos',
            'pantallas',
            'almacenamientos',
            'zona-gamers',
        ];

        $categories = Category::whereIn('slug', $categorySlugs)->get()->keyBy('slug');
        $categoryProducts = collect();
        foreach ($categorySlugs as $slug) {
            $cat = $categories->get($slug);
            if ($cat) {
                $prod = $cat->productos()->latest()->first();
                if ($prod) {
                    $categoryProducts->push($prod);
                }
            }
        }

        return view('livewire.index4', [
            'sliderProducts' => $sliderProducts,
            'bannerProducts' => $bannerProducts,
            'categoryProducts' => $categoryProducts,
        ]);
    }
}
