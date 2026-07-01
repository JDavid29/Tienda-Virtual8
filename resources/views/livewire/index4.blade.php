<div>

    {{-- ══════════════════════════════════════════════════════════════
         SECCIÓN: SLIDER CON BANNERS LATERALES
         Componente Livewire: Inicio
         CSS de mejoras: public/css/pages/slider.css
         Estilos base:    public/style.css (sección 2.2 Slider)

         MEJORAS IMPLEMENTADAS (ver slider.css para el detalle CSS):
           1. Animaciones de entrada: se alterna animation-style-01 y
              animation-style-02 en cada slide para activar los
              keyframes zoomInRight / zoomInUp ya definidos en style.css.
           2. Badge "NUEVO" sobre la imagen del producto (.slider-product-badge).
           3. Descripción corta del producto entre el precio y el CTA.
           4. Dots de navegación Owl Carousel visibles (override CSS).
           5. Banners laterales como mini-cards con nombre y precio.
           6. Estado vacío mejorado con fondo de marca y CTA destacado.
         ══════════════════════════════════════════════════════════════ --}}
    <div class="slider-with-banner">
        <div class="container">
            <div class="row">

                {{-- ── Columna principal del slider (8/12) ─────────── --}}
                <div class="col-lg-8 col-md-12">
                    <div class="slider-area pt-sm-30 pt-xs-30">
                        <div class="slider-active owl-carousel">

                            @forelse($sliderProducts->take(3) as $index => $prod)
                                {{--
                                    MEJORA 1 — Animaciones de entrada
                                    Se alterna animation-style-01 (zoomInRight) y
                                    animation-style-02 (zoomInUp) en slides pares/impares
                                    para dar variedad visual sin repetición.
                                --}}
                                <div class="single-slide align-center-left {{ $index % 2 === 0 ? 'animation-style-01' : 'animation-style-02' }}">

                                    {{-- Barra de progreso animada (ya existía en style.css) --}}
                                    <div class="slider-progress"></div>

                                    {{-- Contenido textual del slide --}}
                                    <div class="slider-content">
                                        <h5>Nueva Llegada <span>— Esta semana</span></h5>
                                        <h2>{{ Str::limit($prod->nombre, 50) }}</h2>
                                        <h3>Desde <span>Bs{{ number_format($prod->precio, 2) }}</span></h3>

                                        {{--
                                            MEJORA 3 — Descripción corta del producto
                                            Ocupa el espacio vacío de 64px que dejaba el margen
                                            inferior de h3. Se limita a 90 chars para no desbordar.
                                            Solo se muestra si el producto tiene descripción.
                                        --}}
                                        @if(!empty($prod->descripcion))
                                            <p class="slider-desc">{{ Str::limit($prod->descripcion, 90) }}</p>
                                        @endif

                                        <div class="default-btn slide-btn">
                                            <a class="links" href="{{ route('single-product', $prod->id) }}">
                                                Ver producto
                                            </a>
                                        </div>
                                    </div>

                                    {{--
                                        MEJORA 2 — Badge "NUEVO" sobre la imagen
                                        Va dentro de .single-slide (position:relative vía slider.css),
                                        NO dentro de .slider-thumb para no romper el layout del template.
                                        Se posiciona en la esquina superior derecha del área de imagen.
                                    --}}
                                    <span class="slider-product-badge">Nuevo</span>
                                    <div class="slider-thumb">
                                        @include('partials.product-image', [
                                            'image'          => $prod->cover_img ?? null,
                                            'alt'            => $prod->nombre,
                                            'default'        => 'images/default.png',
                                            'attributesHtml' => 'style="max-height:260px;object-fit:contain;"',
                                        ])
                                    </div>

                                </div>
                            @empty
                                {{--
                                    MEJORA 6 — Estado vacío mejorado
                                    Reemplaza el slide de texto plano anterior.
                                    Fondo oscuro heredado del .single-slide + clase
                                    .slider-empty-state para centrar y estilizar el contenido.
                                --}}
                                <div class="single-slide align-center-left">
                                    <div class="slider-empty-state">
                                        <span class="empty-badge">Tienda Virtual</span>
                                        <h2>Los mejores productos, al mejor precio</h2>
                                        <p>Explora el catálogo y encuentra lo que necesitas.</p>
                                        <a class="empty-cta" href="{{ route('list.product') }}">
                                            Ver catálogo
                                        </a>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                        {{--
                            MEJORA 4 — Dots de navegación
                            Owl Carousel genera los dots automáticamente.
                            El CSS en slider.css sobreescribe el display:none
                            que tenía .slider-active .owl-dots en style.css.
                        --}}
                    </div>
                </div>

                {{-- ── Columna de banners laterales (4/12) ─────────── --}}
                <div class="col-lg-4 col-md-12 text-center pt-sm-30 pt-xs-30">

                    {{--
                        MEJORA 5 — Banners laterales como mini-cards
                        Antes: solo imagen sin contexto.
                        Ahora: imagen con overlay inferior que muestra
                        nombre (truncado) y precio del producto.
                        Ver clases .li-banner-card y .banner-info en slider.css.
                    --}}
                    @foreach($sliderProducts->skip(3)->take(2) as $bp)
                        <div class="li-banner mb-15">
                            <a class="li-banner-card" href="{{ route('single-product', $bp->id) }}">
                                @include('partials.product-image', [
                                    'image'          => $bp->cover_img ?? null,
                                    'alt'            => $bp->nombre,
                                    'default'        => 'images/banner/1_1.jpg',
                                    'attributesHtml' => '',
                                ])
                                <div class="banner-info">
                                    <span class="banner-name">{{ Str::limit($bp->nombre, 35) }}</span>
                                    <span class="banner-price">Bs {{ number_format($bp->precio, 2) }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    {{-- Fallback: imágenes estáticas si no hay suficientes productos --}}
                    @if($sliderProducts->count() < 4)
                        <div class="li-banner mb-15">
                            <a class="li-banner-card" href="{{ route('list.product') }}">
                                <img src="{{ asset('images/banner/1_1.jpg') }}" alt="Catálogo">
                                <div class="banner-info">
                                    <span class="banner-name">Ver catálogo completo</span>
                                </div>
                            </a>
                        </div>
                        <div class="li-banner">
                            <a class="li-banner-card" href="{{ route('list.product') }}">
                                <img src="{{ asset('images/banner/1_2.jpg') }}" alt="Ofertas">
                                <div class="banner-info">
                                    <span class="banner-name">Ofertas de la semana</span>
                                </div>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    {{-- ══════════════ FIN SLIDER CON BANNERS LATERALES ══════════════ --}}


    {{-- ===================== BANDA PROMO ===================== --}}
    <div class="static-top-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="static-top-content mt-sm-30">
                        Envío gratis en compras mayores a <span>Bs500</span> — Nuevo código: <span>NEXTLEVEL10</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TABS DE PRODUCTOS ===================== --}}
    <div class="product-area pt-55 pb-25 pt-xs-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="li-product-tab">
                        <ul class="nav li-product-menu">
                            <li><a class="active" data-toggle="tab" href="#tab-nuevos"><span>Nuevas Llegadas</span></a></li>
                            <li><a data-toggle="tab" href="#tab-vendidos"><span>Más Vendidos</span></a></li>
                            <li><a data-toggle="tab" href="#tab-destacados"><span>Destacados</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                {{-- Tab: Nuevas llegadas --}}
                <div id="tab-nuevos" class="tab-pane active show" role="tabpanel">
                    <div class="row">
                        <div class="product-active owl-carousel">
                            @foreach($newProducts as $prod)
                                @include('partials.home-product-card', ['prod' => $prod])
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tab: Más vendidos --}}
                <div id="tab-vendidos" class="tab-pane" role="tabpanel">
                    <div class="row">
                        <div class="product-active owl-carousel">
                            @foreach($bestSellers as $prod)
                                @include('partials.home-product-card', ['prod' => $prod])
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tab: Destacados --}}
                <div id="tab-destacados" class="tab-pane" role="tabpanel">
                    <div class="row">
                        <div class="product-active owl-carousel">
                            @foreach($featuredProducts as $prod)
                                @include('partials.home-product-card', ['prod' => $prod])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SECCIONES POR CATEGORÍA ===================== --}}
    @foreach($categoryGroups as $group)
        <section class="product-area li-laptop-product pt-55 pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="li-section-title">
                            <h2><span>{{ $group['category']->name }}</span></h2>
                            <a href="{{ route('category.show', $group['category']->slug) }}"
                               style="float:right;font-size:13px;color:#fed700;font-weight:600;margin-top:8px;">
                                Ver todos <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="row">
                            <div class="product-active owl-carousel">
                                @foreach($group['products'] as $prod)
                                    @include('partials.home-product-card', ['prod' => $prod])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    {{-- ===================== BANNER INFERIOR ===================== --}}
    <div class="li-static-banner li-static-banner-4 text-center pt-20 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 pb-sm-30 pb-xs-30">
                    <div class="single-banner">
                        <a href="{{ route('list.product') }}">
                            <img src="{{ asset('images/banner/2_3.jpg') }}" alt="Oferta">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-banner">
                        <a href="{{ route('list.product') }}">
                            <img src="{{ asset('images/banner/2_4.jpg') }}" alt="Productos">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast confirmación de carrito --}}
    <div id="home-toast" style="display:none;position:fixed;bottom:24px;right:24px;z-index:9999;
        background:#28a745;color:#fff;padding:12px 20px;border-radius:8px;
        box-shadow:0 4px 12px rgba(0,0,0,0.2);font-size:14px;">
        🛒 <span id="home-toast-msg">Producto agregado</span>
    </div>
    <script>
        window.addEventListener('product-added', e => {
            const t = document.getElementById('home-toast');
            document.getElementById('home-toast-msg').textContent = (e.detail.nombre || 'Producto') + ' agregado al carrito';
            t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 3000);
        });
    </script>

</div>
