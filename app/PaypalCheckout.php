<?php

namespace App;

use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Http\Request;

class PaypalCheckout
{
    public function getExpressCheckout($orderId){
        $cart = \Cart::session(auth()->id());
        $cartItems = array_map(function($item){
            return [
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['quantity'],
            ];
        }, $cart->getContent()->toarray());

        $checkoutData = [
            'items' => $cartItems,
            'invoice_id' => uniqid(),
            'invoice_description' => "descripcion de orden",
            'return_url' => route('paypal.success', $orderId),
            'cancel_url' => route('paypal.cancel'),
            'total' => $cart->getTotal(),
        ];

        $provider = new ExpressCheckout;

        $response = $provider->setExpressCheckout($checkoutData);
        return redirect($response['paypal_link']);
    }

    public function getExpressCheckoutSuccess(Request $request, $orderId){
        dd($request);
    }

    public function calcelPage(){
        dd("cancelado");
    }
}
