<div>
<style>
/* ── Cuenta general ──────────────────────────────────────── */
.mc-wrap   { padding: 48px 0 64px; background: #f8f9fa; min-height: 80vh; }
.mc-avatar-wrap { background: linear-gradient(135deg,#fed700,#f5a623); padding: 28px 20px; text-align: center; }
.mc-avatar  { width: 72px; height: 72px; border-radius: 50%; background: #fff;
              display: flex; align-items: center; justify-content: center;
              margin: 0 auto 10px; box-shadow: 0 4px 12px rgba(0,0,0,.12); font-size: 28px; }
.mc-uname   { font-weight: 800; font-size: 15px; color: #1a1a1a; margin-bottom: 2px; }
.mc-uemail  { font-size: 11px; color: #555; }

.mc-sidebar { background: #fff; border-radius: 12px; overflow: hidden;
              box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.mc-nav-item { display: flex; align-items: center; gap: 10px;
               padding: 13px 20px; border-bottom: 1px solid #f0f0f0;
               font-weight: 600; font-size: 14px; color: #444;
               text-decoration: none; cursor: pointer; transition: all .15s; }
.mc-nav-item:hover   { background: #fffbe6; color: #b7860b; text-decoration: none; }
.mc-nav-item.active  { background: #fffbe6; color: #b7860b; border-left: 3px solid #fed700; }
.mc-nav-item .badge  { margin-left: auto; background: #fed700; color: #1a1a1a;
                        border-radius: 20px; padding: 1px 9px; font-size: 11px; }
.mc-nav-item.danger  { color: #e74c3c; }
.mc-nav-item.danger:hover { background: #fff5f5; }

/* ── Panel principal ──────────────────────────────────────── */
.mc-panel   { background: #fff; border-radius: 12px;
              box-shadow: 0 2px 12px rgba(0,0,0,.06); padding: 28px; }
.mc-panel-title { font-size: 17px; font-weight: 800; color: #1a1a1a;
                   border-left: 4px solid #fed700; padding-left: 12px; margin-bottom: 24px; }

/* ── Stats bar ─────────────────────────────────────────────── */
.mc-stats    { display: flex; gap: 12px; margin-bottom: 22px; flex-wrap: wrap; }
.mc-stat     { flex: 1; min-width: 100px; background: #f8f9fa; border-radius: 10px;
               padding: 14px 16px; text-align: center; border: 1.5px solid #f0f0f0; }
.mc-stat-num { font-size: 22px; font-weight: 900; color: #1a1a1a; line-height: 1; }
.mc-stat-lbl { font-size: 10px; color: #aaa; text-transform: uppercase; letter-spacing: .5px; margin-top: 4px; }
.mc-stat.yellow  { border-color: #fed700; background: #fffbe6; }
.mc-stat.green   { border-color: #27ae60; background: #eafaf1; }
.mc-stat.blue    { border-color: #3498db; background: #eaf4fd; }

/* ── Filtros ──────────────────────────────────────────────── */
.mc-filters  { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.mc-filter   { padding: 6px 16px; border-radius: 20px; border: 1.5px solid #e8e8e8;
               font-size: 12px; font-weight: 600; cursor: pointer; transition: all .15s;
               background: #fff; color: #666; }
.mc-filter:hover  { border-color: #fed700; color: #b7860b; }
.mc-filter.active { background: #fed700; border-color: #fed700; color: #1a1a1a; }

/* ── Tarjeta de pedido ──────────────────────────────────────── */
.order-card  { border: 1.5px solid #efefef; border-radius: 12px; margin-bottom: 14px;
               overflow: hidden; transition: box-shadow .15s; }
.order-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.order-head  { display: flex; align-items: center; gap: 14px; padding: 16px 20px;
               cursor: pointer; user-select: none; }
.order-head:hover { background: #fafafa; }

.order-num   { font-size: 13px; font-weight: 700; color: #1a1a1a; }
.order-date  { font-size: 11px; color: #aaa; }

.order-badge { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; }
.badge-pending    { background: #fff3cd; color: #856404; }
.badge-processing { background: #cce5ff; color: #004085; }
.badge-completed  { background: #d4edda; color: #155724; }
.badge-cancelled  { background: #f8d7da; color: #721c24; }

.order-total { font-size: 15px; font-weight: 900; color: #1a1a1a; margin-left: auto; white-space: nowrap; }

.pay-icon    { display: inline-flex; align-items: center; gap: 5px; font-size: 12px;
               font-weight: 600; padding: 3px 10px; border-radius: 6px; white-space: nowrap; }
.pay-cod     { background: #f0f0f0; color: #555; }
.pay-paypal  { background: #e8f0fb; color: #003087; }
.pay-stripe  { background: #f0eaff; color: #5851d8; }
.pay-binance { background: #fffbe6; color: #b7860b; }
.pay-yape    { background: #f3e6f7; color: #6c2d8a; }

.chevron     { font-size: 13px; color: #ccc; transition: transform .2s; margin-left: 8px; }
.chevron.open { transform: rotate(180deg); }

/* ── Detalle expandible ──────────────────────────────────────── */
.order-body  { border-top: 1.5px solid #f5f5f5; padding: 0 20px 20px; background: #fafafa; }
.order-body-inner { padding-top: 16px; }

.order-items-title { font-size: 11px; font-weight: 700; color: #aaa;
                     text-transform: uppercase; letter-spacing: .5px; margin-bottom: 12px; }
.order-item-row    { display: flex; align-items: center; gap: 12px; padding: 10px 0;
                     border-bottom: 1px solid #f0f0f0; }
.order-item-row:last-child { border-bottom: none; }
.order-item-img    { width: 50px; height: 50px; border-radius: 8px; object-fit: cover;
                     border: 1px solid #eee; background: #f5f5f5; flex-shrink: 0; }
.order-item-img-placeholder { width: 50px; height: 50px; border-radius: 8px; background: #f0f0f0;
                              display: flex; align-items: center; justify-content: center;
                              font-size: 20px; flex-shrink: 0; }
.order-item-name   { font-size: 13px; font-weight: 600; color: #1a1a1a; flex: 1; }
.order-item-qty    { font-size: 12px; color: #aaa; }
.order-item-price  { font-size: 13px; font-weight: 700; color: #1a1a1a; white-space: nowrap; }

.order-info-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; }
.order-info-box    { background: #fff; border: 1px solid #efefef; border-radius: 8px; padding: 12px 14px; }
.order-info-lbl    { font-size: 10px; color: #aaa; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.order-info-val    { font-size: 13px; font-weight: 600; color: #333; }

/* ── Estado vacío ─────────────────────────────────────────── */
.mc-empty    { text-align: center; padding: 48px 20px; }
.mc-empty-icon { font-size: 52px; margin-bottom: 12px; opacity: .4; }
.mc-empty-txt  { font-size: 15px; color: #aaa; margin-bottom: 20px; }

/* ── Paginación ──────────────────────────────────────────── */
.mc-pag { display: flex; justify-content: center; margin-top: 20px; }
.mc-pag .pagination { margin: 0; }
.mc-pag .page-item .page-link { border-color: #e8e8e8; color: #555; border-radius: 6px !important; margin: 0 2px; }
.mc-pag .page-item.active .page-link { background: #fed700; border-color: #fed700; color: #1a1a1a; font-weight: 700; }

/* ── Perfil info ─────────────────────────────────────────── */
.perfil-field  { margin-bottom: 20px; }
.perfil-label  { font-size: 10px; color: #aaa; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 3px; }
.perfil-value  { font-size: 15px; font-weight: 600; color: #1a1a1a; }

/* ── Responsive ──────────────────────────────────────────── */
@media(max-width:767px) {
    .order-head { flex-wrap: wrap; gap: 8px; }
    .order-total { margin-left: 0; }
    .order-info-grid { grid-template-columns: 1fr; }
    .mc-stats { gap: 8px; }
}
</style>

<div class="mc-wrap">
<div class="container">
<div class="row">

    {{-- ── Sidebar ─────────────────────────────────────── --}}
    <div class="col-lg-3 mb-4">
        <div class="mc-sidebar">
            <div class="mc-avatar-wrap">
                <div class="mc-avatar">👤</div>
                <div class="mc-uname">{{ $user->name }} {{ $user->last_name }}</div>
                <div class="mc-uemail">{{ $user->email }}</div>
            </div>
            <nav>
                <a class="mc-nav-item {{ $tab === 'perfil' ? 'active' : '' }}"
                   wire:click.prevent="cambiarTab('perfil')">
                    🙍 Mi Perfil
                </a>
                <a class="mc-nav-item {{ $tab === 'pedidos' ? 'active' : '' }}"
                   wire:click.prevent="cambiarTab('pedidos')">
                    🛍️ Mis Pedidos
                    @if($totalPedidos > 0)
                        <span class="badge">{{ $totalPedidos }}</span>
                    @endif
                </a>
                <a class="mc-nav-item" href="{{ route('wishlist') }}">
                    🤍 Lista de Deseos
                </a>
                <a class="mc-nav-item danger" href="#"
                   onclick="event.preventDefault(); document.getElementById('mc-logout').submit();">
                    🚪 Cerrar Sesión
                </a>
                <form id="mc-logout" action="{{ route('voyager.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
        </div>
    </div>

    {{-- ── Contenido ───────────────────────────────────── --}}
    <div class="col-lg-9">

        {{-- ══ TAB PERFIL ══ --}}
        @if($tab === 'perfil')
        <div class="mc-panel">
            <div class="mc-panel-title">Información Personal</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="perfil-field">
                        <div class="perfil-label">Nombre completo</div>
                        <div class="perfil-value">{{ $user->name }} {{ $user->last_name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="perfil-field">
                        <div class="perfil-label">Correo electrónico</div>
                        <div class="perfil-value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="perfil-field">
                        <div class="perfil-label">Teléfono</div>
                        <div class="perfil-value">{{ $user->phone ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="perfil-field">
                        <div class="perfil-label">Fecha de nacimiento</div>
                        <div class="perfil-value">
                            {{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') : '—' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="perfil-field">
                        <div class="perfil-label">Miembro desde</div>
                        <div class="perfil-value">{{ \Carbon\Carbon::parse($user->created_at)->format('d \d\e F, Y') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="perfil-field">
                        <div class="perfil-label">Total de pedidos</div>
                        <div class="perfil-value">{{ $totalPedidos }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ══ TAB PEDIDOS ══ --}}
        @if($tab === 'pedidos')
        <div class="mc-panel">
            <div class="mc-panel-title">Historial de Pedidos</div>

            @php
                $allPedidos = \App\Models\Order::where('user_id', auth()->id());
                $totalGastado  = $allPedidos->sum('total');
                $totalPagados  = (clone $allPedidos)->where('is_paid', true)->count();
                $totalPendientes = (clone $allPedidos)->where('status', 'pending')->count();
            @endphp

            {{-- Stats --}}
            <div class="mc-stats">
                <div class="mc-stat yellow">
                    <div class="mc-stat-num">{{ $totalPedidos }}</div>
                    <div class="mc-stat-lbl">Total pedidos</div>
                </div>
                <div class="mc-stat green">
                    <div class="mc-stat-num">{{ $totalPagados }}</div>
                    <div class="mc-stat-lbl">Pagados</div>
                </div>
                <div class="mc-stat blue">
                    <div class="mc-stat-num">Bs. {{ number_format($totalGastado, 0) }}</div>
                    <div class="mc-stat-lbl">Total gastado</div>
                </div>
                <div class="mc-stat">
                    <div class="mc-stat-num">{{ $totalPendientes }}</div>
                    <div class="mc-stat-lbl">Pendientes</div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="mc-filters">
                @foreach(['todos'=>'Todos', 'pending'=>'⏳ Pendiente', 'processing'=>'🔄 En proceso', 'completed'=>'✅ Completado', 'cancelled'=>'❌ Cancelado'] as $val => $lbl)
                    <button class="mc-filter {{ $filtroEstado === $val ? 'active' : '' }}"
                            wire:click="setFiltro('{{ $val }}')">{{ $lbl }}</button>
                @endforeach
            </div>

            {{-- Lista de pedidos --}}
            @if($pedidos->isEmpty())
                <div class="mc-empty">
                    <div class="mc-empty-icon">🛍️</div>
                    <div class="mc-empty-txt">
                        {{ $filtroEstado === 'todos' ? 'Aún no has realizado ningún pedido.' : 'No hay pedidos con este estado.' }}
                    </div>
                    @if($filtroEstado === 'todos')
                        <a href="{{ route('list.product') }}" class="li-button li-button-sm">
                            <span>Ir a la Tienda</span>
                        </a>
                    @else
                        <button class="mc-filter active" wire:click="setFiltro('todos')">Ver todos</button>
                    @endif
                </div>
            @else
                @foreach($pedidos as $pedido)
                @php
                    $badgeClass = [
                        'pending'    => 'badge-pending',
                        'processing' => 'badge-processing',
                        'completed'  => 'badge-completed',
                        'cancelled'  => 'badge-cancelled',
                    ][$pedido->status] ?? 'badge-pending';

                    $badgeLbl = [
                        'pending'    => '⏳ Pendiente',
                        'processing' => '🔄 En proceso',
                        'completed'  => '✅ Completado',
                        'cancelled'  => '❌ Cancelado',
                    ][$pedido->status] ?? ucfirst($pedido->status);

                    $payIcons = [
                        'cash_on_delivery' => ['🚚', 'Contra entrega', 'pay-cod'],
                        'paypal'           => ['🅿️', 'PayPal', 'pay-paypal'],
                        'stripe'           => ['💳', 'Stripe', 'pay-stripe'],
                        'card'             => ['💳', 'Tarjeta', 'pay-stripe'],
                        'binance'          => ['🟡', 'Binance Pay', 'pay-binance'],
                        'yape'             => ['🟣', 'Yape', 'pay-yape'],
                    ];
                    $pay = $payIcons[$pedido->payment_method] ?? ['💰', ucfirst($pedido->payment_method), 'pay-cod'];
                    $isOpen = $pedidoAbierto === $pedido->id;
                @endphp

                <div class="order-card">
                    {{-- Cabecera clicable --}}
                    <div class="order-head" wire:click="togglePedido({{ $pedido->id }})">
                        {{-- Número y fecha --}}
                        <div>
                            <div class="order-num">#{{ $pedido->order_number }}</div>
                            <div class="order-date">
                                {{ \Carbon\Carbon::parse($pedido->created_at)->format('d M Y, H:i') }}
                            </div>
                        </div>

                        {{-- Estado --}}
                        <span class="order-badge {{ $badgeClass }}">{{ $badgeLbl }}</span>

                        {{-- Método de pago --}}
                        <span class="pay-icon {{ $pay[2] }}">
                            {{ $pay[0] }} {{ $pay[1] }}
                            @if($pedido->is_paid)
                                <span style="color:#27ae60;font-size:13px;" title="Pagado">✓</span>
                            @endif
                        </span>

                        {{-- Total --}}
                        <div class="order-total">Bs. {{ number_format($pedido->total, 2) }}</div>

                        {{-- Chevron --}}
                        <span class="chevron {{ $isOpen ? 'open' : '' }}">▼</span>
                    </div>

                    {{-- Detalle expandible --}}
                    @if($isOpen)
                    <div class="order-body">
                        <div class="order-body-inner">

                            {{-- Productos --}}
                            <div class="order-items-title">🛒 Productos ({{ $pedido->item_count }})</div>
                            @forelse($pedido->items as $item)
                                <div class="order-item-row">
                                    @if($item->cover_img)
                                        <img class="order-item-img"
                                             src="{{ asset('storage/' . $item->cover_img) }}"
                                             alt="{{ $item->nombre }}"
                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <div class="order-item-img-placeholder" style="display:none;">📦</div>
                                    @else
                                        <div class="order-item-img-placeholder">📦</div>
                                    @endif
                                    <div class="order-item-name">{{ $item->nombre ?: 'Producto' }}</div>
                                    <div class="order-item-qty">x{{ $item->pivot->quantity }}</div>
                                    <div class="order-item-price">Bs. {{ number_format($item->pivot->price, 2) }}</div>
                                </div>
                            @empty
                                <p style="color:#aaa;font-size:13px;">No se encontraron detalles de productos.</p>
                            @endforelse

                            {{-- Info adicional --}}
                            <div class="order-info-grid">
                                <div class="order-info-box">
                                    <div class="order-info-lbl">📦 Envío a</div>
                                    <div class="order-info-val">
                                        {{ $pedido->shipping_fullname }}<br>
                                        <span style="font-weight:400;color:#888;font-size:12px;">
                                            {{ $pedido->shipping_address }}, {{ $pedido->shipping_city }}
                                        </span>
                                    </div>
                                </div>
                                <div class="order-info-box">
                                    <div class="order-info-lbl">💰 Resumen</div>
                                    <div class="order-info-val">
                                        Total: <strong>Bs. {{ number_format($pedido->total, 2) }}</strong><br>
                                        <span style="font-weight:400;color:#888;font-size:12px;">
                                            Estado pago:
                                            @if($pedido->is_paid)
                                                <span style="color:#27ae60;font-weight:700;">✅ Pagado</span>
                                            @else
                                                <span style="color:#e67e22;font-weight:700;">⏳ Pendiente</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @if($pedido->notes)
                                <div class="order-info-box" style="grid-column:1/-1;">
                                    <div class="order-info-lbl">📝 Notas</div>
                                    <div class="order-info-val" style="font-weight:400;color:#555;">{{ $pedido->notes }}</div>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    @endif
                </div>
                @endforeach

                {{-- Paginación --}}
                <div class="mc-pag">
                    {{ $pedidos->links() }}
                </div>
            @endif

        </div>
        @endif

    </div>{{-- /col-lg-9 --}}
</div>{{-- /row --}}
</div>{{-- /container --}}
</div>{{-- /mc-wrap --}}
</div>
