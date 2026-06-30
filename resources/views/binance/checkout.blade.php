@extends('layouts.app')

@section('content')
<style>
/* ── Base ───────────────────────────────────────────── */
.bp-page  { min-height:90vh; display:flex; align-items:center; justify-content:center; padding:24px 16px; background:linear-gradient(135deg,#fafafa 0%,#fff8e7 100%); }
.bp-card  { background:#fff; border-radius:20px; box-shadow:0 8px 40px rgba(240,185,11,.13),0 2px 12px rgba(0,0,0,.07); padding:32px 28px; width:100%; max-width:500px; }

/* ── Header ─────────────────────────────────────────── */
.bp-header { display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:6px; }
.bp-logo-circle { width:48px; height:48px; background:#f0b90b; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(240,185,11,.4); }
.bp-logo-circle svg { width:28px; height:28px; }
.bp-brand-name { font-size:20px; font-weight:800; color:#1a1a1a; line-height:1.1; }
.bp-brand-sub  { font-size:11px; color:#aaa; }

/* ── Badges ─────────────────────────────────────────── */
.bp-mock-badge {
    display:flex; align-items:center; justify-content:center; gap:6px;
    background:#e8f4fd; border:1.5px solid #3498db; color:#1a6fa8;
    font-size:11px; font-weight:700; padding:7px 12px; border-radius:8px;
    margin:16px 0 12px; letter-spacing:.3px;
}
.bp-mock-badge .dot { width:7px; height:7px; background:#3498db; border-radius:50%; animation:bp-blink 1.2s infinite; }
@keyframes bp-blink { 0%,100%{opacity:1} 50%{opacity:.2} }

/* ── Coin selector ──────────────────────────────────── */
.bp-coin-label { font-size:11px; color:#888; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px; text-align:center; }
.bp-coins { display:flex; gap:8px; justify-content:center; margin-bottom:18px; }
.bp-coin-btn {
    display:flex; flex-direction:column; align-items:center; gap:3px;
    padding:8px 14px; border-radius:10px; border:2px solid #e8e8e8;
    cursor:pointer; background:#fafafa; transition:all .18s; text-decoration:none;
    min-width:68px;
}
.bp-coin-btn:hover { border-color:#f0b90b; background:#fffbe6; text-decoration:none; }
.bp-coin-btn.active { border-color:#f0b90b; background:#fffbe6; box-shadow:0 0 0 3px rgba(240,185,11,.18); }
.bp-coin-icon { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:800; }
.bp-coin-icon.usdt { background:#26a17b; color:#fff; font-size:10px; }
.bp-coin-icon.bnb  { background:#f0b90b; color:#1a1a1a; font-size:10px; }
.bp-coin-icon.btc  { background:#f7931a; color:#fff; font-size:10px; }
.bp-coin-icon.eth  { background:#627eea; color:#fff; font-size:10px; }
.bp-coin-name  { font-size:12px; font-weight:700; color:#1a1a1a; }
.bp-coin-net   { font-size:9px; color:#aaa; }

/* ── Amount box ─────────────────────────────────────── */
.bp-amount-box {
    background:linear-gradient(135deg,#fffbe6,#fff8d0);
    border:2px solid #f0b90b; border-radius:12px;
    padding:16px 20px; text-align:center; margin-bottom:16px;
    position:relative; overflow:hidden;
}
.bp-amount-box::before { content:''; position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:rgba(240,185,11,.08); border-radius:50%; }
.bp-amount-label { font-size:10px; color:#b7860b; text-transform:uppercase; letter-spacing:.6px; margin-bottom:2px; }
.bp-amount-value { font-size:32px; font-weight:900; color:#1a1a1a; line-height:1.1; }
.bp-amount-value span { font-size:16px; font-weight:600; color:#b7860b; }
.bp-amount-rate  { font-size:11px; color:#aaa; margin-top:4px; }
.bp-amount-rate .live { color:#27ae60; font-weight:700; }
.bp-amount-bs    { font-size:12px; color:#888; margin-top:2px; }

/* ── Status bar ─────────────────────────────────────── */
.bp-status-bar { display:flex; align-items:center; justify-content:center; gap:8px; border-radius:9px; padding:10px 14px; font-size:13px; margin-bottom:14px; transition:all .4s; }
.bp-status-bar.waiting  { background:#fffbe6; color:#b7860b; border:1.5px solid #f0b90b; }
.bp-status-bar.paid     { background:#eafaf1; color:#1a8c3e; border:1.5px solid #27ae60; }
.bp-status-bar.expired  { background:#fdf2f2; color:#c0392b; border:1.5px solid #e74c3c; }
.bp-spinner { display:inline-block; width:15px; height:15px; border:2.5px solid rgba(240,185,11,.3); border-top-color:#b7860b; border-radius:50%; animation:bp-spin .75s linear infinite; flex-shrink:0; }
@keyframes bp-spin { to { transform:rotate(360deg); } }

/* ── Countdown ──────────────────────────────────────── */
.bp-countdown-wrap { text-align:center; margin-bottom:14px; }
.bp-countdown-wrap .label { font-size:10px; color:#aaa; text-transform:uppercase; letter-spacing:.4px; }
.bp-countdown-wrap .timer { font-size:22px; font-weight:800; color:#b7860b; letter-spacing:2px; font-variant-numeric:tabular-nums; }
.bp-countdown-wrap .timer.urgent { color:#e74c3c; animation:bp-pulse .6s infinite; }
@keyframes bp-pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

/* ── QR ─────────────────────────────────────────────── */
.bp-qr-wrap  { display:flex; flex-direction:column; align-items:center; gap:10px; margin-bottom:16px; }
.bp-qr-outer { position:relative; }
.bp-qr-frame { background:#fff; border:3px solid #f0b90b; border-radius:16px; padding:12px; display:inline-flex; box-shadow:0 4px 16px rgba(240,185,11,.2); }
.bp-qr-frame img { width:200px; height:200px; display:block; border-radius:6px; }
.bp-qr-coin-badge {
    position:absolute; bottom:-10px; right:-10px;
    width:32px; height:32px; border-radius:50%; border:3px solid #fff;
    display:flex; align-items:center; justify-content:center;
    font-size:10px; font-weight:800; box-shadow:0 2px 8px rgba(0,0,0,.15);
}
.bp-qr-coin-badge.usdt { background:#26a17b; color:#fff; }
.bp-qr-coin-badge.bnb  { background:#f0b90b; color:#1a1a1a; }
.bp-qr-coin-badge.btc  { background:#f7931a; color:#fff; }
.bp-qr-coin-badge.eth  { background:#627eea; color:#fff; }

.bp-qr-hint { font-size:11px; color:#999; text-align:center; line-height:1.6; }
.bp-qr-hint strong { color:#1a1a1a; }

/* ── Address copy ───────────────────────────────────── */
.bp-address-label { font-size:10px; color:#aaa; font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px; }
.bp-address-box {
    display:flex; align-items:center; gap:8px;
    background:#f7f7f7; border:1.5px solid #e8e8e8; border-radius:9px;
    padding:10px 12px; margin-bottom:16px; cursor:pointer;
    transition:border-color .15s;
}
.bp-address-box:hover { border-color:#f0b90b; }
.bp-address-text { flex:1; font-size:11px; color:#555; font-family:monospace; word-break:break-all; line-height:1.4; }
.bp-copy-btn {
    flex-shrink:0; background:#f0b90b; border:none; border-radius:6px;
    padding:6px 10px; font-size:11px; font-weight:700; color:#1a1a1a;
    cursor:pointer; transition:background .15s; white-space:nowrap;
}
.bp-copy-btn:hover { background:#d4a309; }
.bp-copy-btn.copied { background:#27ae60; color:#fff; }

/* ── Steps ──────────────────────────────────────────── */
.bp-steps { display:flex; gap:0; margin-bottom:18px; }
.bp-step  { flex:1; text-align:center; padding:8px 4px; position:relative; }
.bp-step:not(:last-child)::after {
    content:''; position:absolute; top:14px; right:0; width:50%; height:2px;
    background:#e8e8e8; z-index:0;
}
.bp-step:not(:first-child)::before {
    content:''; position:absolute; top:14px; left:0; width:50%; height:2px;
    background:#e8e8e8; z-index:0;
}
.bp-step-num {
    width:28px; height:28px; border-radius:50%; background:#f0f0f0; color:#aaa;
    font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center;
    margin:0 auto 4px; position:relative; z-index:1;
}
.bp-step.done .bp-step-num  { background:#27ae60; color:#fff; }
.bp-step.active .bp-step-num { background:#f0b90b; color:#1a1a1a; }
.bp-step-txt { font-size:10px; color:#aaa; line-height:1.3; }
.bp-step.done .bp-step-txt   { color:#27ae60; }
.bp-step.active .bp-step-txt { color:#b7860b; font-weight:600; }

/* ── Mock action buttons ────────────────────────────── */
.bp-mock-actions { display:flex; gap:10px; margin-bottom:4px; }
.bp-mock-actions form { flex:1; }
.bp-btn-approve {
    width:100%; padding:13px 8px; background:linear-gradient(135deg,#27ae60,#1e8449);
    color:#fff; font-weight:700; font-size:13px; border:none; border-radius:10px;
    cursor:pointer; transition:all .18s; letter-spacing:.3px;
}
.bp-btn-approve:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(39,174,96,.35); }
.bp-btn-reject  {
    width:100%; padding:13px 8px; background:#f7f7f7;
    color:#c0392b; font-weight:700; font-size:13px; border:2px solid #e74c3c;
    border-radius:10px; cursor:pointer; transition:all .18s;
}
.bp-btn-reject:hover { background:#fdf2f2; }

/* ── Footer ─────────────────────────────────────────── */
.bp-cancel { display:block; text-align:center; font-size:12px; color:#ccc; margin-top:14px; cursor:pointer; text-decoration:none; transition:color .15s; }
.bp-cancel:hover { color:#e74c3c; text-decoration:none; }
.bp-order-ref { font-size:10px; color:#ddd; text-align:center; margin-top:8px; }

/* ── Paid overlay ───────────────────────────────────── */
#bp-paid-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.55);
    align-items:center; justify-content:center; z-index:9999; flex-direction:column; gap:16px;
}
#bp-paid-overlay.show { display:flex; animation:bp-fadein .3s; }
@keyframes bp-fadein { from{opacity:0} to{opacity:1} }
.bp-paid-card {
    background:#fff; border-radius:20px; padding:36px 32px; text-align:center;
    box-shadow:0 16px 48px rgba(0,0,0,.25); max-width:320px; width:90%;
    animation:bp-bounceIn .4s;
}
@keyframes bp-bounceIn { 0%{transform:scale(.7);opacity:0} 70%{transform:scale(1.05)} 100%{transform:scale(1);opacity:1} }
.bp-paid-icon { font-size:56px; margin-bottom:12px; }
.bp-paid-title { font-size:20px; font-weight:800; color:#1a8c3e; margin-bottom:6px; }
.bp-paid-sub   { font-size:13px; color:#888; }
</style>

{{-- Paid overlay --}}
<div id="bp-paid-overlay">
    <div class="bp-paid-card">
        <div class="bp-paid-icon">✅</div>
        <div class="bp-paid-title">¡Pago confirmado!</div>
        <div class="bp-paid-sub">Redirigiendo a tu pedido...</div>
    </div>
</div>

<div class="bp-page">
<div class="bp-card">

    {{-- Header --}}
    <div class="bp-header">
        <div class="bp-logo-circle">
            <svg viewBox="0 0 24 24" fill="#1a1a1a">
                <path d="M12 2L9.5 4.5 12 7l2.5-2.5L12 2zM6 6l-2.5 2.5L6 11l2.5-2.5L6 6zm12 0l-2.5 2.5L18 11l2.5-2.5L18 6zM12 10l-2.5 2.5L12 15l2.5-2.5L12 10zm0 8l-2.5 2.5L12 23l2.5-2.5L12 18z"/>
            </svg>
        </div>
        <div>
            <div class="bp-brand-name">Binance Pay</div>
            <div class="bp-brand-sub">Pago en criptomonedas</div>
        </div>
    </div>

    {{-- Mock badge --}}
    @if(!empty($isMock))
    <div class="bp-mock-badge">
        <span class="dot"></span>
        🧪 MODO SIMULACIÓN — sin dinero real
    </div>
    @endif

    {{-- Selector de moneda --}}
    <div class="bp-coin-label">Selecciona tu moneda</div>
    <div class="bp-coins">
        @foreach($allCoins as $c)
        @php
            $networks = ['USDT'=>'TRC-20','BNB'=>'BEP-20','BTC'=>'Bitcoin','ETH'=>'ERC-20'];
            $icons    = ['USDT'=>'₮','BNB'=>'B','BTC'=>'₿','ETH'=>'Ξ'];
        @endphp
        <a href="{{ route('binance.checkout', ['order'=>$order->id, 'coin'=>$c]) }}"
           class="bp-coin-btn {{ $coin === $c ? 'active' : '' }}">
            <div class="bp-coin-icon {{ strtolower($c) }}">{{ $icons[$c] }}</div>
            <div class="bp-coin-name">{{ $c }}</div>
            <div class="bp-coin-net">{{ $networks[$c] }}</div>
        </a>
        @endforeach
    </div>

    {{-- Monto --}}
    <div class="bp-amount-box">
        <div class="bp-amount-label">Total a pagar</div>
        <div class="bp-amount-value" id="bp-amount-crypto">
            <span class="bp-spinner" style="width:18px;height:18px;border-color:rgba(240,185,11,.3);border-top-color:#b7860b;"></span>
        </div>
        <div class="bp-amount-rate">
            1 <strong>{{ $coin }}</strong> =
            <span class="live" id="bp-rate-display">cargando...</span>
            <span style="color:#ccc;font-size:9px;margin-left:4px;" id="bp-rate-age"></span>
        </div>
        <div class="bp-amount-bs">≈ Bs. {{ number_format($order->total, 2) }} · Pedido #{{ $order->order_number }}</div>
    </div>

    {{-- Status bar --}}
    <div class="bp-status-bar waiting" id="bp-status-bar">
        <span class="bp-spinner" id="bp-spinner"></span>
        <span id="bp-status-text">Esperando pago... escanea el QR con tu app Binance</span>
    </div>

    {{-- Countdown --}}
    <div class="bp-countdown-wrap" id="bp-countdown-wrap">
        <div class="label">Expira en</div>
        <div class="timer" id="bp-countdown">15:00</div>
    </div>

    {{-- QR --}}
    <div class="bp-qr-wrap">
        <div class="bp-qr-outer">
            <div class="bp-qr-frame">
                <img src="{{ $qrCodeLink }}" alt="Binance Pay QR" id="bp-qr-img">
            </div>
            <div class="bp-qr-coin-badge {{ strtolower($coin) }}">
                @php $icons2=['USDT'=>'₮','BNB'=>'B','BTC'=>'₿','ETH'=>'Ξ']; @endphp
                {{ $icons2[$coin] }}
            </div>
        </div>
        <div class="bp-qr-hint">
            1. Abre la app <strong>Binance</strong> &nbsp;→&nbsp;
            2. <strong>Pay → Escanear</strong> &nbsp;→&nbsp;
            3. Confirma el pago en {{ $coin }}
        </div>
    </div>

    {{-- Dirección copiable --}}
    <div class="bp-address-label">Dirección de envío ({{ $network }})</div>
    <div class="bp-address-box" id="bp-address-box" onclick="copyAddress()" title="Clic para copiar">
        <div class="bp-address-text" id="bp-address-text">{{ $address }}</div>
        <button class="bp-copy-btn" id="bp-copy-btn" type="button">📋 Copiar</button>
    </div>

    {{-- Pasos --}}
    <div class="bp-steps" id="bp-steps">
        <div class="bp-step done" id="step-1">
            <div class="bp-step-num">✓</div>
            <div class="bp-step-txt">Pedido<br>creado</div>
        </div>
        <div class="bp-step active" id="step-2">
            <div class="bp-step-num">2</div>
            <div class="bp-step-txt">Esperando<br>pago</div>
        </div>
        <div class="bp-step" id="step-3">
            <div class="bp-step-num">3</div>
            <div class="bp-step-txt">Confirmando<br>red</div>
        </div>
        <div class="bp-step" id="step-4">
            <div class="bp-step-num">4</div>
            <div class="bp-step-txt">¡Listo!</div>
        </div>
    </div>

    {{-- Botones mock --}}
    @if(!empty($isMock))
    <div class="bp-mock-actions">
        <form method="POST" action="{{ route('binance.mock.approve', $order->id) }}">
            @csrf
            <button type="submit" class="bp-btn-approve">✅ Simular pago aprobado</button>
        </form>
        <form method="POST" action="{{ route('binance.mock.reject', $order->id) }}">
            @csrf
            <button type="submit" class="bp-btn-reject">✕ Rechazar</button>
        </form>
    </div>
    @endif

    <a class="bp-cancel" href="{{ route('checkout') }}">← Cancelar y volver al carrito</a>
    <div class="bp-order-ref">PrepayID: {{ $order->binance_prepay_id }}</div>

</div>
</div>

<script>
(function () {
    // ── Config ──────────────────────────────────────────────────
    var COIN        = "{{ $coin }}";
    var COIN_ID     = {{ json_encode($coinGeckoIds[$coin]) }};
    var TOTAL_BOB   = {{ $order->total }};
    var ORDER_NUM   = "{{ $order->order_number }}";
    var ORDER_ID    = {{ $order->id }};
    var QUERY_URL   = "{{ route('binance.query', $order->id) }}";
    var SUCCESS_URL = "{{ route('home') }}";
    var EXPIRE_TIME = {{ $expireTime }};

    // ── DOM refs ────────────────────────────────────────────────
    var elAmount  = document.getElementById('bp-amount-crypto');
    var elRate    = document.getElementById('bp-rate-display');
    var elRateAge = document.getElementById('bp-rate-age');
    var elBar     = document.getElementById('bp-status-bar');
    var elTxt     = document.getElementById('bp-status-text');
    var elSpinner = document.getElementById('bp-spinner');
    var elCD      = document.getElementById('bp-countdown');
    var elCDWrap  = document.getElementById('bp-countdown-wrap');
    var overlay   = document.getElementById('bp-paid-overlay');

    var paid = false;
    var rateUSD = null; // precio 1 COIN en USD
    var rateBOB = null; // tipo de cambio BOB→USD (fijo)

    // ── Tasa de cambio BOB/USD (fija referencial) ───────────────
    var BOB_USD = 1 / 6.96; // 1 BOB ≈ 0.1437 USD

    // ── CoinGecko — obtener precio en USD ───────────────────────
    function fetchRate() {
        var url = 'https://api.coingecko.com/api/v3/simple/price?ids=' + COIN_ID + '&vs_currencies=usd&include_last_updated_at=true';
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data[COIN_ID] && data[COIN_ID].usd) {
                    rateUSD = data[COIN_ID].usd;
                    var updatedAt = data[COIN_ID].last_updated_at;
                    updateAmountDisplay(updatedAt);
                }
            })
            .catch(function () {
                // Fallback: tasa fija si CoinGecko no responde
                var fallback = { 'USDT': 1, 'BNB': 570, 'BTC': 65000, 'ETH': 3300 };
                rateUSD = fallback[COIN] || 1;
                updateAmountDisplay(null);
            });
    }

    function updateAmountDisplay(updatedAt) {
        // TOTAL_BOB → USD → COIN
        var totalUSD  = TOTAL_BOB * BOB_USD;
        var coinAmount;
        if (COIN === 'USDT') {
            coinAmount = totalUSD; // 1 USDT = 1 USD
        } else {
            coinAmount = totalUSD / rateUSD;
        }

        // Decimales según moneda
        var decimals = { 'USDT':2, 'BNB':4, 'BTC':6, 'ETH':5 };
        var dec = decimals[COIN] || 4;

        elAmount.innerHTML = coinAmount.toFixed(dec) + ' <span>' + COIN + '</span>';

        // Mostrar tasa
        var rateFormatted;
        if (COIN === 'USDT') {
            rateFormatted = '<span class="live">$1.00 USD</span>';
        } else {
            rateFormatted = '<span class="live">$' + rateUSD.toLocaleString('en-US', {minimumFractionDigits:2,maximumFractionDigits:2}) + ' USD</span>';
        }
        elRate.innerHTML = rateFormatted;

        if (updatedAt) {
            var age = Math.round((Date.now()/1000 - updatedAt) / 60);
            elRateAge.textContent = age <= 1 ? '(hace 1 min)' : '(hace ' + age + ' min)';
        } else {
            elRateAge.textContent = '(aprox.)';
        }
    }

    // Cargar tasa inmediatamente y refrescar cada 60s
    fetchRate();
    setInterval(fetchRate, 60000);

    // ── Copiar dirección ────────────────────────────────────────
    window.copyAddress = function () {
        var addr = document.getElementById('bp-address-text').textContent.trim();
        var btn  = document.getElementById('bp-copy-btn');
        if (navigator.clipboard) {
            navigator.clipboard.writeText(addr).then(function () {
                btn.textContent = '✅ Copiado';
                btn.classList.add('copied');
                setTimeout(function () { btn.textContent = '📋 Copiar'; btn.classList.remove('copied'); }, 2000);
            });
        } else {
            // Fallback
            var el = document.createElement('textarea');
            el.value = addr; document.body.appendChild(el);
            el.select(); document.execCommand('copy'); document.body.removeChild(el);
            btn.textContent = '✅ Copiado'; btn.classList.add('copied');
            setTimeout(function () { btn.textContent = '📋 Copiar'; btn.classList.remove('copied'); }, 2000);
        }
    };

    // ── Countdown ───────────────────────────────────────────────
    var cdInterval = setInterval(function () {
        var rem = EXPIRE_TIME - Date.now();
        if (rem <= 0) {
            clearInterval(cdInterval);
            if (!paid) {
                elCD.textContent = '00:00';
                elCD.classList.add('urgent');
                elBar.className = 'bp-status-bar expired';
                elTxt.innerHTML = '⏰ Código expirado. <a href="' + window.location.href + '">Generar nuevo →</a>';
                if (elSpinner) elSpinner.style.display = 'none';
            }
            return;
        }
        var mins = Math.floor(rem / 60000);
        var secs = Math.floor((rem % 60000) / 1000);
        elCD.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
        if (rem < 120000) elCD.classList.add('urgent'); // últimos 2 min
    }, 1000);

    // ── Polling de estado ────────────────────────────────────────
    function setStep(n) {
        for (var i = 1; i <= 4; i++) {
            var s = document.getElementById('step-' + i);
            if (!s) continue;
            s.className = 'bp-step' + (i < n ? ' done' : i === n ? ' active' : '');
            if (i < n) s.querySelector('.bp-step-num').textContent = '✓';
        }
    }

    function checkStatus() {
        if (paid) return;
        fetch(QUERY_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'PAID') {
                    paid = true;
                    clearInterval(cdInterval);
                    clearInterval(pollInterval);

                    // Paso 3: confirmando
                    setStep(3);
                    elBar.className = 'bp-status-bar waiting';
                    elTxt.innerHTML = '<span class="bp-spinner"></span> Confirmando en la red...';

                    setTimeout(function () {
                        // Paso 4: listo
                        setStep(4);
                        elBar.className = 'bp-status-bar paid';
                        elTxt.innerHTML = '✅ ¡Pago confirmado!';
                        if (elSpinner) elSpinner.style.display = 'none';
                        elCDWrap.style.display = 'none';

                        // Mostrar overlay animado
                        overlay.classList.add('show');

                        try { sessionStorage.setItem('toast_success', '¡Pago con Binance Pay confirmado! Pedido #' + ORDER_NUM); } catch(e){}
                        setTimeout(function () { window.location.href = SUCCESS_URL; }, 2200);
                    }, 1200);
                }
            })
            .catch(function () { /* silencioso */ });
    }

    var pollInterval;
    setTimeout(function () {
        checkStatus();
        pollInterval = setInterval(checkStatus, 5000);
    }, 3000);
})();
</script>
@endsection
