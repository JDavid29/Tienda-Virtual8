<?php

namespace App\Http\Livewire\Shop\Cart;

use Illuminate\Support\Facades\Log;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;


class IndexComponent extends Component
{
    // PARTE DEL CARRITO DE COMPRAS CON LIVEWIRE
    public $cartItems = [];
    public $couponCode = '';
    public $discount = 0;
    public $subtotal = 0;
    public $cartTotal = 0;


    public function mount()
    {
        $this->updateCart();
    }

    public function calculateTotals()
    {
        if (auth()->check()) {
            $this->subtotal = Cart::session(auth()->id())->getSubTotal();
        } else {
            $this->subtotal = Cart::getSubTotal();
        }

        // Aplicar cupón
        if ($this->couponCode === 'DESCUENTO10') {
            $this->discount = $this->subtotal * 0.10;
        } elseif ($this->couponCode === 'DESCUENTO20') {
            $this->discount = $this->subtotal * 0.20;
        } else {
            $this->discount = 0;
        }

        $this->cartTotal = $this->subtotal - $this->discount;
    }

    public function updateCart()
    {
        if (auth()->check()) {
            $this->cartItems = Cart::session(auth()->id())->getContent()->values()->map(
                function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'attributes' => $item->attributes,
                    ];
                }
            )->toArray();
            // print_r($this->cartItems);
        } else {
            $this->cartItems = Cart::getContent()->toArray();
        }
            $this->calculateTotals();
    }

    public function updateQuantity($itemId, $quantity = 1)
    {
        try {
            // sanitize and ensure minimum of 1
            $quantity = (int) $quantity;
            if ($quantity < 1) {
                $quantity = 1;
            }

            $updatePayload = [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
            ];

            if (auth()->check()) {
                Cart::session(auth()->id())->update($itemId, $updatePayload);
            } else {
                Cart::update($itemId, $updatePayload);
            }

            // recargar datos y actualizar totales sin redirigir
            $this->updateCart();

            // emitir evento para actualizar contadores en otros componentes si existen
            $this->emit('cartUpdated');

            // emit debug info so the frontend can show something if needed
            // $this->emit('cartUpdateSuccess', ['id' => $itemId, 'quantity' => $quantity]);
        } catch (\Throwable $e) {
            Log::error('Cart update failed: ' . $e->getMessage(), ['itemId' => $itemId, 'quantity' => $quantity]);
            $this->emit('cartUpdateError', ['message' => 'Error updating cart']);
        }
    }

    public function applyCoupon(){
        // calculateTotals ya lee $this->couponCode y aplica el descuento
        $this->calculateTotals();
    }

    public function deleteItem($itemId){
        // Eliminar producto sin redireccionar
        if(Auth::check()){
            Cart::session(Auth()->id())->remove($itemId);
        }else{
            Cart::remove($itemId);
        }
        // recargar datos y actualizar vista sin redirigir
        $this->updateCart();
        //emitir evento por si hay contador de carrito en el toolbar
        $this->emit("cartUpdated");
    }

    public function render()
    {
        // print_r($this->cartItems);
        return view('livewire.shop.cart.index-component')
        ->extends('layouts.toolbar')
        ->section('content');
    }
}
