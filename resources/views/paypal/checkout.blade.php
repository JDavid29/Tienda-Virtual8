{{--
    Vista: paypal/checkout.blade.php
    ─────────────────────────────────────────────────────────────────────────
    Popup de pago PayPal con Smart Buttons.

    Variables recibidas desde PaypalController@getExpressCheckout:
      $order        → modelo Order con relación items cargada
      $clientId     → Client ID del SDK PayPal (sandbox o live)
      $mode         → 'sandbox' | 'live'
      $createUrl    → URL POST para crear la orden PayPal (devuelve { id })
      $cancelUrl    → URL de cancelación (volver al checkout)
      $parallelRate → tasa BOB/USD del dólar paralelo (ej: 13.50)
      $totalUsd     → total ya convertido a USD (string con 2 decimales)
    ─────────────────────────────────────────────────────────────────────────
--}}
@extends('layouts.app')

@section('content')
<style>
    /* ── Contenedor centrado en la página ── */
    .pp-page { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; }

    /* ── Tarjeta principal del popup ── */
    .pp-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); padding: 32px 28px; width: 100%; max-width: 460px; }

    /* ── Logo PayPal ── */
    .pp-logo { text-align: center; margin-bottom: 20px; }
    .pp-logo img { height: 36px; }

    /* ── Títulos ── */
    .pp-title    { font-size: 17px; font-weight: 700; color: #1a1a1a; text-align: center; margin-bottom: 4px; }
    .pp-subtitle { font-size: 12px; color: #888; text-align: center; margin-bottom: 20px; }

    /* ── Divisor "o paga con" ── */
    .pp-divider { display: flex; align-items: center; gap: 10px; margin: 16px 0; }
    .pp-divider::before, .pp-divider::after { content: ''; flex: 1; height: 1px; background: #e8e8e8; }
    .pp-divider span { font-size: 11px; color: #aaa; white-space: nowrap; }

    /* ── Resumen de la orden ── */
    .pp-summary { background: #f8f8f8; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; }
    .pp-summary-row { display: flex; justify-content: space-between; font-size: 13px; color: #555; margin-bottom: 6px; }
    .pp-summary-row:last-child { margin-bottom: 0; font-weight: 700; font-size: 15px; color: #1a1a1a; border-top: 1px solid #e8e8e8; padding-top: 8px; margin-top: 4px; }

    /* ── Tasa de conversión ── */
    .pp-rate-note { font-size: 11px; color: #888; text-align: center; margin-top: 6px; margin-bottom: 4px; }

    /* ── Botón cancelar ── */
    .pp-cancel { display: block; text-align: center; font-size: 12px; color: #aaa; margin-top: 16px; text-decoration: none; }
    .pp-cancel:hover { color: #e74c3c; }

    /* ── Contenedor de los botones PayPal ── */
    #paypal-button-container { min-height: 48px; }

    /* ── Spinner mientras procesa el pago ── */
    .pp-spinner { display: none; text-align: center; padding: 20px; color: #888; font-size: 13px; }

    /* ── Mensaje de error visible al usuario ── */
    .pp-error { display: none; background: #fff5f5; border: 1px solid #f5c6cb; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #721c24; margin-top: 12px; }

    /* ── Aviso informativo (ej: tarjeta no disponible en sandbox) ── */
    .pp-info { background: #e8f4fd; border: 1px solid #bee5eb; border-radius: 8px; padding: 10px 14px; font-size: 12px; color: #0c5460; margin-top: 10px; display: none; }

    /* ── Badge de modo sandbox (solo visible en desarrollo) ── */
    @if($mode === 'sandbox')
    .pp-sandbox-badge { background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; padding: 6px 12px; font-size: 11px; color: #856404; text-align: center; margin-bottom: 16px; }
    @endif
</style>

<div class="pp-page">
    <div class="pp-card">

        {{-- ── Logo PayPal ── --}}
        <div class="pp-logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="28" viewBox="0 0 100 28">
                <text y="22" font-family="Arial" font-size="22" font-weight="bold">
                    <tspan fill="#003087">Pay</tspan><tspan fill="#009cde">Pal</tspan>
                </text>
            </svg>
        </div>

        {{-- ── Badge de sandbox: solo visible en modo desarrollo ── --}}
        @if($mode === 'sandbox')
        <div class="pp-sandbox-badge">🧪 Modo Sandbox — entorno de pruebas</div>
        @endif

        <div class="pp-title">Completa tu pago</div>
        <div class="pp-subtitle">Pedido #{{ $order->order_number }}</div>

        {{--
            ── Resumen de la orden ──
            Los precios se muestran en BOB (moneda local) y el total
            también en USD (lo que PayPal realmente cobra).
        --}}
        <div class="pp-summary">
            @foreach($order->items as $item)
            <div class="pp-summary-row">
                <span>{{ Str::limit($item->nombre ?? $item->name, 30) }} × {{ $item->pivot->quantity }}</span>
                <span>Bs {{ number_format($item->pivot->price * $item->pivot->quantity, 2) }}</span>
            </div>
            @endforeach

            {{-- Fila de total en BOB --}}
            <div class="pp-summary-row">
                <span>Total (Bs)</span>
                <span>Bs {{ number_format($order->total, 2) }}</span>
            </div>

            {{-- Fila de total en USD usando la tasa paralela --}}
            <div class="pp-summary-row" style="color:#0070ba; border-top: none; padding-top: 0; margin-top: 2px; font-size: 13px; font-weight: 400;">
                <span>Equivalente USD <small style="color:#aaa">(Dólar paralelo)</small></span>
                <span style="font-weight:700">${{ $totalUsd }} USD</span>
            </div>
        </div>

        {{-- ── Nota explicativa de la tasa usada ── --}}
        <div class="pp-rate-note">
            Tasa aplicada: 1 USD = Bs {{ number_format($parallelRate, 2) }} (dólar paralelo)
        </div>

        {{--
            ── Contenedor de botones PayPal ──
            El SDK inyecta aquí el botón PayPal.
            El botón de tarjeta se inyecta en #pp-card-wrap si es elegible.
        --}}
        <div id="paypal-button-container"></div>

        {{-- Contenedor del botón de tarjeta (se muestra solo si el SDK lo soporta) --}}
        <div id="pp-card-wrap" style="display:none">
            <div class="pp-divider"><span>o paga con tarjeta</span></div>
            <div id="paypal-card-container"></div>
        </div>

        {{-- Mensaje informativo si el pago con tarjeta no está disponible --}}
        <div class="pp-info" id="pp-card-info">
            ℹ️ El pago con tarjeta directa no está disponible en este momento. Usa el botón PayPal arriba para pagar con tarjeta dentro del popup de PayPal.
        </div>

        {{-- Spinner mostrado mientras se procesa la captura --}}
        <div class="pp-spinner" id="pp-spinner">⏳ Procesando pago...</div>

        {{-- Mensaje de error para el usuario --}}
        <div class="pp-error" id="pp-error"></div>

        <a href="{{ $cancelUrl }}" class="pp-cancel">← Cancelar y volver</a>
    </div>
</div>

{{--
    ── PayPal JS SDK ──
    Parámetros de la URL:
      client-id       → credencial del merchant (sandbox o live)
      currency        → siempre USD (PayPal solo trabaja con USD en LatAm)
      intent          → capture: cobra inmediatamente al aprobar
      enable-funding  → habilita explícitamente los métodos: PayPal, tarjeta, crédito
      disable-funding → desactiva paylater y venmo (no disponibles en Bolivia)
      components      → carga solo el componente "buttons" para reducir peso del SDK
--}}
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture&enable-funding=paypal,card,credit&disable-funding=paylater,venmo&components=buttons" data-namespace="paypal_sdk"></script>

<script>
(function () {
    /*
     * Variables pasadas desde el controlador PHP al contexto JS.
     * La directiva @@json() de Blade escapa correctamente los valores para evitar XSS.
     */
    var CREATE_URL  = @json($createUrl);   // endpoint POST que crea la orden en PayPal y devuelve { id }
    var CSRF_TOKEN  = document.querySelector('meta[name="csrf-token"]').content;
    var ORDER_ID    = @json($order->id);   // ID interno de la orden en nuestra DB
    var ORDER_NUM   = @json($order->order_number);
    var TOTAL_USD   = @json($totalUsd);    // monto en USD ya convertido (para log/debug)

    /* ── Muestra un mensaje de error visible al usuario ── */
    function showError(msg) {
        var el = document.getElementById('pp-error');
        el.textContent = '⚠️ ' + msg;
        el.style.display = 'block';
        document.getElementById('pp-spinner').style.display = 'none';
    }

    /*
     * ── createOrder ──
     * Llamado por el SDK antes de abrir el popup de PayPal.
     * Hace POST a nuestro servidor, que crea la orden en la API de PayPal
     * y devuelve el paypalOrderId. El SDK necesita ese ID para continuar.
     */
    function createOrder() {
        return fetch(CREATE_URL, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  CSRF_TOKEN
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.error) throw new Error(data.error);
            return data.id; // ID de la orden PayPal (formato "EC-XXXXXX")
        });
    }

    /*
     * ── onApprove ──
     * Llamado por el SDK cuando el usuario aprueba el pago en PayPal.
     * Hace POST a nuestro servidor para capturar el dinero y marcar la orden.
     * data.orderID es el paypalOrderId devuelto por PayPal tras la aprobación.
     */
    function onApprove(data) {
        document.getElementById('pp-spinner').style.display = 'block';

        // Endpoint de captura: /paypal/capture/{paypalOrderId}/{orderId}
        var captureUrl = '/paypal/capture/' + data.orderID + '/' + ORDER_ID;

        return fetch(captureUrl, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  CSRF_TOKEN
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (result) {
            if (result.status === 'COMPLETED') {
                // Guarda el mensaje de éxito en sessionStorage para mostrarlo en home
                sessionStorage.setItem('paypal_success', result.message);
                window.location.href = result.redirect;
            } else {
                showError(result.error || 'El pago no fue completado.');
            }
        })
        .catch(function (err) { showError(err.message); });
    }

    /* ── onError ── Llamado si el SDK falla internamente ── */
    function onError(err) {
        showError('Ocurrió un error con PayPal. Intenta de nuevo.');
        console.error('[PayPal SDK error]', err);
    }

    /* ── onCancel ── El usuario cerró el popup sin pagar ── */
    function onCancel() {
        document.getElementById('pp-error').style.display = 'none';
    }

    /*
     * ── Botón PayPal (cuenta PayPal o tarjeta dentro del popup de PayPal) ──
     * Este botón siempre está disponible y es el método principal.
     * El usuario puede pagar con su cuenta PayPal o con tarjeta dentro del popup.
     */
    paypal_sdk.Buttons({
        fundingSource: paypal_sdk.FUNDING.PAYPAL,
        style: {
            color:  'gold',   // amarillo característico de PayPal
            label:  'pay',    // texto "Pagar con PayPal"
            height: 48
        },
        createOrder: createOrder,
        onApprove:   onApprove,
        onError:     onError,
        onCancel:    onCancel,
    }).render('#paypal-button-container');

    /*
     * ── Botón de tarjeta (Advanced Credit and Debit Card / ACDC) ──
     * Este botón permite pagar con tarjeta directamente SIN abrir un popup.
     * Requiere que el merchant tenga "Advanced Card Payments" habilitado en PayPal.
     *
     * IMPORTANTE: En sandbox esto puede no estar disponible dependiendo del
     * plan de la cuenta de desarrollador. En producción se habilita en
     * developer.paypal.com → Apps → tu app → "Advanced Credit and Debit Card Payments".
     *
     * Usamos isEligible() para verificar disponibilidad ANTES de renderizar.
     * Si no está disponible, mostramos un mensaje informativo en lugar del error de PayPal.
     */
    var cardButton = paypal_sdk.Buttons({
        fundingSource: paypal_sdk.FUNDING.CARD,
        style: {
            color:  'black',  // botón oscuro para diferenciar del botón PayPal
            label:  'pay',
            height: 48
        },
        createOrder: createOrder,
        onApprove:   onApprove,
        onError:     onError,
        onCancel:    onCancel,
    });

    if (cardButton.isEligible()) {
        /*
         * El merchant tiene ACDC habilitado → mostramos el botón de tarjeta directa.
         * Se muestra el wrapper #pp-card-wrap que contiene el divisor y el contenedor.
         */
        document.getElementById('pp-card-wrap').style.display = 'block';
        cardButton.render('#paypal-card-container');
    } else {
        /*
         * El merchant NO tiene ACDC habilitado (común en sandbox sin configuración extra).
         * Mostramos el mensaje informativo en lugar del botón para evitar
         * el error genérico "Se ha producido un error. Lo enviaremos de regreso al pago".
         */
        document.getElementById('pp-card-info').style.display = 'block';
    }

})();
</script>

{{--
    ── Mostrar mensaje de éxito post-pago ──
    Después de un pago exitoso, el controlador redirige a /home.
    El mensaje se guardó en sessionStorage antes de la redirección
    para sobrevivir el cambio de página.
--}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var msg = sessionStorage.getItem('paypal_success');
    if (msg) {
        sessionStorage.removeItem('paypal_success');
        if (typeof showToast === 'function') showToast('success', msg, 6000);
    }
});
</script>
@endsection
