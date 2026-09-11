@extends('voyager::master')

{{-- ══════════════════════════════════════════════════════════════════
     BROWSE PERSONALIZADO — Listado de Pedidos (Orders)
     Mejoras sobre el listado por defecto de Voyager:
       - Filtros de estado por pestañas (Todos / Pendiente / En proceso / Completado / Rechazado)
       - Badges de color por estado
       - Columna de método de pago con ícono
       - Buscador por número de pedido o cliente (JS, sin recarga)
       - Botones de acción: Ver detalle / Editar / Eliminar
     ══════════════════════════════════════════════════════════════════ --}}

@section('page_title', 'Pedidos')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bag"></i> Pedidos
    </h1>

    {{-- Botón exportar CSV — genera el archivo en el cliente sin recargar --}}
    <button id="btn-export-csv" class="btn btn-default"
            style="margin-right:8px;" title="Exportar pedidos visibles a CSV">
        <i class="voyager-download"></i> <span>Exportar CSV</span>
    </button>

    @can('add', app(\App\Models\Order::class))
        <a href="{{ route('voyager.orders.create') }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>Nuevo Pedido</span>
        </a>
    @endcan
@stop

@section('css')
    <style>
        /* ── Filtros de estado ───────────────────────────────────── */
        .orders-filter-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .filter-btn {
            border: none;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            background: #f0f0f0;
            color: #555;
        }
        .filter-btn.active, .filter-btn:hover { color: #fff; }
        .filter-btn.active.f-all, .filter-btn.f-all:hover       { background: #555; }
        .filter-btn.active.f-pending, .filter-btn.f-pending:hover   { background: #856404; }
        .filter-btn.active.f-processing, .filter-btn.f-processing:hover { background: #004085; }
        .filter-btn.active.f-completed, .filter-btn.f-completed:hover  { background: #155724; }
        .filter-btn.active.f-declined, .filter-btn.f-declined:hover   { background: #721c24; }

        /* ── Buscador ────────────────────────────────────────────── */
        .orders-search {
            margin-left: auto;
            position: relative;
        }
        .orders-search input {
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 5px 14px 5px 32px;
            font-size: 13px;
            outline: none;
            width: 220px;
        }
        .orders-search .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 13px;
        }

        /* ── Badges de estado ───────────────────────────────────── */
        .badge-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-pending    { background: #fef3cd; color: #856404; }
        .badge-processing { background: #cce5ff; color: #004085; }
        .badge-completed  { background: #d4edda; color: #155724; }
        .badge-declined   { background: #f8d7da; color: #721c24; }

        /* ── Tabla ───────────────────────────────────────────────── */
        #orders-table th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #888;
            font-weight: 600;
            white-space: nowrap;
        }
        #orders-table td { vertical-align: middle !important; }
        .payment-icon { font-size: 15px; margin-right: 4px; }
    </style>
@stop

@section('content')
@php
    /* ── Carga de pedidos paginados con usuario relacionado ─────────
     * Se ordena por fecha de creación descendente para ver los más
     * recientes primero. Se aplica filtro de estado si viene en GET.
     * ─────────────────────────────────────────────────────────────── */
    $filterStatus = request('status', 'all');
    $query = \App\Models\Order::with('user')->latest();
    if ($filterStatus !== 'all') {
        $query->where('status', $filterStatus);
    }
    $orders = $query->paginate(15)->appends(request()->query());
@endphp

<div class="page-content browse container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered" style="padding:20px 24px 10px;">

                {{-- ── Barra de filtros + buscador ─────────────────────── --}}
                <div class="orders-filter-bar">
                    @php
                        /* Conteo por estado para mostrar en cada botón */
                        $counts = [
                            'all'        => \App\Models\Order::count(),
                            'pending'    => \App\Models\Order::where('status','pending')->count(),
                            'processing' => \App\Models\Order::where('status','processing')->count(),
                            'completed'  => \App\Models\Order::where('status','completed')->count(),
                            'declined'   => \App\Models\Order::where('status','declined')->count(),
                        ];
                    @endphp

                    <a href="{{ request()->fullUrlWithQuery(['status'=>'all']) }}"
                       class="filter-btn f-all {{ $filterStatus==='all' ? 'active' : '' }}">
                        Todos ({{ $counts['all'] }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status'=>'pending']) }}"
                       class="filter-btn f-pending {{ $filterStatus==='pending' ? 'active' : '' }}">
                        Pendiente ({{ $counts['pending'] }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status'=>'processing']) }}"
                       class="filter-btn f-processing {{ $filterStatus==='processing' ? 'active' : '' }}">
                        En proceso ({{ $counts['processing'] }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status'=>'completed']) }}"
                       class="filter-btn f-completed {{ $filterStatus==='completed' ? 'active' : '' }}">
                        Completado ({{ $counts['completed'] }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status'=>'declined']) }}"
                       class="filter-btn f-declined {{ $filterStatus==='declined' ? 'active' : '' }}">
                        Rechazado ({{ $counts['declined'] }})
                    </a>

                    {{-- Buscador cliente-side por N° pedido o nombre --}}
                    <div class="orders-search">
                        <i class="voyager-search search-icon"></i>
                        <input type="text" id="order-search" placeholder="Buscar pedido o cliente…">
                    </div>
                </div>

                {{-- ── Tabla de pedidos ─────────────────────────────────── --}}
                <table class="table table-hover" id="orders-table">
                    <thead>
                        <tr>
                            <th>N° Pedido</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Método de Pago</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Pagado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            /* Mapeo de estados a clases CSS y etiquetas en español */
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

                            /* Ícono por método de pago */
                            $payIcon = match($order->payment_method) {
                                'paypal'           => '🅿️',
                                'stripe', 'card'   => '💳',
                                'binance'          => '🟡',
                                'yape'             => '📱',
                                default            => '💵',
                            };

                            $payLabel = match($order->payment_method) {
                                'cash_on_delivery' => 'Contra entrega',
                                'paypal'           => 'PayPal',
                                'stripe'           => 'Stripe',
                                'card'             => 'Tarjeta',
                                'binance'          => 'Binance Pay',
                                'yape'             => 'Yape QR',
                                default            => ucfirst($order->payment_method),
                            };
                        @endphp
                        {{-- data-* attributes con valores limpios para la exportación CSV --}}
                        <tr class="order-row"
                            data-id="{{ $order->order_number }}"
                            data-cliente="{{ $order->user->name ?? $order->shipping_fullname }}"
                            data-email="{{ $order->user->email ?? '' }}"
                            data-status="{{ $estadoLabel }}"
                            data-payment="{{ $payLabel }}"
                            data-items="{{ $order->item_count }}"
                            data-total="{{ number_format($order->total, 2, '.', '') }}"
                            data-paid="{{ $order->is_paid ? 'Sí' : 'No' }}"
                            data-fecha="{{ $order->created_at->format('d/m/Y H:i') }}">
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user->name ?? $order->shipping_fullname }}</td>
                            <td><span class="badge-status {{ $badgeClass }}">{{ $estadoLabel }}</span></td>
                            <td>
                                <span class="payment-icon">{{ $payIcon }}</span>
                                {{ $payLabel }}
                            </td>
                            <td class="text-center">{{ $order->item_count }}</td>
                            <td><strong>Bs {{ number_format($order->total, 2) }}</strong></td>
                            <td class="text-center">
                                @if($order->is_paid)
                                    <span style="color:#27ae60;font-size:16px;">✓</span>
                                @else
                                    <span style="color:#e74c3c;font-size:16px;">✗</span>
                                @endif
                            </td>
                            <td style="color:#888;font-size:12px;white-space:nowrap;">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td style="white-space:nowrap;">
                                {{-- Ver detalle --}}
                                <a href="{{ route('voyager.orders.show', $order->id) }}"
                                   class="btn btn-sm btn-info" title="Ver detalle">
                                    <i class="voyager-eye"></i>
                                </a>
                                {{-- Editar --}}
                                @can('edit', $order)
                                <a href="{{ route('voyager.orders.edit', $order->id) }}"
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="voyager-edit"></i>
                                </a>
                                @endcan
                                {{-- Eliminar --}}
                                @can('delete', $order)
                                <a href="javascript:;"
                                   class="btn btn-sm btn-danger btn-delete"
                                   data-id="{{ $order->id }}"
                                   data-name="#{{ $order->order_number }}"
                                   title="Eliminar">
                                    <i class="voyager-trash"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding:40px;color:#aaa;">
                                No hay pedidos con este estado.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- ── Paginación ───────────────────────────────────────── --}}
                <div class="text-center">
                    {{ $orders->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmación de eliminación --}}
<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <i class="voyager-trash"></i> ¿Eliminar pedido <span id="del-name"></span>?
                </h4>
            </div>
            <div class="modal-footer">
                <form id="delete_form" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger pull-right">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
/* ── Buscador cliente-side ──────────────────────────────────────────
 * Filtra las filas visibles de la tabla por N° pedido o nombre
 * de cliente sin hacer petición al servidor.
 * ─────────────────────────────────────────────────────────────────── */
document.getElementById('order-search').addEventListener('keyup', function () {
    var term = this.value.toLowerCase();
    document.querySelectorAll('#orders-table .order-row').forEach(function (row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.indexOf(term) > -1 ? '' : 'none';
    });
});

/* ── Exportar pedidos visibles a CSV ───────────────────────────────
 *
 * Cómo funciona:
 *  1. Lee las filas visibles de la tabla (las que no están ocultas por el filtro).
 *  2. Extrae el texto de cada celda relevante usando data-* attributes
 *     para evitar parsear iconos o badges.
 *  3. Construye un string CSV con encabezado y lo convierte a Blob.
 *  4. Crea un <a> temporal con URL del Blob y simula el clic para descargarlo.
 *     (Los Blobs son temporales en memoria — no se envía nada al servidor.)
 *  5. Revoca la URL del Blob para liberar memoria.
 * ─────────────────────────────────────────────────────────────────── */
document.getElementById('btn-export-csv').addEventListener('click', function () {

    // Columnas del encabezado del CSV
    var headers = ['N° Pedido', 'Cliente', 'Email', 'Estado', 'Método Pago', 'Items', 'Total (Bs)', 'Pagado', 'Fecha'];

    var rows = [headers];

    // Recorrer solo filas visibles (respeta el filtro activo)
    document.querySelectorAll('#orders-table .order-row').forEach(function (row) {
        if (row.style.display === 'none') return;

        // Leer datos desde data-attributes de la fila para obtener valores limpios
        // (sin HTML de badges, iconos, etc.)
        var cells = [
            row.dataset.id       || '',
            row.dataset.cliente  || '',
            row.dataset.email    || '',
            row.dataset.status   || '',
            row.dataset.payment  || '',
            row.dataset.items    || '',
            row.dataset.total    || '',
            row.dataset.paid     || '',
            row.dataset.fecha    || '',
        ];

        // Escapar celdas que contengan comas o comillas (estándar CSV RFC 4180)
        var escaped = cells.map(function (cell) {
            var str = String(cell).replace(/"/g, '""');
            return /[",\n]/.test(str) ? '"' + str + '"' : str;
        });

        rows.push(escaped);
    });

    // Unir filas con salto de línea y agregar BOM para que Excel lo abra bien
    var csvContent = '﻿' + rows.map(function (r) { return r.join(','); }).join('\r\n');

    // Crear Blob y descargarlo
    var blob = URL.createObjectURL(new Blob([csvContent], { type: 'text/csv;charset=utf-8;' }));
    var link = document.createElement('a');
    link.href = blob;
    // Nombre del archivo: pedidos_YYYY-MM-DD.csv
    var today = new Date().toISOString().slice(0, 10);
    link.download = 'pedidos_' + today + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(blob); // liberar memoria
});

/* ── Botón eliminar — rellena el modal con el ID correcto ───────── */
document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id   = this.dataset.id;
        var name = this.dataset.name;
        document.getElementById('del-name').textContent = name;
        document.getElementById('delete_form').action =
            '{{ url("admin/orders") }}/' + id;
        document.getElementById('delete_modal') &&
            $('#delete_modal').modal('show');
    });
});
</script>
@stop
