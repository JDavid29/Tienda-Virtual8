<?php

namespace App\Http\Livewire\Shop;

use Livewire\Component;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartComponent extends Component
{
    public int $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'refreshCount'];

    public function mount()
    {
        $this->refreshCount();
    }

    public function refreshCount()
    {
        try {
            $this->cartCount = auth()->check()
                ? Cart::session(auth()->id())->getContent()->count()
                : Cart::getContent()->count();
        } catch (\Throwable $e) {
            $this->cartCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.shop.cart-component');
    }
}
