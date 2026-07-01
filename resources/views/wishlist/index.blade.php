{{--
    Vista: wishlist/index.blade.php
    Layout: layouts.toolbar
    Componente Livewire: App\Http\Livewire\Wishlist

    ESTILOS (buenas prácticas):
    ─────────────────────────────────────────────────────────────────
    Todos los CSS específicos de esta página se inyectan en el <head>
    del layout mediante @push('styles') → @stack('styles').
    Esto evita mezclar estilos con el HTML del componente Livewire
    y garantiza que las reglas estén disponibles antes de que el
    navegador renderice el contenido (sin FOUC).

    Los estilos del template base (tablas, colores de marca, etc.)
    viven en public/style.css. Los estilos que están aquí solo
    complementan o sobrescriben lo necesario para esta página.
--}}
@extends('layouts.toolbar')

{{--
    @push('styles') acumula bloques de estilos definidos en las vistas
    hijas y los coloca donde el layout tenga @stack('styles'), dentro
    del <head>. A diferencia de @section/@yield, varios @push al mismo
    stack se concatenan sin pisarse.
--}}
@push('styles')
<style>
    /*
     * ══════════════════════════════════════════════════════════════
     *  WISHLIST PAGE — estilos específicos de la página
     *  Alcance: .wishlist-area y descendientes
     *  Colores de marca: amarillo #fed700, oscuro #2c2c2c
     * ══════════════════════════════════════════════════════════════
     */

    /* Espaciado vertical de la sección principal */
    .wishlist-area {
        padding: 50px 0 60px;
    }

    /*
     * Contenedor de la tabla:
     * fondo blanco + esquinas redondeadas + sombra sutil para dar
     * sensación de "tarjeta" elevada sobre el fondo de la página.
     * overflow:hidden recorta las esquinas del thead.
     */
    .wishlist-table-wrapper {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    /*
     * Encabezado de la tabla:
     * fondo oscuro (#2c2c2c) con texto blanco, mayúsculas y
     * letter-spacing para darle jerarquía visual al header.
     * white-space:nowrap evita que los títulos rompan línea.
     */
    .wishlist-table thead tr {
        background: #2c2c2c;
    }
    .wishlist-table thead th {
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 14px 16px;
        border: none;          /* elimina el borde por defecto de Bootstrap */
        white-space: nowrap;
    }

    /*
     * Filas del cuerpo:
     * separador inferior sutil entre filas; la última fila no lo lleva
     * para evitar doble borde con el contenedor.
     * Hover con fondo amarillo muy tenue (#fffdf0) coherente con la
     * paleta de la tienda.
     */
    .wishlist-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }
    .wishlist-table tbody tr:last-child {
        border-bottom: none;
    }
    .wishlist-table tbody tr:hover {
        background: #fffdf0;
    }

    /* Celdas del cuerpo: alineación vertical centrada y sin bordes */
    .wishlist-table tbody td {
        vertical-align: middle;
        padding: 14px 16px;
        border: none;
        color: #444;
        font-size: 14px;
    }

    /*
     * Columna ELIMINAR:
     * Botón circular (30×30 px) con borde gris neutro.
     * Al hacer hover cambia a rojo destructivo (#e74c3c) para
     * indicar visualmente que la acción borra el ítem.
     */
    .wishlist-table td.li-product-remove {
        text-align: center;
        width: 60px;
    }
    .wishlist-table td.li-product-remove a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #ddd;
        color: #999;
        transition: all 0.25s ease;
    }
    .wishlist-table td.li-product-remove a:hover {
        background: #e74c3c;
        border-color: #e74c3c;
        color: #fff;
    }

    /*
     * Columna IMAGEN:
     * Ancho fijo de 90 px; esquinas redondeadas y borde gris claro
     * para enmarcar la imagen del producto.
     * object-fit:cover asegura que la imagen no se deforme.
     */
    .wishlist-table td.li-product-thumbnail {
        width: 90px;
        text-align: center;
    }
    .wishlist-table td.li-product-thumbnail img {
        border-radius: 6px;
        border: 1px solid #eee;
        object-fit: cover;
    }

    /*
     * Columna NOMBRE DEL PRODUCTO:
     * Enlace en oscuro (#222) con hover al amarillo de marca (#fed700).
     * Sin subrayado por defecto para un look más limpio.
     */
    .wishlist-table td.li-product-name a {
        color: #222;
        font-weight: 500;
        font-size: 15px;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .wishlist-table td.li-product-name a:hover {
        color: #fed700;
    }

    /* Columna PRECIO: negrita y oscuro para resaltar el valor */
    .wishlist-table td.li-product-price .amount {
        font-weight: 700;
        font-size: 15px;
        color: #2c2c2c;
    }

    /*
     * Columna ESTADO DE STOCK:
     * Badges tipo "pill" con colores semánticos:
     *   - Verde (#27ae60) → producto disponible
     *   - Rojo (#e74c3c)  → sin stock
     * El borde y el fondo tenue refuerzan el color sin ser agresivos.
     */
    .wishlist-table td.li-product-stock-status {
        text-align: center;
    }
    .wishlist-table .badge-stock {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;    /* pill shape */
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.04em;
    }
    .wishlist-table .badge-in-stock {
        background: #e6f9ee;
        color: #27ae60;
        border: 1px solid #b2dfca;
    }
    .wishlist-table .badge-out-stock {
        background: #fdecea;
        color: #e74c3c;
        border: 1px solid #f5c6c3;
    }

    /*
     * Columna AÑADIR AL CARRITO:
     * Botón oscuro (#2c2c2c) que al hover invierte a amarillo (#fed700)
     * consistente con el CTA principal de la tienda.
     * white-space:nowrap previene que el texto del botón parta en 2 líneas.
     */
    .wishlist-table td.li-product-add-cart {
        text-align: center;
    }
    .wishlist-table td.li-product-add-cart a {
        display: inline-block;
        padding: 8px 18px;
        background: #2c2c2c;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.25s ease, color 0.25s ease;
        white-space: nowrap;
    }
    .wishlist-table td.li-product-add-cart a:hover {
        background: #fed700;
        color: #222;
    }

    /*
     * ESTADO VACÍO:
     * Se muestra cuando el usuario no tiene ítems en su wishlist.
     * Icono grande centrado + mensaje para guiar al usuario.
     */
    .wishlist-empty {
        text-align: center;
        padding: 48px 20px;
        color: #999;
        font-size: 15px;
    }
    .wishlist-empty i {
        display: block;
        font-size: 48px;
        margin-bottom: 12px;
        color: #ddd;
    }

    /*
     * RESPONSIVE — pantallas pequeñas (< 576 px):
     * La clase .hide-xs oculta la columna de stock para que la tabla
     * no se desborde en móvil. El botón de carrito reduce su padding
     * y fuente para caber en el espacio disponible.
     */
    @media (max-width: 575px) {
        .wishlist-table thead th.hide-xs,
        .wishlist-table tbody td.hide-xs {
            display: none;
        }
        .wishlist-table td.li-product-add-cart a {
            padding: 7px 10px;
            font-size: 11px;
        }
    }
</style>
@endpush

@section('content')
    {{-- Componente Livewire que gestiona la lógica de la wishlist:
         eliminar ítem (deleteItem) y añadir al carrito (agregarCarrito) --}}
    @livewire('wishlist')
@endsection
