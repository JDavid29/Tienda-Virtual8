<?php

namespace App\Http\Livewire\Shop;

use App\Models\Producto;
use Livewire\Component;

class IndexComponent extends Component
{
    public function render()
    {
        $productos = Producto::all();
        return view('livewire.shop.index-component', compact('productos'))
        ->extends('layouts.app')
        ->section('content');
    }
}
