<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ToolbarComponent extends Component
{
    public $cartItems = [];
    public $cartSubTotal = 0;
    public $cartIsEmpty = true;
    public $cartTotalQuantity = 0;

    protected $listeners = ['productAdded' => 'updateCart', 'cartUpdated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        try {
            // Verificar si la clase Cart existe y está disponible
            if (class_exists('\\Cart') && app()->has('cart')) {
                $cart = app('cart');

                $this->cartItems = $cart->session(auth()->id())->getContent()->toArray();
                $this->cartSubTotal = $cart->session(auth()->id())->getSubTotal();
                $this->cartIsEmpty = $cart->session(auth()->id())->isEmpty();
                $this->cartTotalQuantity = $cart->session(auth()->id())->getTotalQuantity();
            } else {
                // Si Cart no está disponible, usar valores por defecto
                $this->setDefaultCartValues();
            }
        } catch (\Exception $e) {
            // En caso de error, usar valores por defecto
            $this->setDefaultCartValues();
        }
    }

    private function setDefaultCartValues()
    {
        $this->cartItems = [];
        $this->cartSubTotal = 0;
        $this->cartIsEmpty = true;
        $this->cartTotalQuantity = 0;
    }

    public function removeFromCart($productId)
    {
        try {
            if (class_exists('\\Cart') && app()->has('cart')) {
                $cart = app('cart');
                $cart->remove($productId);
                $this->updateCart();
                $this->emit('cartUpdated');
            }
        } catch (\Exception $e) {
            // Log error si es necesario
        }
    }

    public function render()
    {
        return view('livewire.toolbar-component');
    }
}
