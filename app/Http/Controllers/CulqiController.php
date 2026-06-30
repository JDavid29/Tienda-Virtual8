<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Culqi\Culqi;

class CulqiController extends Controller
{
    private function culqi(): Culqi
    {
        return new Culqi(['api_key' => config('services.culqi.secret_key')]);
    }

    /**
     * Lee phone + OTP de la sesión (guardados por CheckoutComponent),
     * genera el token Yape en Culqi y procesa el cargo.
     */
    public function processYape(Request $request, $orderId)
    {
        $phone = session('culqi_yape_phone');
        $otp   = session('culqi_yape_otp');
        session()->forget(['culqi_yape_order_id', 'culqi_yape_phone', 'culqi_yape_otp']);

        if (!$phone || !$otp) {
            return redirect()->route('verificar')->with('error', 'Sesión de pago expirada. Intenta de nuevo.');
        }

        $order = Order::with('items', 'user')->findOrFail($orderId);

        // Culqi trabaja en centavos de PEN/USD. Usamos el total * 100.
        $amountCents = (int) round($order->total * 100);

        try {
            $culqi = $this->culqi();

            // 1. Crear token Yape
            $tokenData = $culqi->Tokens->createYape([
                'phone_number' => $phone,
                'otp'          => $otp,
                'amount'       => $amountCents,
            ]);

            if (empty($tokenData->id)) {
                Log::warning('Culqi Yape token failed', (array) $tokenData);
                return redirect()->route('verificar')->with('error', 'No se pudo generar el token Yape. Verifica el número y código OTP.');
            }

            // 2. Crear cargo usando el token
            $charge = $culqi->Charges->create([
                'amount'        => $amountCents,
                'currency_code' => 'PEN',
                'email'         => $order->user->email,
                'source_id'     => $tokenData->id,
                'description'   => 'Pedido #' . $order->order_number,
                'capture'       => true,
                'metadata'      => ['order_id' => $order->id],
            ]);

            if (isset($charge->object) && $charge->object === 'charge' && isset($charge->outcome->type) && $charge->outcome->type === 'venta_exitosa') {
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
                    ->with('success', '¡Gracias por tu compra con Yape! Tu pedido #' . $order->order_number . ' ha sido confirmado.');
            }

            $merchantMsg = $charge->outcome->merchant_message ?? 'Pago no aprobado.';
            Log::warning('Culqi Yape charge not approved', (array) $charge);
            return redirect()->route('verificar')->with('error', 'Pago rechazado: ' . $merchantMsg);

        } catch (\Throwable $e) {
            Log::error('Culqi Yape error: ' . $e->getMessage());
            return redirect()->route('verificar')->with('error', 'Error al procesar Yape: ' . $e->getMessage());
        }
    }
}
