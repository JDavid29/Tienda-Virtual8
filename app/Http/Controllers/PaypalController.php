<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

class PaypalController extends Controller
{
    private function baseUrl(): string
    {
        return config('paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function clientId(): string
    {
        return config('paypal.mode') === 'live'
            ? config('paypal.live.client_id')
            : config('paypal.sandbox.client_id');
    }

    private function getAccessToken(): string
    {
        $clientSecret = config('paypal.mode') === 'live'
            ? config('paypal.live.client_secret')
            : config('paypal.sandbox.client_secret');

        // ⚠️  VULNERABILIDAD CRÍTICA: 'verify'=>false deshabilita la verificación del certificado TLS.
        // En producción un atacante MitM puede interceptar las credenciales client_id/client_secret
        // y falsificar respuestas de PayPal (p.ej. status:"COMPLETED" sin cobro real).
        // FIX: eliminar 'verify'=>false. Guzzle verifica TLS por defecto.
        // Si hay problema de CA local en dev, usar: 'verify' => '/ruta/al/cacert.pem'
        $client = new Client(['verify' => false]);

        $response = $client->post($this->baseUrl() . '/v1/oauth2/token', [
            'auth'        => [$this->clientId(), $clientSecret],
            'form_params' => ['grant_type' => 'client_credentials'],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

    /**
     * Devuelve la tasa del dólar paralelo configurada en .env.
     * Se usa para convertir el total en BOB a USD antes de enviarlo a PayPal.
     */
    private function parallelRate(): float
    {
        return (float) env('DOLAR_PARALELO', 13.50);
    }

    /**
     * Convierte un monto en BOB a USD usando la tasa paralela configurada.
     */
    private function bobToUsd(float $bob): string
    {
        return number_format($bob / $this->parallelRate(), 2, '.', '');
    }

    /**
     * Muestra la página con los Smart Buttons (PayPal + Tarjeta).
     * Pasa la tasa y el total en USD al view para mostrarlo en el resumen.
     */
    public function getExpressCheckout($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        return view('paypal.checkout', [
            'order'        => $order,
            'clientId'     => $this->clientId(),
            'mode'         => config('paypal.mode'),
            'createUrl'    => route('paypal.create.order', $orderId),
            'cancelUrl'    => route('paypal.cancel'),
            // Tasa y total convertido para mostrar en el popup
            'parallelRate' => $this->parallelRate(),
            'totalUsd'     => $this->bobToUsd((float) $order->total),
        ]);
    }

    /**
     * Llamado por el JS SDK: crea la orden en PayPal y devuelve el ID.
     * El monto se convierte de BOB a USD usando la tasa paralela antes de enviar a PayPal.
     */
    public function createPaypalOrder(Request $request, $orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        try {
            $token  = $this->getAccessToken();
            $client = new Client(['verify' => false]);

            // Cada ítem se convierte de BOB a USD individualmente
            $items = $order->items->map(fn($p) => [
                'name'        => $p->nombre ?? $p->name,
                'unit_amount' => ['currency_code' => 'USD', 'value' => $this->bobToUsd((float)$p->pivot->price)],
                'quantity'    => (string)$p->pivot->quantity,
            ])->values()->toArray();

            // Total de la orden convertido a USD
            $total = $this->bobToUsd((float)$order->total);

            $body = [
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $order->order_number,
                    'description'  => 'Pedido #' . $order->order_number,
                    'items'        => $items,
                    'amount'       => [
                        'currency_code' => 'USD',
                        'value'         => $total,
                        'breakdown'     => [
                            'item_total' => ['currency_code' => 'USD', 'value' => $total],
                        ],
                    ],
                ]],
                'application_context' => [
                    'brand_name'          => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action'         => 'PAY_NOW',
                ],
            ];

            $response = $client->post($this->baseUrl() . '/v2/checkout/orders', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $body,
            ]);

            $paypalOrder = json_decode($response->getBody(), true);

            $order->paypal_order_id = $paypalOrder['id'];
            $order->save();

            return response()->json(['id' => $paypalOrder['id']]);

        } catch (\Throwable $e) {
            Log::error('PayPal createPaypalOrder error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Llamado por el JS SDK tras la aprobación: captura el pago.
     */
    public function capturePaypalOrder(Request $request, $paypalOrderId, $orderId)
    {
        $order = Order::findOrFail($orderId);

        try {
            $token  = $this->getAccessToken();
            // ⚠️  MISMA VULNERABILIDAD TLS — ver comentario en getAccessToken()
            $client = new Client(['verify' => false]);

            $response = $client->post(
                $this->baseUrl() . '/v2/checkout/orders/' . $paypalOrderId . '/capture',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type'  => 'application/json',
                    ],
                ]
            );

            $capture = json_decode($response->getBody(), true);
            $status  = $capture['status'] ?? '';

            if ($status === 'COMPLETED') {
                $order->is_paid = 1;
                $order->status  = 'paid';
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

                return response()->json([
                    'status'   => 'COMPLETED',
                    'redirect' => route('home'),
                    'message'  => '¡Gracias por tu compra! Tu pedido #' . $order->order_number . ' ha sido confirmado.',
                ]);
            }

            return response()->json(['status' => $status, 'error' => 'Pago no completado'], 422);

        } catch (\Throwable $e) {
            Log::error('PayPal capturePaypalOrder error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Éxito por redirect clásico (fallback, por si el JS falla).
     */
    public function getExpressCheckoutSuccess(Request $request, $orderId)
    {
        $order         = Order::findOrFail($orderId);
        $paypalOrderId = $request->token ?? $order->paypal_order_id;

        try {
            $token  = $this->getAccessToken();
            // ⚠️  MISMA VULNERABILIDAD TLS — ver comentario en getAccessToken()
            $client = new Client(['verify' => false]);

            $response = $client->post(
                $this->baseUrl() . '/v2/checkout/orders/' . $paypalOrderId . '/capture',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type'  => 'application/json',
                    ],
                ]
            );

            $capture = json_decode($response->getBody(), true);

            if (($capture['status'] ?? '') === 'COMPLETED') {
                $order->is_paid = 1;
                $order->status  = 'paid';
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
            Log::error('PayPal success fallback error: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('error', 'El pago no pudo completarse. Intenta de nuevo.');
    }

    public function calcelPage()
    {
        return redirect()->route('verificar')->with('error', 'El pago con PayPal fue cancelado.');
    }
}
