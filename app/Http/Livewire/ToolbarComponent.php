<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ToolbarComponent extends Component
{
    public $cartItems = [];
    public $cartSubTotal = 0;
    public $cartIsEmpty = true;
    public $cartTotalQuantity = 0;
    public $wishlistCount = 0;

    protected $listeners = ['productAdded' => 'updateCart', 'cartUpdated' => 'updateCart', 'wishlistUpdated' => 'updateWishlist'];

    public function mount()
    {
        $this->updateCart();
        $this->updateWishlist();
    }

    public function updateCart()
    {
        try {
            // Verificar si la clase Cart existe y está disponible
            if (class_exists('\\Cart')) {
                // usar la fachada Cart; manejar usuarios autenticados y guest
                if (auth()->check()) {
                    $this->cartItems = \Cart::session(auth()->id())->getContent()->toArray();
                    $this->cartSubTotal = \Cart::session(auth()->id())->getSubTotal();
                    $this->cartIsEmpty = \Cart::session(auth()->id())->isEmpty();
                    $this->cartTotalQuantity = \Cart::session(auth()->id())->getTotalQuantity();
                } else {
                    $this->cartItems = \Cart::getContent()->toArray();
                    $this->cartSubTotal = \Cart::getSubTotal();
                    $this->cartIsEmpty = \Cart::isEmpty();
                    $this->cartTotalQuantity = \Cart::getTotalQuantity();
                }
            } else {
                // Si Cart no está disponible, usar valores por defecto
                $this->setDefaultCartValues();
            }
        } catch (\Exception $e) {
            // En caso de error, usar valores por defecto
            $this->setDefaultCartValues();
        }
        // Siempre actualizar contador de lista de deseos al refrescar el carrito
        $this->updateWishlist();
    }

    public function updateWishlist()
    {
        try {
            if (auth()->check()) {
                $this->wishlistCount = \App\Models\ListaDeDeseo::where('user_id', auth()->id())->count();
            } else {
                $this->wishlistCount = 0;
            }
        } catch (\Exception $e) {
            $this->wishlistCount = 0;
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
            if (class_exists('\\Cart')) {
                if (auth()->check()) {
                    \Cart::session(auth()->id())->remove($productId);
                } else {
                    \Cart::remove($productId);
                }
                $this->updateCart();
                $this->emit('cartUpdated');
            }
        } catch (\Exception $e) {
            // Log error si es necesario
        }
    }

    public function render()
    {
        $categories = \DB::table('categories')->orderBy('order')->get();
        return view('livewire.toolbar-component', compact('categories'));
    }
}
