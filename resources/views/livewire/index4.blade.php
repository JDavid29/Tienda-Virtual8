<div>

    {{-- ===================== SLIDER ===================== --}}
    <div class="slider-with-banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="slider-area pt-sm-30 pt-xs-30">
                        <div class="slider-active owl-carousel">
                            @forelse($sliderProducts as $prod)
                                <div class="single-slide align-center-left">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5>Nueva Llegada <span>— Esta semana</span></h5>
                                        <h2>{{ Str::limit($prod->nombre, 50) }}</h2>
                                        <h3>Desde <span>Bs{{ number_format($prod->precio, 2) }}</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="{{ route('single-product', $prod->id) }}">Ver producto</a>
                                        </div>
                                    </div>
                                    <div class="slider-thumb">
                                        @include('partials.product-image', [
                                            'image'         => $prod->cover_img ?? null,
                                            'alt'           => $prod->nombre,
                                            'default'       => 'images/default.jpg',
                                            'attributesHtml'=> 'style="max-height:260px;object-fit:contain;"'
                                        ])
                                    </div>
                                </div>
                            @empty
                                <div class="single-slide align-center-left">
                                    <div class="slider-content">
                                        <h5>Bienvenido a <span>nuestra tienda</span></h5>
                                        <h2>Los mejores productos</h2>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="{{ route('list.product') }}">Ver catálogo</a>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Banners laterales con últimos 2 productos --}}
                <div class="col-lg-4 col-md-12 text-center pt-sm-30 pt-xs-30">
                    @foreach($sliderProducts->skip(3)->take(2) as $bp)
                        <div class="li-banner mb-15">
                            <a href="{{ route('single-product', $bp->id) }}">
                                @include('partials.product-image', [
                                    'image'         => $bp->cover_img ?? null,
                                    'alt'           => $bp->nombre,
                                    'default'       => 'images/banner/1_1.jpg',
                                    'attributesHtml'=> 'style="width:100%;height:160px;object-fit:cover;"'
                                ])
                            </a>
                        </div>
                    @endforeach
                    @if($sliderProducts->count() < 4)
                        <div class="li-banner mb-15">
                            <a href="{{ route('list.product') }}">
                                <img src="{{ asset('images/banner/1_1.jpg') }}" alt="Catálogo" style="width:100%;">
                            </a>
                        </div>
                        <div class="li-banner">
                            <a href="{{ route('list.product') }}">
                                <img src="{{ asset('images/banner/1_2.jpg') }}" alt="Ofertas" style="width:100%;">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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

    {{-- Toast confirmación --}}
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
