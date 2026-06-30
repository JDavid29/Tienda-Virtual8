<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function checkout($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        try {
            // ⚠️  ERROR DE MONEDA: pivot->price está en BOB, no en USD.
            // Se está cobrando el valor numérico en BOB como si fueran centavos de USD.
            // Ejemplo: Bs 500 → se cobra $5.00 USD en vez de ~$36.00 USD.
            // FIX: convertir BOB→USD antes de multiplicar por 100.
            // Usar la misma lógica de PaypalController::bobToUsd() o un helper compartido.
            $lineItems = $order->items->map(fn($p) => [
                'price_data' => [
                    'currency'     => 'usd',
                    'unit_amount'  => (int) round($p->pivot->price * 100),
                    'product_data' => ['name' => $p->nombre ?? $p->name],
                ],
                'quantity' => $p->pivot->quantity,
            ])->values()->toArray();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'success_url'          => route('stripe.success', $orderId) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => route('stripe.cancel'),
                'metadata'             => ['order_id' => $orderId],
            ]);

            $order->stripe_session_id = $session->id;
            $order->save();

            return redirect()->away($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Error al conectar con Stripe: ' . $e->getMessage());
        }
    }

    public function success(Request $request, $orderId)
    {
        $order     = Order::findOrFail($orderId);
        $sessionId = $request->query('session_id');

        try {
            $session = Session::retrieve($sessionId);

            // ⚠️  VULNERABILIDAD IDOR: no se verifica que la session_id corresponda a esta orden.
            // Un atacante puede pagar $1, obtener su session_id y luego acceder a
            // /stripe/success/{id_de_orden_ajena}?session_id=su_session_valida.
            // Stripe confirmaría "paid" (porque sí pagó algo) y se marcaría la orden ajena como pagada.
            // FIX: verificar que $session->metadata['order_id'] === (string) $orderId
            // antes de marcar la orden como pagada.
            if ($session->payment_status === 'paid') {
                $order->is_paid           = 1;
                $order->status            = 'paid';
                $order->stripe_session_id = $session->id;
                $order->save();

                try {
                    Mail::to($order->user->email)->send(new OrderPaid($order));
                } catch (\Throwable $e) {
                    Log::warning('OrderPaid mail failed: ' . $e->getMessage());
                }

                if (auth()->check()) {
                    \Cart::session(auth()->id())->clear();
                } else {
                    \Cart::clear();
                }

                return redirect()->route('home')
                    ->with('success', '¡Gracias por tu compra! Tu pedido #' . $order->order_number . ' ha sido confirmado.');
            }
        } catch (\Throwable $e) {
            Log::error('Stripe success error: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('error', 'El pago no pudo verificarse. Contacta soporte.');
    }

    public function cancel()
    {
        return redirect()->route('checkout')->with('error', 'El pago con Stripe fue cancelado.');
    }
}
