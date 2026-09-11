{{--
    Navbar personalizado del panel admin NextLevelGamer.
    Incluye:
      - Barra de búsqueda global con autocompletado (productos, pedidos, categorías)
      - Resultados flotantes con dropdown en tiempo real (mínimo 2 caracteres)
      - Perfil de usuario con dropdown estándar de Voyager
--}}
<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">

        {{-- ── Lado izquierdo: hamburger + breadcrumbs ──────────────── --}}
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>

            @section('breadcrumbs')
            <ol class="breadcrumb hidden-xs">
                @php
                    $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                    $url = route('voyager.dashboard');
                @endphp
                @if(count($segments) == 0)
                    <li class="active"><i class="voyager-boat"></i> Dashboard</li>
                @else
                    <li class="active">
                        <a href="{{ route('voyager.dashboard') }}"><i class="voyager-boat"></i> Dashboard</a>
                    </li>
                    @foreach ($segments as $segment)
                        @php $url .= '/' . $segment; @endphp
                        @if ($loop->last)
                            <li>{{ ucfirst(urldecode($segment)) }}</li>
                        @else
                            <li><a href="{{ $url }}">{{ ucfirst(urldecode($segment)) }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ol>
            @show
        </div>

        {{-- ── Centro: buscador global ──────────────────────────────── --}}
        <div class="navbar-form navbar-left admin-search-wrapper" style="margin-left:20px;">
            <div class="form-group" style="position:relative;">

                {{-- Icono de lupa --}}
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#aaa;pointer-events:none;">
                    <i class="voyager-search"></i>
                </span>

                {{-- Input de búsqueda --}}
                <input
                    id="admin-search-input"
                    type="text"
                    class="form-control"
                    placeholder="Buscar productos, pedidos..."
                    autocomplete="off"
                    style="padding-left:32px;width:280px;border-radius:20px;"
                >

                {{-- Spinner (visible mientras busca) --}}
                <span
                    id="admin-search-spinner"
                    style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);"
                >
                    <i class="voyager-refresh" style="color:#aaa;animation:spin 1s linear infinite;"></i>
                </span>

                {{-- Dropdown de resultados (position:fixed para escapar del overflow del navbar) --}}
                <div
                    id="admin-search-results"
                    style="display:none;position:fixed;top:50px;width:360px;
                           background:#fff;border:1px solid #ddd;border-radius:6px;
                           box-shadow:0 4px 16px rgba(0,0,0,.15);z-index:99999;max-height:420px;overflow-y:auto;"
                >
                </div>
            </div>
        </div>

        {{-- ── Derecho: perfil de usuario ───────────────────────────── --}}
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button" aria-expanded="false">
                    <img src="{{ $user_avatar }}" class="profile-img">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                        @foreach($nav_items as $name => $item)
                        <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                            @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                                <form action="{{ route('voyager.logout') }}" method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger btn-block">
                                        @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                            <i class="{!! $item['icon_class'] !!}"></i>
                                        @endif
                                        {{ __($name) }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}"
                                   {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                                    @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                        <i class="{!! $item['icon_class'] !!}"></i>
                                    @endif
                                    {{ __($name) }}
                                </a>
                            @endif
                        </li>
                        @endforeach
                    @endif
                </ul>
            </li>
        </ul>

    </div><!-- /.container-fluid -->
</nav>

{{-- ── Estilos del dropdown de resultados ───────────────────────────── --}}
<style>
    /* Animación de giro para el spinner */
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Grupos de resultados */
    .search-group-title {
        padding: 6px 12px 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #888;
        background: #f8f8f8;
        border-bottom: 1px solid #eee;
        letter-spacing: .5px;
    }

    /* Cada fila de resultado */
    .search-result-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #f2f2f2;
        transition: background .15s;
    }
    .search-result-item:last-child  { border-bottom: none; }
    .search-result-item:hover       { background: #f0f7ff; color: #1a73e8; text-decoration: none; }

    /* Miniatura de imagen en resultados de productos */
    .search-result-img {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
        background: #eee;
    }

    /* Texto secundario (precio, estado) */
    .search-result-sub {
        font-size: 12px;
        color: #999;
        display: block;
    }

    /* Mensaje sin resultados */
    .search-no-results {
        padding: 16px;
        text-align: center;
        color: #999;
        font-size: 13px;
    }

    /* Badge de categoría dentro del resultado */
    .search-badge {
        margin-left: auto;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 10px;
        background: #e8f0fe;
        color: #1a73e8;
        flex-shrink: 0;
    }
</style>

{{-- ── Lógica JS de búsqueda global ─────────────────────────────────── --}}
<script>
(function () {
    'use strict';

    // Elementos del DOM
    var input    = document.getElementById('admin-search-input');
    var results  = document.getElementById('admin-search-results');
    var spinner  = document.getElementById('admin-search-spinner');

    // Token CSRF para fetch POST
    var csrfToken = document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').content
                    : '';

    var debounceTimer = null;   // Temporizador para debounce
    var currentQuery  = '';     // Última búsqueda ejecutada

    // ── Abrir/cerrar dropdown ────────────────────────────────────────
    function showResults() {
        // Posicionar dinámicamente bajo el input (compensar scroll y posición real)
        var rect = input.getBoundingClientRect();
        results.style.left = rect.left + 'px';
        results.style.top  = (rect.bottom + 4) + 'px';
        results.style.display = 'block';
    }
    function hideResults() { results.style.display = 'none'; }

    // ── Renderizar resultados en el dropdown ────────────────────────
    function renderResults(data, query) {
        results.innerHTML = '';

        var totalItems = 0;

        // ── Grupo: Productos ────────────────────────────────────────
        if (data.productos && data.productos.length > 0) {
            totalItems += data.productos.length;

            var groupTitle = document.createElement('div');
            groupTitle.className = 'search-group-title';
            groupTitle.innerHTML = '<i class="voyager-bag"></i> Productos (' + data.productos.length + ')';
            results.appendChild(groupTitle);

            data.productos.forEach(function (p) {
                var imgSrc = p.cover_img
                    ? (p.cover_img.startsWith('http') ? p.cover_img : '/storage/' + p.cover_img)
                    : '/vendor/voyager/images/default.png';

                var a = document.createElement('a');
                a.className = 'search-result-item';
                a.href = '/admin/productos/' + p.id + '/edit';
                a.innerHTML =
                    '<img class="search-result-img" src="' + imgSrc + '" onerror="this.src=\'/vendor/voyager/images/default.png\'">' +
                    '<div style="flex:1;min-width:0;">' +
                        '<div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
                            + highlight(p.nombre, query) +
                        '</div>' +
                        '<span class="search-result-sub">Bs ' + parseFloat(p.precio).toFixed(2) + ' &bull; Stock: ' + p.cantidad + '</span>' +
                    '</div>' +
                    (p.categoria ? '<span class="search-badge">' + p.categoria + '</span>' : '');
                results.appendChild(a);
            });
        }

        // ── Grupo: Pedidos ──────────────────────────────────────────
        if (data.pedidos && data.pedidos.length > 0) {
            totalItems += data.pedidos.length;

            var groupTitle2 = document.createElement('div');
            groupTitle2.className = 'search-group-title';
            groupTitle2.innerHTML = '<i class="voyager-list"></i> Pedidos (' + data.pedidos.length + ')';
            results.appendChild(groupTitle2);

            // Mapa de colores por estado
            var estadoColor = {
                pending:    '#f0ad4e',
                processing: '#5bc0de',
                completed:  '#5cb85c',
                declined:   '#d9534f'
            };

            data.pedidos.forEach(function (o) {
                var color = estadoColor[o.status] || '#aaa';
                var a = document.createElement('a');
                a.className = 'search-result-item';
                a.href = '/admin/orders/' + o.id + '/read';
                a.innerHTML =
                    '<div style="width:36px;height:36px;border-radius:50%;background:' + color + ';display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0;">'
                        + '#' + o.id +
                    '</div>' +
                    '<div style="flex:1;min-width:0;">' +
                        '<div style="font-size:13px;font-weight:600;">' + (o.cliente || 'Cliente #' + o.id) + '</div>' +
                        '<span class="search-result-sub">Bs ' + parseFloat(o.total || 0).toFixed(2) + ' &bull; ' + formatDate(o.created_at) + '</span>' +
                    '</div>' +
                    '<span class="search-badge" style="background:' + color + '20;color:' + color + ';">' + o.status + '</span>';
                results.appendChild(a);
            });
        }

        // ── Sin resultados ──────────────────────────────────────────
        if (totalItems === 0) {
            var noRes = document.createElement('div');
            noRes.className = 'search-no-results';
            noRes.innerHTML = '<i class="voyager-search" style="font-size:24px;display:block;margin-bottom:8px;"></i>Sin resultados para <strong>"' + escapeHtml(query) + '"</strong>';
            results.appendChild(noRes);
        }

        showResults();
    }

    // ── Resaltar coincidencia en el texto ───────────────────────────
    function highlight(text, query) {
        if (!query) return escapeHtml(text);
        var safeText  = escapeHtml(text);
        var safeQuery = escapeHtml(query).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return safeText.replace(new RegExp('(' + safeQuery + ')', 'gi'), '<mark style="background:#fff3cd;padding:0;">$1</mark>');
    }

    // ── Formatear fecha ISO a "dd/mm/yyyy" ──────────────────────────
    function formatDate(iso) {
        if (!iso) return '';
        var d = new Date(iso);
        return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
    }

    // ── Escapar HTML para evitar XSS ───────────────────────────────
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Ejecutar búsqueda al servidor ───────────────────────────────
    function doSearch(query) {
        currentQuery = query;
        spinner.style.display = 'inline';

        fetch('/admin/search?q=' + encodeURIComponent(query), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            spinner.style.display = 'none';
            // Ignorar si el usuario ya escribió algo diferente
            if (input.value.trim() === currentQuery) {
                renderResults(data, query);
            }
        })
        .catch(function () {
            spinner.style.display = 'none';
        });
    }

    // ── Eventos del input ────────────────────────────────────────────
    input.addEventListener('input', function () {
        var q = input.value.trim();
        clearTimeout(debounceTimer);

        if (q.length < 2) {
            hideResults();
            results.innerHTML = '';
            return;
        }

        // Debounce: esperar 300ms antes de buscar
        debounceTimer = setTimeout(function () { doSearch(q); }, 300);
    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            hideResults();
        }
    });

    // Reabrir si el input ya tiene texto y se hace foco
    input.addEventListener('focus', function () {
        if (input.value.trim().length >= 2 && results.innerHTML !== '') {
            showResults();
        }
    });

    // Navegar con teclas arriba/abajo + Enter
    input.addEventListener('keydown', function (e) {
        var items = results.querySelectorAll('.search-result-item');
        var active = results.querySelector('.search-result-item.keyboard-active');
        var idx = Array.from(items).indexOf(active);

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (active) active.classList.remove('keyboard-active');
            var next = items[idx + 1] || items[0];
            if (next) { next.classList.add('keyboard-active'); next.style.background = '#f0f7ff'; }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active) active.classList.remove('keyboard-active');
            var prev = items[idx - 1] || items[items.length - 1];
            if (prev) { prev.classList.add('keyboard-active'); prev.style.background = '#f0f7ff'; }
        } else if (e.key === 'Enter' && active) {
            e.preventDefault();
            window.location.href = active.href;
        } else if (e.key === 'Escape') {
            hideResults();
            input.blur();
        }
    });

})();
</script>
