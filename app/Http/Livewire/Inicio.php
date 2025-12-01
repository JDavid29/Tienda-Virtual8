<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;

class Inicio extends Component
{
    public function render()
    {
        // Cargar hasta 5 productos recientes: 3 para el slider y 2 para los banners
        $products = Producto::orderBy('created_at', 'desc')->take(5)->get();
        $sliderProducts = $products->slice(0, 3);
        $bannerProducts = $products->slice(3, 2);

        return view('livewire.index4', [
            'sliderProducts' => $sliderProducts,
            'bannerProducts' => $bannerProducts,
        ]);
    }
}
