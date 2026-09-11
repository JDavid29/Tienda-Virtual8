@extends('voyager::master')

{{-- ══════════════════════════════════════════════════════════════════
     BROWSE PERSONALIZADO — Listado de Productos
     Columnas: imagen, nombre + descripción limpia, precio, stock, categoría, acciones
     Sin columna Referencia (contiene URLs largas no aptas para listar)
     ══════════════════════════════════════════════════════════════════ --}}

@section('page_title', 'Productos')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-box"></i> Productos
    </h1>
    @can('add', app(\App\Models\Producto::class))
        <a href="{{ route('voyager.productos.create') }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>Nuevo Producto</span>
        </a>
    @endcan
@stop

@section('css')
<style>
/* ── Filtros por categoría ───────────────────────────────────── */
.cat-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f0f0f0;
}
.cat-btn {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #f4f4f4;
    color: #666;
    text-decoration: none;
    transition: all .18s;
    white-space: nowrap;
}
.cat-btn:hover { background: #dde8f5; color: #1a6aac; text-decoration: none; }
.cat-btn.active { background: #2980b9; color: #fff; text-decoration: none; }

/* ── Barra de herramientas (buscador + filtros) ──────────────── */
.toolbar-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    padding: 12px 14px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #efefef;
}

/* Input de búsqueda por texto */
.toolbar-search {
    position: relative;
    flex: 1;
    min-width: 180px;
    max-width: 280px;
}
.toolbar-search input {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 7px 12px 7px 32px;
    font-size: 13px;
    outline: none;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%23aaa' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.868-3.833zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z'/%3E%3C/svg%3E") no-repeat 10px center;
}
.toolbar-search input:focus { border-color: #2980b9; }

/* Separador visual entre grupos de filtros */
.toolbar-sep {
    width: 1px;
    height: 28px;
    background: #ddd;
    flex-shrink: 0;
}

/* Grupo de filtros de precio */
.price-range {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #888;
}
.price-range input {
    width: 80px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 8px;
    font-size: 12px;
    outline: none;
    text-align: center;
    background: #fff;
}
.price-range input:focus { border-color: #2980b9; }

/* Select de filtro de stock y ordenamiento */
.toolbar-select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 6px 10px;
    font-size: 12px;
    color: #555;
    background: #fff;
    outline: none;
    cursor: pointer;
}
.toolbar-select:focus { border-color: #2980b9; }

/* Botón limpiar filtros */
.btn-clear-filters {
    font-size: 12px;
    color: #e74c3c;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px 6px;
    display: none; /* solo visible cuando hay filtros activos */
    white-space: nowrap;
}
.btn-clear-filters:hover { text-decoration: underline; }

/* Contador de resultados al final */
.results-count { font-size: 12px; color: #aaa; margin-left: auto; white-space: nowrap; }

/* ── Miniatura ───────────────────────────────────────────────── */
.prod-thumb {
    width: 56px;
    height: 56px;
    object-fit: contain;
    border: 1px solid #eee;
    border-radius: 6px;
    background: #fafafa;
    display: block;
}

/* ── Badges de stock ─────────────────────────────────────────── */
.stock-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.stock-ok  { background: #d4edda; color: #155724; }
.stock-low { background: #fff3cd; color: #856404; }
.stock-out { background: #f8d7da; color: #721c24; }

/* ── Input de edición rápida de stock ───────────────────────── */
.stock-input-wrap { position: relative; display: inline-flex; align-items: center; gap: 6px; }
.stock-input {
    width: 64px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    padding: 4px 8px;
    font-size: 13px;
    font-weight: 700;
    text-align: center;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    background: #fff;
}
.stock-input:focus {
    border-color: #2980b9;
    box-shadow: 0 0 0 3px rgba(41,128,185,.15);
}
/* Animación de guardado exitoso */
.stock-input.saved {
    border-color: #27ae60;
    box-shadow: 0 0 0 3px rgba(39,174,96,.15);
    transition: border-color .1s;
}
.stock-input.error {
    border-color: #e74c3c;
    box-shadow: 0 0 0 3px rgba(231,76,60,.15);
}
/* Spinner mientras guarda */
.stock-saving {
    display: none;
    width: 14px;
    height: 14px;
    border: 2px solid #ddd;
    border-top-color: #2980b9;
    border-radius: 50%;
    animation: spin .6s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.cat-badge {
    display: inline-block;
    background: #edf3fc;
    color: #2471a3;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

/* ── Tabla ───────────────────────────────────────────────────── */
.prod-table-wrap { overflow-x: auto; }
#productos-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}
#productos-table th {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #aaa;
    font-weight: 700;
    padding: 0 12px 12px;
    border-bottom: 2px solid #f0f0f0;
    white-space: nowrap;
}
#productos-table td {
    padding: 14px 12px;
    vertical-align: middle !important;
    border-bottom: 1px solid #f7f7f7;
}
#productos-table tbody tr:last-child td { border-bottom: none; }
#productos-table tbody tr:hover { background: #f9fbff; }

/* Anchos fijos — columnas: img, nombre, precio, stock, categoría, proveedor, acciones */
#productos-table .col-img    { width: 72px; }
#productos-table .col-nombre { width: 34%; }
#productos-table .col-precio { width: 110px; }
#productos-table .col-stock  { width: 100px; text-align: center; }
#productos-table .col-cat    { width: 120px; }
#productos-table .col-prov   { width: 120px; }
#productos-table .col-acc    { width: 120px; text-align: right; }

/* Badge de proveedor */
.prov-badge {
    display: inline-block;
    background: #f0f0f0;
    color: #666;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    white-space: nowrap;
}

/* Nombre del producto */
.prod-nombre { font-size: 14px; font-weight: 600; color: #333; display: block; line-height: 1.3; }
.prod-desc   { font-size: 11px; color: #aaa; margin-top: 3px; display: block; line-height: 1.4; }
.prod-precio { font-size: 14px; font-weight: 700; color: #2c3e50; }
.prod-id     { font-size: 10px; color: #ccc; display: block; margin-top: 2px; }
</style>
@stop

@section('content')
@php
    /* ── Consulta paginada con relación category ────────────────── */
    $filterCat  = request('category_id', 'all');
    // Se agrega 'proveedor' al eager loading para evitar N+1 queries
    $query      = \App\Models\Producto::with('category', 'proveedor')->latest();
    if ($filterCat !== 'all' && is_numeric($filterCat)) {
        $query->where('category_id', $filterCat);
    }
    $productos  = $query->paginate(20)->appends(request()->query());
    $categories = \App\Models\Category::orderBy('name')->get();
    $totalAll   = \App\Models\Producto::count();
@endphp

<div class="page-content container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel panel-bordered" style="padding: 22px 24px 14px;">

    {{-- ── Filtros de categoría ───────────────────────────────── --}}
    <div class="cat-filter-bar">
        <a href="{{ request()->fullUrlWithQuery(['category_id' => 'all', 'page' => 1]) }}"
           class="cat-btn {{ $filterCat === 'all' ? 'active' : '' }}">
            Todos ({{ $totalAll }})
        </a>
        @foreach($categories as $cat)
        @php $cnt = \App\Models\Producto::where('category_id', $cat->id)->count(); @endphp
        <a href="{{ request()->fullUrlWithQuery(['category_id' => $cat->id, 'page' => 1]) }}"
           class="cat-btn {{ (string)$filterCat === (string)$cat->id ? 'active' : '' }}">
            {{ $cat->name }}
            <span style="opacity:.65;font-weight:400;">({{ $cnt }})</span>
        </a>
        @endforeach
    </div>

    {{-- ── Barra de herramientas: búsqueda + filtros ───────────────────────────
         Todos los filtros operan en el cliente (JS) sobre las filas ya cargadas.
         No hace peticiones al servidor — respuesta inmediata sin recarga.
    ──────────────────────────────────────────────────────────────────────── --}}
    <div class="toolbar-row">

        {{-- Búsqueda por nombre / descripción --}}
        <div class="toolbar-search">
            <input type="text"
                   id="prod-search"
                   placeholder="Buscar por nombre…"
                   title="Filtra las filas por nombre o descripción">
        </div>

        <div class="toolbar-sep"></div>

        {{-- Filtro de rango de precio --}}
        <div class="price-range">
            <span>Bs</span>
            <input type="number" id="price-min" placeholder="Mín" min="0" title="Precio mínimo">
            <span>—</span>
            <input type="number" id="price-max" placeholder="Máx" min="0" title="Precio máximo">
        </div>

        <div class="toolbar-sep"></div>

        {{-- Filtro de estado de stock --}}
        <select id="stock-filter" class="toolbar-select" title="Filtrar por nivel de stock">
            <option value="all">Todo el stock</option>
            <option value="in">Con stock</option>
            <option value="low">Stock bajo (≤5)</option>
            <option value="out">Sin stock</option>
        </select>

        {{-- Ordenamiento --}}
        <select id="sort-select" class="toolbar-select" title="Ordenar la tabla">
            <option value="">Ordenar por…</option>
            <option value="name-asc">Nombre A → Z</option>
            <option value="name-desc">Nombre Z → A</option>
            <option value="price-asc">Precio ↑ menor a mayor</option>
            <option value="price-desc">Precio ↓ mayor a menor</option>
            <option value="stock-asc">Stock ↑ menor a mayor</option>
            <option value="stock-desc">Stock ↓ mayor a menor</option>
        </select>

        {{-- Botón para limpiar todos los filtros --}}
        <button id="btn-clear-filters" class="btn-clear-filters" title="Restablecer todos los filtros">
            ✕ Limpiar filtros
        </button>

        {{-- Contador dinámico de filas visibles --}}
        <span class="results-count" id="results-count">
            {{ $productos->total() }} productos
        </span>

    </div>

    {{-- ── Tabla ──────────────────────────────────────────────── --}}
    <div class="prod-table-wrap">
        <table id="productos-table">
            <colgroup>
                <col class="col-img">
                <col class="col-nombre">
                <col class="col-precio">
                <col class="col-stock">
                <col class="col-cat">
                <col class="col-prov">
                <col class="col-acc">
            </colgroup>
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre del Producto</th>
                    <th>Precio</th>
                    <th style="text-align:center;">Stock</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $prod)
                @php
                    /* Clase del badge de stock según cantidad */
                    [$stockClass, $stockLabel] = match(true) {
                        ($prod->cantidad ?? 0) <= 0 => ['stock-out', 'Sin stock'],
                        ($prod->cantidad ?? 0) <= 5 => ['stock-low', ($prod->cantidad) . ' (bajo)'],
                        default                     => ['stock-ok',  $prod->cantidad],
                    };

                    /* Descripción: quitar HTML y truncar */
                    $descLimpia = $prod->descripcion
                        ? \Illuminate\Support\Str::limit(strip_tags($prod->descripcion), 70)
                        : null;
                @endphp
                <tr class="prod-row">

                    {{-- Miniatura --}}
                    <td>
                        @if($prod->cover_img)
                            <img src="{{ Voyager::image($prod->cover_img) }}"
                                 class="prod-thumb"
                                 alt="{{ $prod->nombre }}">
                        @else
                            <img src="{{ asset('images/default.png') }}"
                                 class="prod-thumb" alt="Sin imagen">
                        @endif
                    </td>

                    {{-- Nombre + descripción limpia + ID --}}
                    <td>
                        <span class="prod-nombre">
                            {{ \Illuminate\Support\Str::limit($prod->nombre, 55) }}
                        </span>
                        @if($descLimpia)
                            <span class="prod-desc">{{ $descLimpia }}</span>
                        @endif
                        <span class="prod-id"># {{ $prod->id }}</span>
                    </td>

                    {{-- Precio --}}
                    <td>
                        <span class="prod-precio">Bs {{ number_format($prod->precio, 2) }}</span>
                    </td>

                    {{-- Stock — input editable inline ──────────────────────
                         Al salir del campo (blur) o presionar Enter se guarda
                         via AJAX. El badge de color se recalcula en JS según
                         el nuevo valor sin recargar la página.
                    --}}
                    <td style="text-align:center;">
                        <div class="stock-input-wrap">
                            <input type="number"
                                   class="stock-input"
                                   value="{{ $prod->cantidad ?? 0 }}"
                                   min="0"
                                   data-id="{{ $prod->id }}"
                                   data-original="{{ $prod->cantidad ?? 0 }}"
                                   title="Editar stock — presiona Enter o haz clic afuera para guardar">
                            <span class="stock-saving"></span>
                        </div>
                    </td>

                    {{-- Categoría --}}
                    <td>
                        @if($prod->category)
                            <span class="cat-badge">{{ $prod->category->name }}</span>
                        @else
                            <span style="color:#ddd;">—</span>
                        @endif
                    </td>

                    {{-- Proveedor — muestra — si no tiene asignado --}}
                    <td>
                        @if($prod->proveedor)
                            <span class="prov-badge">{{ $prod->proveedor->nombre }}</span>
                        @else
                            <span style="color:#ddd;">—</span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('voyager.productos.show', $prod->id) }}"
                           class="btn btn-sm btn-info" title="Ver detalle">
                            <i class="voyager-eye"></i>
                        </a>
                        @can('edit', $prod)
                        <a href="{{ route('voyager.productos.edit', $prod->id) }}"
                           class="btn btn-sm btn-warning" title="Editar">
                            <i class="voyager-edit"></i>
                        </a>
                        @endcan
                        @can('delete', $prod)
                        <a href="javascript:;"
                           class="btn btn-sm btn-danger btn-delete"
                           data-id="{{ $prod->id }}"
                           data-name="{{ $prod->nombre }}"
                           title="Eliminar">
                            <i class="voyager-trash"></i>
                        </a>
                        @endcan
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center"
                        style="padding: 50px; color: #ccc; font-size: 14px;">
                        <i class="voyager-box" style="font-size:32px; display:block; margin-bottom:10px;"></i>
                        No hay productos en esta categoría.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div style="margin-top: 16px; text-align: center;">
        {{ $productos->links() }}
    </div>

</div>
</div>
</div>
</div>

{{-- Modal confirmación eliminar --}}
<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="voyager-trash"></i>
                    ¿Eliminar "<span id="del-name"></span>"?
                </h4>
            </div>
            <div class="modal-footer">
                <form id="delete_form" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">Cancelar</button>
                    <button type="submit"
                            class="btn btn-danger pull-right">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
/* ── Edición rápida de stock inline ────────────────────────────────
 * Al hacer blur o Enter en un .stock-input se envía el nuevo valor
 * al controlador vía fetch(). Si el valor no cambió, no hace nada.
 * Feedback visual: spinner mientras guarda, borde verde/rojo según resultado.
 * ─────────────────────────────────────────────────────────────────── */
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function saveStock(input) {
        var id       = input.dataset.id;
        var original = parseInt(input.dataset.original, 10);
        var newVal   = parseInt(input.value, 10);

        // No guardar si el valor no cambió o es inválido
        if (isNaN(newVal) || newVal < 0) { input.value = original; return; }
        if (newVal === original) return;

        var spinner = input.nextElementSibling;
        spinner.style.display = 'inline-block';
        input.disabled = true;

        fetch('{{ route("admin.productos.stock", ["id" => "__ID__"]) }}'.replace('__ID__', id), {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  csrfToken,
                'Accept':        'application/json',
            },
            body: JSON.stringify({ cantidad: newVal }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            spinner.style.display = 'none';
            input.disabled = false;

            if (data.success) {
                // Actualizar valor de referencia para evitar re-guardados
                input.dataset.original = newVal;
                input.classList.add('saved');
                // Mostrar toastr de éxito si Voyager lo tiene cargado
                if (typeof toastr !== 'undefined') toastr.success(data.message);
                setTimeout(function () { input.classList.remove('saved'); }, 1500);
            } else {
                input.value = original;
                input.classList.add('error');
                if (typeof toastr !== 'undefined') toastr.error('Error al guardar el stock.');
                setTimeout(function () { input.classList.remove('error'); }, 1500);
            }
        })
        .catch(function () {
            spinner.style.display = 'none';
            input.disabled = false;
            input.value = original;
            input.classList.add('error');
            setTimeout(function () { input.classList.remove('error'); }, 1500);
        });
    }

    document.querySelectorAll('.stock-input').forEach(function (input) {
        // Guardar al perder el foco
        input.addEventListener('blur', function () { saveStock(this); });
        // Guardar al presionar Enter; Escape restaura el valor original
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter')  { e.preventDefault(); this.blur(); }
            if (e.key === 'Escape') { this.value = this.dataset.original; this.blur(); }
        });
    });
})();

/* ── Sistema de filtros cliente-side ──────────────────────────────────
 *
 * Cómo funciona:
 *  1. Cada <tr class="prod-row"> tiene data-* attributes con sus valores
 *     numéricos (precio, stock) y texto (nombre) para filtrar sin parsear DOM.
 *  2. applyFilters() recorre todas las filas, evalúa los 4 criterios
 *     (texto, precio mín/máx, stock) y muestra/oculta según el resultado.
 *  3. applySorting() reordena las filas visibles dentro del <tbody> según
 *     el criterio elegido, usando Array.sort() + DOM insertBefore.
 *  4. Todos los controles llaman a applyFilters() en su evento de cambio.
 *
 * ──────────────────────────────────────────────────────────────────── */
(function () {

    // Referencias a los controles
    var searchInput  = document.getElementById('prod-search');
    var priceMin     = document.getElementById('price-min');
    var priceMax     = document.getElementById('price-max');
    var stockFilter  = document.getElementById('stock-filter');
    var sortSelect   = document.getElementById('sort-select');
    var clearBtn     = document.getElementById('btn-clear-filters');
    var counter      = document.getElementById('results-count');
    var tbody        = document.querySelector('#productos-table tbody');
    var allRows      = Array.from(document.querySelectorAll('#productos-table .prod-row'));

    // ── Leer precio y stock de cada fila una sola vez ────────────────
    // Se guarda en el propio DOM como data-attributes para no parsear texto cada vez
    allRows.forEach(function (row) {
        var precioText = row.querySelector('.prod-precio').textContent;
        var stockVal   = row.querySelector('.stock-input').value;
        // Precio: eliminar "Bs" y espacios, convertir a número
        row.dataset.precio = parseFloat(precioText.replace(/[^0-9.]/g, '')) || 0;
        row.dataset.stock  = parseInt(stockVal, 10) || 0;
        // Nombre: texto de la primera celda de nombre (para comparación)
        row.dataset.nombre = (row.querySelector('.prod-nombre') || {}).textContent || '';
    });

    // ── Función principal: aplicar todos los filtros ─────────────────
    function applyFilters() {
        var term     = searchInput.value.toLowerCase().trim();
        var minPrice = parseFloat(priceMin.value) || 0;
        var maxPrice = parseFloat(priceMax.value) || Infinity;
        var stockOpt = stockFilter.value;  // 'all' | 'in' | 'low' | 'out'
        var visible  = 0;

        allRows.forEach(function (row) {
            var precio = parseFloat(row.dataset.precio);
            var stock  = parseInt(row.dataset.stock, 10);
            var nombre = row.dataset.nombre.toLowerCase();

            // ── Criterio 1: coincidencia de texto (nombre o contenido) ──
            var passText = !term || row.textContent.toLowerCase().indexOf(term) > -1;

            // ── Criterio 2: rango de precio ──────────────────────────────
            var passPrice = precio >= minPrice && precio <= maxPrice;

            // ── Criterio 3: estado de stock ──────────────────────────────
            var passStock = true;
            if (stockOpt === 'in')  passStock = stock > 5;
            if (stockOpt === 'low') passStock = stock > 0 && stock <= 5;
            if (stockOpt === 'out') passStock = stock <= 0;

            // Mostrar fila solo si pasa los tres criterios
            var show = passText && passPrice && passStock;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Actualizar contador
        counter.textContent = visible + ' producto' + (visible !== 1 ? 's' : '');

        // Mostrar botón "Limpiar filtros" si hay algún filtro activo
        var hasFilter = term || priceMin.value || priceMax.value || stockOpt !== 'all';
        clearBtn.style.display = hasFilter ? 'inline' : 'none';

        // Aplicar ordenamiento sobre las filas ya filtradas
        applySorting();
    }

    // ── Ordenar filas visibles dentro del tbody ──────────────────────
    function applySorting() {
        var criteria = sortSelect.value;
        if (!criteria) return;  // Sin criterio seleccionado, no cambia orden

        // Separar en visibles e invisibles (las ocultas no se reordenan)
        var visible = allRows.filter(function (r) { return r.style.display !== 'none'; });
        var hidden  = allRows.filter(function (r) { return r.style.display === 'none'; });

        visible.sort(function (a, b) {
            switch (criteria) {
                case 'name-asc':    return a.dataset.nombre.localeCompare(b.dataset.nombre);
                case 'name-desc':   return b.dataset.nombre.localeCompare(a.dataset.nombre);
                case 'price-asc':   return parseFloat(a.dataset.precio) - parseFloat(b.dataset.precio);
                case 'price-desc':  return parseFloat(b.dataset.precio) - parseFloat(a.dataset.precio);
                case 'stock-asc':   return parseInt(a.dataset.stock, 10) - parseInt(b.dataset.stock, 10);
                case 'stock-desc':  return parseInt(b.dataset.stock, 10) - parseInt(a.dataset.stock, 10);
                default: return 0;
            }
        });

        // Reinsertar filas en el nuevo orden: primero visibles, luego ocultas
        visible.concat(hidden).forEach(function (row) { tbody.appendChild(row); });
    }

    // ── Limpiar todos los filtros y restaurar vista ───────────────────
    clearBtn.addEventListener('click', function () {
        searchInput.value  = '';
        priceMin.value     = '';
        priceMax.value     = '';
        stockFilter.value  = 'all';
        sortSelect.value   = '';
        applyFilters();
    });

    // ── Eventos: cada control llama a applyFilters() ──────────────────
    // Búsqueda: al escribir (keyup para respuesta en tiempo real)
    searchInput.addEventListener('keyup',  applyFilters);

    // Precio: al cambiar el valor (input + change cubre teclado y flecha)
    priceMin.addEventListener('input',    applyFilters);
    priceMax.addEventListener('input',    applyFilters);

    // Stock y orden: al cambiar la opción del select
    stockFilter.addEventListener('change', applyFilters);
    sortSelect.addEventListener('change',  applyFilters);

})();

/* ── Modal de eliminación ─────────────────────────────────────────── */
document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('del-name').textContent = this.dataset.name;
        document.getElementById('delete_form').action  =
            '{{ url("admin/productos") }}/' + this.dataset.id;
        $('#delete_modal').modal('show');
    });
});
</script>
@stop
