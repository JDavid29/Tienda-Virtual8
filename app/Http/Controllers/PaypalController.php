<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
{
    public function getExpressCheckout($orderId){
        $checkoutData = $this->checkoutData($orderId);
        $provider = new ExpressCheckout;

        $response = $provider->setExpressCheckout($checkoutData);
        return redirect($response['paypal_link']);
    }

    public function getExpressCheckoutSuccess(Request $request, $orderId){

        //dd($request);
        $provider = new ExpressCheckout;
        $token = $request->token;
        $PayerID = $request->PayerID;
        $checkoutData = $this->checkoutData($orderId);

        $response = $provider->getExpressCheckoutDetails($token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            // Note that 'token', 'PayerID' are values returned by PayPal when it redirects to success page after successful verification of user's PayPal info.
            $payment_status = $provider->doExpressCheckoutPayment($checkoutData, $token, $PayerID);

            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
            if (in_array($status, ['Completed', 'Processed'])) {
                $order = Order::find($orderId);
                $order->is_paid = 1;
                $order->save();
                Mail::to($order->user->email)->send(new OrderPaid($order));

                \Cart::session(auth()->id())->clear(); //limpiamos el product del carito despues de la compra
                return redirect()->route('shop.index')->withMessage('Pago exitoso');
            }
        }

        return redirect()->route('shop.index')->withError('Pago fallido');

    }

    public function calcelPage(){
        dd("cancelado");
    }

    public function checkoutData($orderId){
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
        return $checkoutData;
    }
}
