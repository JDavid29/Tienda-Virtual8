<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use TCG\Voyager\Models\Category;

class Categories extends Component
{
    public function render()
    {
        $categories = Category::orderBy('id','DESC')->get();
        return view('livewire.shop.categories', compact('categories'));
    }
}
