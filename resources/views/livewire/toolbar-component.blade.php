<div>
    <!-- Begin Header Top Area -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <!-- Begin Header Top Left Area -->
                <div class="col-lg-3 col-md-4">
                    <div class="header-top-left">
                        <ul class="phone-wrap">
                            <li><span>Teléfono de Consulta:</span><a href="#">(+591) 70547372</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Header Top Left Area End Here -->
                <!-- Begin Header Top Right Area -->
                <div class="col-lg-9 col-md-8">
                    <div class="header-top-right">
                        <ul class="ht-menu">
                            @guest
                            <li>
                                <a class="" href="{{ route('login.client') }}"><span>Iniciar Sesión</span></a>
                            </li>
                            <li>
                                <a class="" href="{{ route('register') }}"><span>Registrarse</span></a>
                            </li>
                            @else
                                <li>
                                    <div class="ht-setting-trigger"><span>{{ Auth::user()->name }}</span></div>
                                    <div class="setting ht-setting">
                                        <ul class="ht-setting-list">
                                            <li>
                                                <a class="nav-link" href="{{ route('voyager.logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>
                                            </li>
                                            <form id="logout-form" action="{{ route('voyager.logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </ul>
                                    </div>
                                </li>
                            @endguest
                            <!-- Begin Currency Area -->
                            <li>
                                <span class="currency-selector-wrapper">Divisa :</span>
                                <div class="ht-currency-trigger"><span>BOB Bs</span></div>
                                <div class="currency ht-currency">
                                    <ul class="ht-setting-list">
                                        <li class="active"><a href="#">BOB Bs</a></li>
                                        <li><a href="#">USD $</a></li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Currency Area End Here -->
                            <!-- Begin Language Area -->
                            <li>
                                <span class="language-selector-wrapper">Idioma :</span>
                                <div class="ht-language-trigger"><span>Español</span></div>
                                <div class="language ht-language">
                                    <ul class="ht-setting-list">
                                        <li class="active"><a href="#"><img src="{{ asset('images/menu/flag-icon/3.jpg') }}" alt="">Español</a></li>
                                        <li><a href="#"><img src="{{ asset('images/menu/flag-icon/1.jpg') }}" alt="">Inglés</a></li>
                                    </ul>
                                </div>
                            </li>
                            <!-- Language Area End Here -->
                        </ul>
                    </div>
                </div>
                <!-- Header Top Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Top Area End Here -->

    <!-- Begin Header Middle Area -->
    <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
        <div class="container">
            <div class="row">
                <!-- Begin Header Logo Area -->
                <div class="col-lg-3">
                    <div class="logo pb-sm-30 pb-xs-30">
                        <a href="{{ route('inicio') }}">
                            <img src="{{ asset('images/menu/logo/nlg-logo.jpg') }}" alt="NextLevelGamer" width="188" height="45">
                        </a>
                    </div>
                </div>
                <!-- Header Logo Area End Here -->

                <!-- Begin Header Middle Right Area -->
                <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15">
                    <!-- Begin Middle Searchbox Area -->
                    <form action="#" class="hm-searchbox">
                        <select class="nice-select select-search-category">
                            <option value="0">Todo</option>
                            <option value="10">Portátiles</option>
                            <option value="11">TV &amp; Audio</option>
                            <option value="12">Smartphone</option>
                            <option value="13">Camaras</option>
                            <option value="14">Auricular</option>
                            <option value="15">Reloj inteligente</option>
                            <option value="16">Accesorios</option>
                        </select>
                        <input type="text" placeholder="Introduzca su clave de búsqueda ...">
                        <button class="li-btn" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                    <!-- Header Middle Searchbox Area End Here -->

                    <!-- Begin Header Middle Right Area -->
                    <div class="header-middle-right">
                        <ul class="hm-menu">
                            <!-- Begin Header Middle Wishlist Area -->
                            <li class="hm-wishlist">
                                <a href="{{ route('wishlist') }}">
                                    {{-- cantidad de productos en la lista de deseos --}}
                                    <span class="cart-item-count wishlist-item-count">{{ $wishlistCount }}</span>
                                    <i class="fa fa-heart-o"></i>
                                </a>
                            </li>
                            <!-- Header Middle Wishlist Area End Here -->

                            <!-- Begin Header Mini Cart Area -->
                            <li class="hm-minicart">
                                <div class="hm-minicart-trigger">
                                    <span class="item-icon">
                                        <span class="cart-item-count">{{ $cartTotalQuantity }}</span>
                                    </span>
                                    <span class="item-text">
                                        BOB.&nbsp;{{ number_format($cartSubTotal, 2) }}
                                    </span>
                                </div>
                                <div class="minicart">
                                    <ul class="minicart-product-list">
                                        @if ($cartIsEmpty)
                                            <li>
                                                <p>El carrito está vacío</p>
                                            </li>
                                        @else
                                            @foreach ($cartItems as $item)
                                                <li>
                                                    <a href="{{ route('single-product', $item['id']) }}" class="minicart-product-image">
                                                            @include('partials.product-image', [
                                                                'image' => $item['attributes']['image'] ?? $item['attributes']['cover_img'] ?? null,
                                                                'alt' => $item['name'] ?? 'cart products',
                                                                'default' => 'images/default.png',
                                                                'attributesHtml' => ''
                                                            ])
                                                    </a>
                                                    <div class="minicart-product-details">
                                                        <h6><a href="{{ route('single-product', $item['id']) }}">{{ \Illuminate\Support\Str::limit($item['name'], 60, '...') }}</a></h6>
                                                        <span>Bs. {{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}</span>
                                                    </div>
                                                    <button wire:click="removeFromCart('{{ $item['id'] }}')" class="close" type="button">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <p class="minicart-total">SUBTOTAL: <span>Bs. {{ number_format($cartSubTotal, 2) }}</span></p>
                                    <div class="minicart-button">
                                        <a href="/cart" class="li-button li-button-dark li-button-fullwidth li-button-sm">
                                            <span>VER CARRITO COMPLETO</span>
                                        </a>
                                        <a href="{{ route('verificar') }}" class="li-button li-button-fullwidth li-button-sm">
                                            <span>VERIFICAR</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <!-- Header Mini Cart Area End Here -->
                        </ul>
                    </div>
                    <!-- Header Middle Right Area End Here -->
                </div>
                <!-- Header Middle Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Middle Area End Here -->

    <!-- Begin Header Bottom Area -->
    <div class="header-bottom header-sticky stick d-none d-lg-block d-xl-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hb-menu">
                        <nav>
                            <ul>
                                {{-- 1. Inicio --}}
                                <li class="{{ request()->routeIs('inicio','home') ? 'active' : '' }}">
                                    <a href="{{ route('inicio') }}">Inicio</a>
                                </li>

                                {{-- 2. Tienda: catálogo con vistas --}}
                                <li class="dropdown-holder {{ request()->routeIs('list.product','shopleftsidebar','shop3column','shop4column') ? 'active' : '' }}">
                                    <a href="{{ route('list.product') }}">Tienda</a>
                                    <ul class="hb-dropdown">
                                        <li><a href="{{ route('list.product') }}">Todos los Productos</a></li>
                                        <li><a href="{{ route('shopleftsidebar') }}">Vista con Filtros</a></li>
                                        <li><a href="{{ route('shop3column') }}">Vista 3 Columnas</a></li>
                                        <li><a href="{{ route('shop4column') }}">Vista 4 Columnas</a></li>
                                    </ul>
                                </li>

                                {{-- 3. Categorías dinámicas --}}
                                <li class="dropdown-holder {{ request()->is('category/*') ? 'active' : '' }}">
                                    <a href="#">Categorías</a>
                                    <ul class="hb-dropdown">
                                        @foreach($categories as $cat)
                                            <li>
                                                <a href="{{ route('category.show', $cat->slug) }}">
                                                    {{ $cat->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>

                                {{-- 4. Ofertas del Día --}}
                                <li class="{{ request()->routeIs('ofertas') ? 'active' : '' }}">
                                    <a href="{{ route('ofertas') }}"
                                       style="{{ request()->routeIs('ofertas') ? '' : 'color:#c0392b!important;' }}">
                                        🏷️ Ofertas
                                    </a>
                                </li>

                                {{-- 5. Marcas / Proveedores --}}
                                <li class="{{ request()->routeIs('marcas') ? 'active' : '' }}">
                                    <a href="{{ route('marcas') }}">Marcas</a>
                                </li>

                                {{-- 6. Comparar --}}
                                <li class="{{ request()->routeIs('compare') ? 'active' : '' }}">
                                    <a href="{{ route('compare') }}">Comparar</a>
                                </li>

                                {{-- 7. Blog --}}
                                <li class="{{ request()->routeIs('blogleftsidebar') ? 'active' : '' }}">
                                    <a href="{{ route('blogleftsidebar') }}">Blog</a>
                                </li>

                                {{-- 8. Mi Cuenta (solo autenticados) / Nosotros + Ayuda --}}
                                <li class="dropdown-holder {{ request()->routeIs('about-us','faq','contacto','mi-cuenta') ? 'active' : '' }}">
                                    <a href="#">Más</a>
                                    <ul class="hb-dropdown">
                                        @auth
                                            <li><a href="{{ route('mi-cuenta') }}"><i class="fa fa-user-o"></i> &nbsp;Mi Cuenta</a></li>
                                        @endauth
                                        <li><a href="{{ route('about-us') }}">Sobre Nosotros</a></li>
                                        <li><a href="{{ route('faq') }}">Preguntas Frecuentes</a></li>
                                        <li><a href="{{ route('contacto') }}">Contacto</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Area End Here -->

    <!-- Begin Mobile Menu Area -->
    <div class="mobile-menu-area mobile-menu-area-4 d-lg-none d-xl-none col-12">
        <div class="container">
            <div class="row">
                <div class="mobile-menu">
                    <nav id="mobile-menu-nav">
                        <ul>
                            <li><a href="{{ route('inicio') }}">Inicio</a></li>
                            <li><a href="{{ route('list.product') }}">Productos</a></li>
                            <li><a href="#">Categorías</a>
                                <ul>
                                    <li><a href="{{ route('category.portatiles') }}">Portátiles</a></li>
                                    <li><a href="{{ route('category.smartphones') }}">Smartphones</a></li>
                                    <li><a href="{{ route('category.tablets') }}">Tablets</a></li>
                                    <li><a href="{{ route('category.accesorios') }}">Accesorios</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('about-us') }}">Nosotros</a></li>
                            <li><a href="{{ route('contacto') }}">Contacto</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End Here -->
</div>
