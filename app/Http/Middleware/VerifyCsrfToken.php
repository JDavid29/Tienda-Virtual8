<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    // ⚠️  SEGURIDAD: Estas rutas están exentas de CSRF porque reciben POST de servicios externos.
    // OBLIGATORIO antes de producción:
    //   - binance/webhook → validar header "BinancePay-Signature" con HMAC-SHA512 usando BINANCE_API_SECRET
    //   - stripe/webhook  → usar Webhook::constructEvent($payload, $sig, config('services.stripe.webhook_secret'))
    // Sin validación de firma, cualquiera puede forjar un webhook y marcar órdenes como pagadas.
    protected $except = [
        'binance/webhook',
        'stripe/webhook',
    ];
}
