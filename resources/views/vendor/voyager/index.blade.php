@extends('voyager::master')

{{-- ══════════════════════════════════════════════════════════════════
     DASHBOARD PERSONALIZADO — Panel de administración NextLevelGamer
     Muestra estadísticas clave, gráfico de ventas mensuales y
     tabla de los últimos 8 pedidos.
     Chart.js se carga al final del @section('javascript').
     ══════════════════════════════════════════════════════════════════ --}}

@section('page_title', 'Dashboard')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-boat"></i> Dashboard
        <small>Resumen de NextLevelGamer</small>
    </h1>
@stop

@section('css')
    <style>
        /* ── Tarjetas de estadística ─────────────────────────────────── */
        .stat-card {
            border-radius: 6px;
            padding: 22px 24px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }
        .stat-card .stat-icon {
            font-size: 38px;
            opacity: .85;
        }
        .stat-card .stat-value {
            font-size: 30px;
            font-weight: 700;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 12px;
            opacity: .85;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        /* Paleta de colores por tarjeta */
        .stat-blue   { background: linear-gradient(135deg, #2980b9, #1a5276); }
        .stat-green  { background: linear-gradient(135deg, #27ae60, #1a6b3c); }
        .stat-orange { background: linear-gradient(135deg, #e67e22, #a04000); }
        .stat-red    { background: linear-gradient(135deg, #e74c3c, #922b21); }

        /* ── Panel de gráfico ────────────────────────────────────────── */
        .dash-panel {
            background: #fff;
            border-radius: 6px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }
        .dash-panel h4 {
            font-size: 14px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 16px;
            border-bottom: 2px solid #f5f5f5;
            padding-bottom: 10px;
        }

        /* ── Tabla últimos pedidos ───────────────────────────────────── */
        .orders-table th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #888;
            font-weight: 600;
        }
        .orders-table td { vertical-align: middle !important; }

        /* Badges de estado de pedido */
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-pending    { background: #fef3cd; color: #856404; }
        .badge-processing { background: #cce5ff; color: #004085; }
        .badge-completed  { background: #d4edda; color: #155724; }
        .badge-declined   { background: #f8d7da; color: #721c24; }
    </style>
@stop

@section('content')
@php
    /* ── Consultas de estadísticas para los widgets ─────────────────
     * Se ejecutan directamente en la vista para no requerir un
     * controlador adicional. Se usan los modelos ya existentes.
     * ─────────────────────────────────────────────────────────────── */
    use App\Models\Order;
    use App\Models\Producto;
    use App\Models\User;

    $totalPedidos     = Order::count();
    $pedidosPendientes= Order::where('status', 'pending')->count();
    $totalProductos   = Producto::count();
    $ingresoTotal     = Order::where('status', 'completed')->sum('total');
    $ultimosPedidos   = Order::with('user')->latest()->take(8)->get();

    // Ventas por mes — últimos 6 meses (para el gráfico de barras)
    $meses = [];
    $ventas = [];
    for ($i = 5; $i >= 0; $i--) {
        $fecha = \Carbon\Carbon::now()->subMonths($i);
        $meses[]  = $fecha->translatedFormat('M Y');
        $ventas[] = Order::whereYear('created_at', $fecha->year)
                         ->whereMonth('created_at', $fecha->month)
                         ->sum('total');
    }

    // Top 5 productos más vendidos por unidades
    $topProductos = \DB::table('order_items')
        ->join('productos', 'order_items.product_id', '=', 'productos.id')
        ->select(
            'productos.id',
            'productos.nombre',
            'productos.cover_img',
            \DB::raw('SUM(order_items.quantity) as total_vendido'),
            \DB::raw('SUM(order_items.quantity * order_items.price) as ingresos')
        )
        ->groupBy('productos.id', 'productos.nombre', 'productos.cover_img')
        ->orderByDesc('total_vendido')
        ->limit(5)
        ->get();

    // Arrays para Chart.js — nombres truncados + unidades vendidas
    $topNombres  = $topProductos->map(fn($p) => \Illuminate\Support\Str::limit($p->nombre, 28))->values()->toArray();
    $topUnidades = $topProductos->pluck('total_vendido')->toArray();
    $topIngresos = $topProductos->pluck('ingresos')->toArray();
@endphp

<div class="page-content container-fluid">

    {{-- ── Fila de tarjetas de estadística ──────────────────────────── --}}
    <div class="row">
        {{-- Total de pedidos --}}
        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="voyager-bag"></i></div>
                <div>
                    <div class="stat-value">{{ $totalPedidos }}</div>
                    <div class="stat-label">Total Pedidos</div>
                </div>
            </div>
        </div>

        {{-- Ingresos totales --}}
        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="voyager-dollar"></i></div>
                <div>
                    <div class="stat-value">Bs {{ number_format($ingresoTotal, 0) }}</div>
                    <div class="stat-label">Ingresos (completados)</div>
                </div>
            </div>
        </div>

        {{-- Productos en catálogo --}}
        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-orange">
                <div class="stat-icon"><i class="voyager-box"></i></div>
                <div>
                    <div class="stat-value">{{ $totalProductos }}</div>
                    <div class="stat-label">Productos</div>
                </div>
            </div>
        </div>

        {{-- Pedidos pendientes --}}
        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-red">
                <div class="stat-icon"><i class="voyager-clock"></i></div>
                <div>
                    <div class="stat-value">{{ $pedidosPendientes }}</div>
                    <div class="stat-label">Pedidos Pendientes</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Gráfico de ventas mensuales ────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-8">
            <div class="dash-panel">
                <h4><i class="voyager-bar-chart"></i> Ventas de los últimos 6 meses (Bs)</h4>
                <canvas id="chartVentas" height="110"></canvas>
            </div>
        </div>

        {{-- ── Mini resumen lateral ──────────────────────────────────── --}}
        <div class="col-md-4">
            <div class="dash-panel">
                <h4><i class="voyager-list"></i> Estado de pedidos</h4>
                @php
                    $estados = [
                        'pending'    => ['label' => 'Pendiente',   'class' => 'badge-pending'],
                        'processing' => ['label' => 'En proceso',  'class' => 'badge-processing'],
                        'completed'  => ['label' => 'Completado',  'class' => 'badge-completed'],
                        'declined'   => ['label' => 'Rechazado',   'class' => 'badge-declined'],
                    ];
                @endphp
                <table class="table orders-table" style="margin-bottom:0">
                    @foreach($estados as $key => $cfg)
                    @php $cnt = Order::where('status',$key)->count(); @endphp
                    <tr>
                        <td><span class="badge-status {{ $cfg['class'] }}">{{ $cfg['label'] }}</span></td>
                        <td class="text-right"><strong>{{ $cnt }}</strong></td>
                        <td class="text-right" style="color:#aaa;font-size:12px;">
                            {{ $totalPedidos > 0 ? round($cnt/$totalPedidos*100) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    {{-- ── Top 5 productos más vendidos ─────────────────────────────────── --}}
    <div class="row">

        {{-- Gráfico de barras horizontales --}}
        <div class="col-md-7">
            <div class="dash-panel">
                <h4><i class="voyager-trophy"></i> Top 5 productos más vendidos (unidades)</h4>
                <canvas id="chartTopProductos" height="160"></canvas>
            </div>
        </div>

        {{-- Tabla detalle con imagen, nombre e ingresos --}}
        <div class="col-md-5">
            <div class="dash-panel">
                <h4><i class="voyager-list"></i> Detalle de ventas</h4>
                <table class="table orders-table" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th colspan="2">Producto</th>
                            <th class="text-right">Uds</th>
                            <th class="text-right">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProductos as $i => $tp)
                        <tr>
                            <td style="width:28px;padding-right:4px;">
                                {{-- Posición con color de medalla --}}
                                <span style="
                                    display:inline-flex;align-items:center;justify-content:center;
                                    width:22px;height:22px;border-radius:50%;font-size:11px;font-weight:700;
                                    background:{{ ['#f39c12','#bdc3c7','#cd7f32','#ecf0f1','#ecf0f1'][$i] }};
                                    color:{{ $i < 3 ? '#fff' : '#888' }};">
                                    {{ $i + 1 }}
                                </span>
                            </td>
                            <td style="font-size:12px;font-weight:500;">
                                {{ \Illuminate\Support\Str::limit($tp->nombre, 32) }}
                            </td>
                            <td class="text-right" style="font-weight:700;color:#2980b9;">
                                {{ $tp->total_vendido }}
                            </td>
                            <td class="text-right" style="font-size:12px;color:#27ae60;font-weight:600;">
                                Bs {{ number_format($tp->ingresos, 0) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center" style="color:#aaa;padding:20px;">
                                Sin ventas registradas aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ── Tabla de últimos pedidos ────────────────────────────────────── --}}
    <div class="row">
        <div class="col-md-12">
            <div class="dash-panel">
                <h4><i class="voyager-news"></i> Últimos pedidos</h4>
                <table class="table table-hover orders-table" style="margin-bottom:0">
                    <thead>
                        <tr>
                            <th>N° Pedido</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Método Pago</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPedidos as $order)
                        @php
                            /* Mapeo de estado a clase badge */
                            $badgeClass = [
                                'pending'    => 'badge-pending',
                                'processing' => 'badge-processing',
                                'completed'  => 'badge-completed',
                                'declined'   => 'badge-declined',
                            ][$order->status] ?? 'badge-pending';

                            $estadoLabel = [
                                'pending'    => 'Pendiente',
                                'processing' => 'En proceso',
                                'completed'  => 'Completado',
                                'declined'   => 'Rechazado',
                            ][$order->status] ?? $order->status;
                        @endphp
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user->name ?? $order->shipping_fullname }}</td>
                            <td><span class="badge-status {{ $badgeClass }}">{{ $estadoLabel }}</span></td>
                            <td style="text-transform:capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</td>
                            <td><strong>Bs {{ number_format($order->total, 2) }}</strong></td>
                            <td style="color:#888;font-size:12px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('voyager.orders.show', $order->id) }}"
                                   class="btn btn-xs btn-info">
                                    <i class="voyager-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center" style="color:#aaa;padding:30px">
                                No hay pedidos registrados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@stop

@section('javascript')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── Gráfico de barras — ventas mensuales ────────────────────────────
 * Usa Chart.js v4. Los datos vienen de las consultas PHP de arriba
 * serializados como JSON para pasarlos a JS de forma segura.
 * ─────────────────────────────────────────────────────────────────── */
(function () {
    var ctx = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($meses),
            datasets: [{
                label: 'Ventas (Bs)',
                data: @json($ventas),
                backgroundColor: 'rgba(41, 128, 185, 0.75)',
                borderColor: '#2980b9',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ' Bs ' + ctx.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(v) { return 'Bs ' + v; }
                    }
                }
            }
        }
    });
})();

/* ── Gráfico horizontal — Top 5 productos más vendidos ─────────────
 * Barras horizontales para que los nombres de producto quepan bien.
 * Paleta degradada: el primero más oscuro, el último más claro.
 * ─────────────────────────────────────────────────────────────────── */
(function () {
    var ctx = document.getElementById('chartTopProductos').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($topNombres),
            datasets: [{
                label: 'Unidades vendidas',
                data: @json($topUnidades),
                backgroundColor: [
                    'rgba(39,  174, 96,  0.85)',
                    'rgba(41,  128, 185, 0.80)',
                    'rgba(142, 68,  173, 0.75)',
                    'rgba(230, 126, 34,  0.70)',
                    'rgba(149, 165, 166, 0.65)',
                ],
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',   /* barras horizontales */
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var ingresos = @json($topIngresos);
                            return ' ' + ctx.parsed.x + ' uds  |  Bs ' + ingresos[ctx.dataIndex].toFixed(0);
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                },
                y: {
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
})();
</script>
@stop
