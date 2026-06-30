<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * MODO SIMULACIÓN — no realiza llamadas reales a Binance Pay.
 * Genera direcciones mock por moneda, tasa en vivo via CoinGecko (gratis),
 * selector de moneda (USDT, BNB, BTC, ETH) y botones aprobar/rechazar.
 */
class BinancePayController extends Controller
{
    // Direcciones mock por moneda (solo para demostración visual)
    private array $mockAddresses = [
        'USDT' => 'TQnMockUSDT7xK9PzW3rBhNvYqLm4cFake1',
        'BNB'  => '0xMockBNB3aE9fD12c7Bb84FakeAddress99',
        'BTC'  => 'bc1qMockBTC8xK2pZwFakeAddressDemo9',
        'ETH'  => '0xMockETH5cD8aB34FakeAddressEth77',
    ];

    // Redes por moneda
    private array $networks = [
        'USDT' => 'TRC-20',
        'BNB'  => 'BEP-20',
        'BTC'  => 'Bitcoin',
        'ETH'  => 'ERC-20',
    ];

    // IDs de CoinGecko para cada moneda
    private array $coinGeckoIds = [
        'USDT' => 'tether',
        'BNB'  => 'binancecoin',
        'BTC'  => 'bitcoin',
        'ETH'  => 'ethereum',
    ];

    public function checkout(Order $order, Request $request)
    {
        abort_unless(auth()->id() === $order->user_id, 403);

        $coin = strtoupper($request->get('coin', 'USDT'));
        if (! array_key_exists($coin, $this->mockAddresses)) {
            $coin = 'USDT';
        }

        if (! $order->binance_prepay_id) {
            $mockPrepayId = 'MOCK-' . strtoupper(substr(md5($order->id . time()), 0, 16));
            $order->update([
                'binance_order_id'  => $order->order_number,
                'binance_prepay_id' => $mockPrepayId,
            ]);
        }

        $order = $order->fresh();

        // Generar QR con la dirección mock de la moneda seleccionada
        $address  = $this->mockAddresses[$coin];
        $network  = $this->networks[$coin];
        $qrData   = urlencode($address . '?amount=MOCK&order=' . $order->order_number);
        $qrUrl    = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&color=1a1a1a&bgcolor=ffffff&data=' . $qrData;

        return view('binance.checkout', [
            'order'        => $order,
            'coin'         => $coin,
            'address'      => $address,
            'network'      => $network,
            'qrCodeLink'   => $qrUrl,
            'expireTime'   => now()->addMinutes(15)->timestamp * 1000,
            'isMock'       => true,
            'allCoins'     => array_keys($this->mockAddresses),
            'coinGeckoIds' => $this->coinGeckoIds,
        ]);
    }

    /**
     * Polling de estado — mock: PAID si ya se aprobó, PENDING si no.
     */
    public function queryStatus(Order $order)
    {
        abort_unless(auth()->id() === $order->user_id, 403);

        return response()->json([
            'status' => $order->fresh()->is_paid ? 'PAID' : 'PENDING',
        ]);
    }

    /**
     * Simular pago aprobado.
     */
    public function mockApprove(Order $order)
    {
        abort_unless(auth()->id() === $order->user_id, 403);

        if (! $order->is_paid) {
            $order->update(['is_paid' => true, 'status' => 'processing']);

            try {
                \Darryldecode\Cart\Facades\CartFacade::session($order->user_id)->clear();
            } catch (\Throwable $e) {
                Log::warning('BinanceMock: no se pudo limpiar carrito: ' . $e->getMessage());
            }
        }

        return redirect()->route('home')
            ->with('success', '¡Pago simulado con Binance Pay aprobado! Pedido #' . $order->order_number);
    }

    /**
     * Simular pago rechazado.
     */
    public function mockReject(Order $order)
    {
        abort_unless(auth()->id() === $order->user_id, 403);

        return redirect()->route('checkout')
            ->with('error', 'Pago simulado con Binance Pay rechazado. Intenta de nuevo.');
    }

    /**
     * Webhook stub.
     */
    public function webhook(Request $request)
    {
        Log::info('BinancePay mock webhook recibido (ignorado)');
        return response()->json(['returnCode' => 'SUCCESS', 'returnMessage' => null]);
    }
}
