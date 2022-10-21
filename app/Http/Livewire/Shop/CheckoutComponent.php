<?php

namespace App\Http\Livewire\Shop;

use App\Models\Order;
//use App\PaypalCheckout;
use Livewire\Component;

use Srmklive\PayPal\Services\ExpressCheckout;

class CheckoutComponent extends Component
{
    public $fullname, $address, $city, $state, $zipcode, $phone;

    public $billing_fullname, $billing_address, $billing_city, $billing_state, $billing_zipcode,
    $billing_phone;

    public $payment_method, $total;

    //private $paypalCheckout;
    // public function mount(PaypalCheckout $paypalCheckout){
    //     $this->paypalCheckout = $paypalCheckout;
    // }

    public function render()
    {
        return view('livewire.shop.checkout-component')
        ->extends('layouts.app')
        ->section('content');
    }

    public function make_order(){
        $this->validate([
            'fullname' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'phone' => 'required',
            'payment_method' => 'required',
        ]);

        $order= new Order();
        $order->user_id = auth()->id();
        $order->order_number = uniqid('OrderNumber-');
        $order->item_count = \Cart::session(auth()->id())->getContent()->count();

        $order->shipping_fullname = $this->fullname;
        $order->shipping_address = $this->address;
        $order->shipping_city = $this->city;
        $order->shipping_state = $this->state;
        $order->shipping_zipcode = $this->zipcode;
        $order->shipping_phone = $this->phone;

        if (is_null($this->billing_fullname)) {
            $order->billing_fullname = $this->fullname;
            $order->billing_address = $this->address;
            $order->billing_city = $this->city;
            $order->billing_state = $this->state;
            $order->billing_zipcode = $this->zipcode;
            $order->billing_phone = $this->phone;
        } else {
            $order->billing_fullname = $this->billing_fullname;
            $order->billing_address = $this->billing_address;
            $order->billing_city = $this->billing_city;
            $order->billing_state = $this->billing_state;
            $order->billing_zipcode = $this->billing_zipcode;
            $order->billing_phone = $this->billing_phone;
        }

        $order->payment_method = $this->payment_method;

        $order->total = \Cart::session(auth()->id())->getTotal();

        $order->save();

        $cartItems = \Cart::session(auth()->id())->getContent();
        foreach ($cartItems as $key => $item) {
            $order->items()->attach($item->id, [
                'price' => $item->price,
                'quantity' => $item->quantity,
            ]);
        }

        if ($this->payment_method == 'paypal') {
            $this->getExpressCheckout($order->id);
        } else {
            //false
            //$order->is_paid = false
        }

    }

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
