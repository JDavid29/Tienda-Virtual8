<div>
    {{-- ===== ESTILOS SCOPED DEL COMPONENTE ===== --}}
    <style>
        .co-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            padding: 28px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 20px;
        }
        .co-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 700;
            color: #222;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #fed700;
        }
        .co-section-title .co-icon {
            width: 32px; height: 32px;
            background: #fed700;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }
        .co-field { margin-bottom: 16px; }
        .co-field label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .co-field label i { color: #aaa; font-size: 12px; }
        .co-field label .req { color: #e74c3c; margin-left: 2px; }
        .co-input-wrap { position: relative; }
        .co-input-wrap .co-prefix {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: #bbb; font-size: 14px; pointer-events: none;
        }
        .co-input-wrap .co-input {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background: #fafafa;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .co-input-wrap .co-input:focus {
            border-color: #fed700;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(254,215,0,0.15);
        }
        .co-input-wrap .co-input.is-invalid {
            border-color: #e74c3c;
            background: #fff8f8;
        }
        .co-input-wrap .co-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(231,76,60,0.12);
        }
        .co-select { appearance: none; cursor: pointer; }
        .co-select-arrow {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            color: #bbb; font-size: 11px; pointer-events: none;
        }
        .co-err { color: #e74c3c; font-size: 11px; margin-top: 4px; display: block; }
        /* Tabla pedido */
        .order-table { width: 100%; border-collapse: collapse; }
        .order-table thead tr { background: #f8f8f8; }
        .order-table thead th {
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #666;
            border-bottom: 2px solid #f0f0f0;
        }
        .order-table tbody tr { border-bottom: 1px solid #f5f5f5; transition: background .15s; }
        .order-table tbody tr:hover { background: #fffdf0; }
        .order-table tbody td { padding: 10px 12px; vertical-align: middle; }
        .order-table tfoot tr td,
        .order-table tfoot tr th { padding: 10px 12px; font-size: 13px; }
        .order-table tfoot .row-subtotal { background: #f9f9f9; }
        .order-table tfoot .row-total { background: #fffbe6; }
        .order-table tfoot .row-total th,
        .order-table tfoot .row-total td { font-weight: 700; font-size: 15px; color: #222; }
        .prod-thumb {
            width: 48px; height: 48px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
            background: #f5f5f5;
        }
        .prod-thumb-placeholder {
            width: 48px; height: 48px;
            border-radius: 6px;
            background: #f0f0f0;
            display: flex; align-items: center; justify-content: center;
            color: #ccc; font-size: 18px;
            border: 1px solid #eee;
            flex-shrink: 0;
        }
    </style>

    <!--Checkout Area Start-->
    <div class="checkout-area pt-60 pb-30">
        <div class="container">
            <div class="row">

                {{-- ======== COLUMNA IZQUIERDA: FORMULARIO ======== --}}
                <div class="col-lg-6 col-12">

                    {{-- Tarjeta: Dirección de Envío --}}
                    <div class="co-card">
                        <div class="co-section-title">
                            <span class="co-icon"><i class="fa fa-truck"></i></span>
                            Dirección de Envío
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="co-field">
                                    <label><i class="fa fa-user-o"></i> Nombre completo <span class="req">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-user co-prefix"></i>
                                        <input wire:model.defer="shipping_fullname" type="text"
                                            placeholder="Ej: Juan Pérez López"
                                            class="co-input @error('shipping_fullname') is-invalid @enderror">
                                    </div>
                                    @error('shipping_fullname')<span class="co-err"><i class="fa fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="co-field">
                                    <label><i class="fa fa-map-marker"></i> Dirección de entrega <span class="req">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-map-marker co-prefix"></i>
                                        <input wire:model.defer="shipping_address" type="text"
                                            placeholder="Ej: Calle Comercio #890, Piso 2"
                                            class="co-input @error('shipping_address') is-invalid @enderror">
                                    </div>
                                    @error('shipping_address')<span class="co-err"><i class="fa fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="co-field">
                                    <label><i class="fa fa-building-o"></i> Ciudad <span class="req">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-building-o co-prefix"></i>
                                        <input wire:model.defer="shipping_city" type="text"
                                            placeholder="Ej: Cochabamba"
                                            class="co-input @error('shipping_city') is-invalid @enderror">
                                    </div>
                                    @error('shipping_city')<span class="co-err">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="co-field">
                                    <label><i class="fa fa-globe"></i> Departamento <span class="req">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-globe co-prefix"></i>
                                        <select wire:model.defer="shipping_state"
                                            class="co-input co-select @error('shipping_state') is-invalid @enderror">
                                            <option value="">Seleccionar...</option>
                                            <option>La Paz</option><option>Cochabamba</option>
                                            <option>Santa Cruz</option><option>Oruro</option>
                                            <option>Potosí</option><option>Chuquisaca</option>
                                            <option>Tarija</option><option>Beni</option>
                                            <option>Pando</option>
                                        </select>
                                        <i class="fa fa-chevron-down co-select-arrow"></i>
                                    </div>
                                    @error('shipping_state')<span class="co-err">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="co-field">
                                    <label><i class="fa fa-hashtag"></i> Código postal <span class="req">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-hashtag co-prefix"></i>
                                        <input wire:model.defer="shipping_zipcode" type="text"
                                            placeholder="Ej: 0000"
                                            class="co-input @error('shipping_zipcode') is-invalid @enderror">
                                    </div>
                                    @error('shipping_zipcode')<span class="co-err">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="co-field">
                                    <label><i class="fa fa-phone"></i> Teléfono <span class="req">*</span></label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-phone co-prefix"></i>
                                        <input wire:model.defer="shipping_phone" type="tel"
                                            placeholder="+591 7XXXXXXX"
                                            class="co-input @error('shipping_phone') is-invalid @enderror">
                                    </div>
                                    @error('shipping_phone')<span class="co-err">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="co-field">
                                    <label><i class="fa fa-commenting-o"></i> Notas del pedido</label>
                                    <div class="co-input-wrap">
                                        <i class="fa fa-commenting-o co-prefix" style="top:16px;transform:none;"></i>
                                        <textarea wire:model.defer="notes" rows="3"
                                            placeholder="Instrucciones especiales para la entrega, referencias del lugar, etc."
                                            class="co-input" style="padding-top:10px;resize:vertical;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ======== COLUMNA DERECHA: PEDIDO + PAGO ======== --}}
                <div class="col-lg-6 col-12">
                    <div class="co-card">
                        {{-- Título tabla --}}
                        <div class="co-section-title">
                            <span class="co-icon"><i class="fa fa-shopping-bag"></i></span>
                            Su Pedido
                        </div>

                        <div class="your-order-table table-responsive">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th style="width:56px;"><i class="fa fa-image" style="color:#ccc;"></i></th>
                                        <th><i class="fa fa-tag" style="color:#fed700;margin-right:5px;"></i>Producto</th>
                                        <th style="text-align:right;"><i class="fa fa-money" style="color:#27ae60;margin-right:5px;"></i>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cartItems as $item)
                                        @php
                                            $img = $item['attributes']['image']
                                                ?? $item['attributes']['cover_img']
                                                ?? null;
                                        @endphp
                                        <tr>
                                            {{-- Mini imagen --}}
                                            <td>
                                                @if($img)
                                                    <img src="{{ Str::startsWith($img, ['http','//']) ? $img : asset('storage/'.$img) }}"
                                                         alt="{{ $item['name'] }}"
                                                         class="prod-thumb">
                                                @else
                                                    <div class="prod-thumb-placeholder">
                                                        <i class="fa fa-cube"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            {{-- Nombre + cantidad --}}
                                            <td>
                                                <span style="font-size:13px;font-weight:600;color:#222;">
                                                    {{ \Illuminate\Support\Str::limit($item['name'], 40) }}
                                                </span>
                                                <br>
                                                <span style="font-size:11px;color:#888;">
                                                    <i class="fa fa-times" style="font-size:9px;"></i>
                                                    {{ $item['quantity'] }} unid.
                                                    &nbsp;·&nbsp;
                                                    <i class="fa fa-tag" style="font-size:9px;color:#aaa;"></i>
                                                    {{ $currencySymbol }}{{ number_format($item['price'], 2) }} c/u
                                                </span>
                                            </td>
                                            {{-- Total --}}
                                            <td style="text-align:right;">
                                                <strong style="font-size:14px;color:#222;">
                                                    {{ $currencySymbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" style="text-align:center;padding:24px;color:#aaa;">
                                                <i class="fa fa-shopping-cart" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                                No hay productos en el carrito.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="row-subtotal">
                                        <td colspan="2">
                                            <i class="fa fa-list-ul" style="color:#aaa;margin-right:6px;"></i>
                                            <span style="font-size:13px;color:#555;">Subtotal</span>
                                        </td>
                                        <td style="text-align:right;font-size:13px;color:#555;">
                                            {{ $currencySymbol }}{{ number_format($subtotal ?? 0, 2) }}
                                        </td>
                                    </tr>
                                    <tr class="row-total">
                                        <td colspan="2">
                                            <i class="fa fa-check-circle" style="color:#fed700;margin-right:6px;"></i>
                                            <strong>Total del pedido</strong>
                                        </td>
                                        <td style="text-align:right;">
                                            <strong style="font-size:16px;color:#e74c3c;">
                                                {{ $currencySymbol }}{{ number_format($cartTotal ?? $subtotal ?? 0, 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                        {{-- ===== MÉTODO DE PAGO (estilo AliExpress) ===== --}}
                        <div class="payment-method" style="margin-top:24px;">

                            @if($orderError)
                                <div style="background:#fdf2f2;border:1.5px solid #e74c3c;border-radius:8px;padding:12px 14px;margin-bottom:14px;font-size:13px;color:#922b21;">
                                    ❌ {{ $orderError }}
                                </div>
                            @endif

                            @if($orderSuccess)
                                <div style="background:#eafaf1;border:2px solid #27ae60;border-radius:10px;padding:16px 18px;margin-bottom:16px;">
                                    <div style="font-size:15px;font-weight:700;color:#1e8449;margin-bottom:8px;">
                                        ✅ ¡Pedido registrado con éxito!
                                    </div>
                                    <div style="font-size:13px;color:#2d6a4f;line-height:1.7;">
                                        {{ $orderSuccess }}
                                    </div>
                                    <div style="margin-top:10px;font-size:12px;color:#555;background:#d5f5e3;padding:8px 10px;border-radius:6px;">
                                        📦 Tu pedido está <strong>en proceso</strong>. Lo confirmaremos en cuanto verifiquemos tu pago.
                                        Puedes hacer seguimiento desde <strong>Mi Cuenta</strong>.
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div style="background:#fdf2f2;border:1.5px solid #e74c3c;border-radius:8px;padding:12px 14px;margin-bottom:14px;font-size:13px;color:#922b21;">
                                    ❌ {{ session('error') }}
                                </div>
                            @endif
                            @if(session('success'))
                                <div style="background:#eafaf1;border:1.5px solid #27ae60;border-radius:8px;padding:12px 14px;margin-bottom:14px;font-size:13px;color:#1e8449;">
                                    ✅ {{ session('success') }}
                                </div>
                            @endif

                            {{-- Título sección --}}
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                                <span style="width:4px;height:18px;background:#e74c3c;border-radius:2px;display:inline-block;"></span>
                                <strong style="font-size:15px;color:#222;">Método de pago</strong>
                            </div>

                            {{-- Tarjetas de método: radio inputs ocultos + labels enlazados --}}
                            @php
                                $methods = [
                                    'cash_on_delivery' => ['emoji'=>'🚚','label'=>'Contra entrega','sub'=>'📍 Solo en Yacuiba',      'color'=>'#e74c3c','bg'=>'#fff5f5'],
                                    'paypal'           => ['emoji'=>'💳','label'=>'PayPal / Tarjeta','sub'=>'Visa · Mastercard · PayPal','color'=>'#003087','bg'=>'#e8f4fd'],
                                    'stripe'           => ['emoji'=>'🔵','label'=>'Stripe',          'sub'=>'Tarjeta crédito/débito',   'color'=>'#635bff','bg'=>'#f0eeff'],
                                    'yape'             => ['emoji'=>'🟣','label'=>'Yape',            'sub'=>'Pago por QR',              'color'=>'#6c2d8a','bg'=>'#f9f0ff'],
                                    'binance'          => ['emoji'=>'🟡','label'=>'Binance Pay',     'sub'=>'Cripto / USDT',            'color'=>'#b7860b','bg'=>'#fffbe6'],
                                ];
                            @endphp

                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px;">
                                @foreach($methods as $value => $m)
                                    @php $selected = $payment_method === $value; @endphp
                                    <div wire:click="$set('payment_method','{{ $value }}')"
                                         style="cursor:pointer;user-select:none;
                                                border:2px solid {{ $selected ? $m['color'] : '#e0e0e0' }};
                                                border-radius:8px;padding:14px 12px;
                                                background:{{ $selected ? $m['bg'] : '#fff' }};
                                                position:relative;transition:border-color .15s,background .15s;">

                                        @if($selected)
                                            <span style="position:absolute;top:-1px;right:-1px;
                                                         background:{{ $m['color'] }};color:#fff;
                                                         font-size:10px;padding:2px 7px;
                                                         border-radius:0 6px 0 6px;font-weight:700;">✓</span>
                                        @endif

                                        <div style="display:flex;align-items:center;gap:10px;pointer-events:none;">
                                            <span style="font-size:24px;line-height:1;">{{ $m['emoji'] }}</span>
                                            <div>
                                                <div style="font-weight:700;font-size:13px;color:{{ $m['color'] }};">{{ $m['label'] }}</div>
                                                <div style="font-size:11px;color:#888;">{{ $m['sub'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Info contextual del método seleccionado --}}
                            @if($payment_method === 'paypal')
                                <div style="background:#e8f4fd;border:1px solid #b3d4f5;border-radius:8px;padding:12px 14px;margin-bottom:14px;">
                                    <div style="font-size:12px;color:#003087;margin-bottom:8px;">
                                        🔒 Serás redirigido al sitio seguro de <strong>PayPal</strong>. Puedes pagar con:
                                    </div>
                                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                        <span style="background:#1a1f71;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:4px;">VISA</span>
                                        <span style="background:#eb001b;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:4px;">MasterCard</span>
                                        <span style="background:#003087;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:4px;">PayPal</span>
                                        <span style="background:#009cde;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:4px;">American Express</span>
                                    </div>
                                    <div style="font-size:11px;color:#555;margin-top:8px;">
                                        💡 No necesitas cuenta PayPal — puedes pagar directo con tu tarjeta.
                                    </div>
                                </div>
                            @elseif($payment_method === 'stripe')
                                <div style="background:#f0eeff;border-left:3px solid #635bff;border-radius:6px;padding:10px 14px;font-size:12px;color:#635bff;margin-bottom:14px;">
                                    🔒 Serás redirigido a <strong>Stripe</strong> para pagar con tarjeta de forma segura.
                                </div>
                            @elseif($payment_method === 'cash_on_delivery')
                                <div style="background:#fff5f5;border:1.5px solid #e74c3c;border-radius:8px;padding:12px 14px;margin-bottom:14px;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                        <span style="font-size:16px;">🚚</span>
                                        <strong style="font-size:13px;color:#c0392b;">Contra entrega — pago en efectivo</strong>
                                    </div>
                                    <ul style="margin:0;padding-left:18px;font-size:12px;color:#555;line-height:2;">
                                        <li>💵 <strong>Ten el monto exacto disponible</strong></li>
                                        <li>
                                            <span style="display:inline-flex;align-items:center;gap:4px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                                Disponible <strong>solo dentro de Yacuiba</strong>
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                            @elseif($payment_method === 'yape')
                                {{-- Panel Yape Manual (QR real) --}}
                                <div style="background:#f9f0ff;border:2px solid #6c2d8a;border-radius:10px;padding:16px;margin-bottom:14px;">
                                    {{-- Header --}}
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                        <span style="font-size:22px;">🟣</span>
                                        <strong style="color:#6c2d8a;font-size:15px;">Paga con Yape</strong>
                                    </div>

                                    {{-- QR + datos --}}
                                    <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap;">

                                        {{-- QR imagen --}}
                                        <div style="background:#fff;border:2px solid #6c2d8a;border-radius:12px;padding:10px;text-align:center;flex-shrink:0;">
                                            <img src="{{ asset('images/yape-qr.svg') }}"
                                                 alt="QR Yape"
                                                 style="width:120px;height:120px;display:block;">
                                            <div style="font-size:10px;color:#6c2d8a;font-weight:600;margin-top:6px;">Escanea con Yape</div>
                                        </div>

                                        {{-- Info --}}
                                        <div style="flex:1;min-width:160px;">
                                            <div style="font-size:12px;color:#444;line-height:2;">
                                                <div>📱 <strong>Número:</strong>
                                                    <span style="color:#6c2d8a;font-size:15px;font-weight:700;letter-spacing:0.5px;">+591 70547372</span>
                                                </div>
                                                <div>👤 <strong>A nombre de:</strong> Tienda Virtual</div>
                                                <div>💰 <strong>Monto:</strong>
                                                    <span style="color:#6c2d8a;font-size:15px;font-weight:700;">
                                                        Bs. {{ number_format($cartTotal ?? $subtotal ?? 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div style="margin-top:10px;background:#fff3cd;padding:8px 10px;border-radius:8px;color:#856404;font-size:11px;line-height:1.6;">
                                                ⚠️ Después de yapear, envía el <strong>comprobante</strong> por WhatsApp al
                                                <strong>+591 70547372</strong> con tu nombre. Tu pedido se confirma en minutos.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @elseif($payment_method === 'binance')
                                {{-- Panel Binance Pay QR estático --}}
                                <div style="background:#fffbe6;border:2px solid #f0b90b;border-radius:10px;padding:16px;margin-bottom:14px;">
                                    {{-- Header --}}
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                        <span style="font-size:22px;">🟡</span>
                                        <strong style="color:#b7860b;font-size:15px;">Paga con Binance Pay</strong>
                                    </div>

                                    {{-- QR + datos --}}
                                    <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap;">

                                        {{-- QR imagen --}}
                                        <div style="background:#fff;border:2px solid #f0b90b;border-radius:12px;padding:10px;text-align:center;flex-shrink:0;">
                                            <img src="{{ asset('images/binance-qr.svg') }}"
                                                 alt="QR Binance Pay"
                                                 style="width:120px;height:120px;display:block;">
                                            <div style="font-size:10px;color:#b7860b;font-weight:600;margin-top:6px;">Escanea con Binance</div>
                                        </div>

                                        {{-- Info --}}
                                        <div style="flex:1;min-width:160px;">
                                            <div style="font-size:12px;color:#444;line-height:2;">
                                                <div>🆔 <strong>Binance ID:</strong>
                                                    <span style="color:#b7860b;font-size:15px;font-weight:700;letter-spacing:0.5px;">123456789</span>
                                                </div>
                                                <div>👤 <strong>A nombre de:</strong> Tienda Virtual</div>
                                                <div>🪙 <strong>Acepta:</strong> USDT · BNB · BUSD</div>
                                                <div>💰 <strong>Monto:</strong>
                                                    <span style="color:#b7860b;font-size:15px;font-weight:700;">
                                                        ≈ ${{ number_format(($cartTotal ?? $subtotal ?? 0) / 6.96, 2) }} USDT
                                                    </span>
                                                    <span style="font-size:11px;color:#aaa;">(Bs. {{ number_format($cartTotal ?? $subtotal ?? 0, 2) }})</span>
                                                </div>
                                            </div>

                                            <div style="margin-top:10px;background:#fff3cd;padding:8px 10px;border-radius:8px;color:#856404;font-size:11px;line-height:1.6;">
                                                ⚠️ Después de pagar, envía el <strong>comprobante o hash</strong> por WhatsApp al
                                                <strong>+591 70547372</strong> con tu nombre y número de pedido.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif

                            {{-- Logos de seguridad --}}
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;padding:8px 12px;
                                        background:#f9f9f9;border-radius:6px;border:1px solid #eee;">
                                <i class="fa fa-lock" style="color:#27ae60;font-size:14px;"></i>
                                <span style="font-size:11px;color:#666;">Pago 100% seguro y encriptado</span>
                                <span style="margin-left:auto;display:flex;gap:6px;align-items:center;">
                                    <span style="background:#1a1f71;color:#fff;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;">VISA</span>
                                    <span style="background:#eb001b;color:#fff;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;">MC</span>
                                    <span style="background:#003087;color:#fff;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;">PP</span>
                                </span>
                            </div>

                            {{-- Botón de confirmar pedido --}}
                            <div class="order-button-payment">
                                @if($payment_method === 'paypal')
                                    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
                                        style="width:100%;padding:14px;background:#ffc439;border:none;border-radius:8px;
                                               font-weight:700;font-size:15px;color:#003087;cursor:pointer;
                                               box-shadow:0 2px 8px rgba(0,0,0,0.15);transition:opacity .2s;">
                                        <span wire:loading.remove wire:target="placeOrder">
                                            Pagar con PayPal →
                                        </span>
                                        <span wire:loading wire:target="placeOrder">⏳ Procesando...</span>
                                    </button>
                                @elseif($payment_method === 'stripe')
                                    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
                                        style="width:100%;padding:14px;background:#635bff;border:none;border-radius:8px;
                                               font-weight:700;font-size:15px;color:#fff;cursor:pointer;
                                               box-shadow:0 2px 8px rgba(99,91,255,0.4);transition:opacity .2s;">
                                        <span wire:loading.remove wire:target="placeOrder">
                                            💳 Pagar con Stripe →
                                        </span>
                                        <span wire:loading wire:target="placeOrder">⏳ Procesando...</span>
                                    </button>
                                @elseif($payment_method === 'yape')
                                    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
                                        style="width:100%;padding:14px;background:#6c2d8a;border:none;border-radius:8px;
                                               font-weight:700;font-size:15px;color:#fff;cursor:pointer;
                                               box-shadow:0 2px 8px rgba(108,45,138,0.4);transition:opacity .2s;">
                                        <span wire:loading.remove wire:target="placeOrder">
                                            🟣 Pagar con Yape →
                                        </span>
                                        <span wire:loading wire:target="placeOrder">⏳ Procesando pago...</span>
                                    </button>
                                @elseif($payment_method === 'binance')
                                    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
                                        style="width:100%;padding:14px;background:#f0b90b;border:none;border-radius:8px;
                                               font-weight:700;font-size:15px;color:#1a1a1a;cursor:pointer;
                                               box-shadow:0 2px 8px rgba(240,185,11,0.4);transition:opacity .2s;">
                                        <span wire:loading.remove wire:target="placeOrder">
                                            🟡 Confirmar pedido — pagaré con Binance →
                                        </span>
                                        <span wire:loading wire:target="placeOrder">⏳ Registrando pedido...</span>
                                    </button>
                                @else
                                    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
                                        style="width:100%;padding:14px;background:#e74c3c;border:none;border-radius:8px;
                                               font-weight:700;font-size:15px;color:#fff;cursor:pointer;
                                               box-shadow:0 2px 8px rgba(231,76,60,0.35);transition:opacity .2s;">
                                        <span wire:loading.remove wire:target="placeOrder">
                                            Confirmar pedido →
                                        </span>
                                        <span wire:loading wire:target="placeOrder">⏳ Procesando...</span>
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Checkout Area End-->
</div>
