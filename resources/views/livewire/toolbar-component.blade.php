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
                                <a class="" href="/admin/login"><span>Iniciar Sesión</span></a> {{-- ✅ URL directa --}}
                            </li>
                            <li>
                                <a class="" href="/admin/login"><span>Registrarse</span></a> {{-- ✅ URL directa --}}
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
                                        <li class="active"><a href="#"><img src="images/menu/flag-icon/3.jpg" alt="">Español</a></li>
                                        <li><a href="#"><img src="images/menu/flag-icon/1.jpg" alt="">Inglés</a></li>
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
                        <a href="index.html">
                            <img src="images/menu/logo/2.JPG" alt="">
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
                                    <span class="cart-item-count wishlist-item-count">0</span>
                                    <i class="fa fa-heart-o"></i>
                                </a>
                            </li>
                            <!-- Header Middle Wishlist Area End Here -->

                            <!-- Begin Header Mini Cart Area -->
                            <li class="hm-minicart">
                                <div class="hm-minicart-trigger">
                                    <span class="item-icon"></span>
                                    <span class="item-text">
                                        BOB. {{ number_format($cartSubTotal, 2) }}
                                        <span class="cart-item-count">{{ $cartTotalQuantity }}</span>
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
                                                    <a href="single-product.html" class="minicart-product-image">
                                                        @if(isset($item['attributes']['cover_img']) && $item['attributes']['cover_img'])
                                                            <img src="{{ asset('storage/' . $item['attributes']['cover_img']) }}" alt="cart products">
                                                        @else
                                                            <img src="{{ asset('image_default.png') }}" alt="cart products">
                                                        @endif
                                                    </a>
                                                    <div class="minicart-product-details">
                                                        <h6><a href="single-product.html">{{ $item['name'] }}</a></h6>
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
                                        <a href="checkout.html" class="li-button li-button-fullwidth li-button-sm">
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
                    <!-- Begin Header Bottom Menu Area -->
                    <div class="hb-menu">
                        <nav>
                            <ul>
                                {{-- inicio de la tienda en la barra de herramientas --}}
                                <li><a href="{{ route('inicio') }}">Todo</a></li>
                                {{-- fin de la tienda en la barra de herramientas --}}

                                {{-- Hogar
                                <li class="dropdown-holder"><a href="index.html">Home</a>
                                    {{-- <ul class="hb-dropdown">
                                        <li><a href="#">Accesorios</a></li>
                                        <li><a href="#">SmartWatches</a></li>
                                        <li><a href="index-3.html">Portatiles</a></li>
                                        <li class="active"><a href="index-4.html">General</a></li>
                                    </ul>
                                </li> --}}

                                {{-- Ofertas del dia --}}
                                {{-- <li><a href="shop-left-sidebar.html">Ofertas del día</a></li> --}}
                                {{-- Fin Ofertas del dia --}}

                                <li class="megamenu-holder"><a href="{{ route('shopleftsidebar') }}">Comercio</a>
                                    <ul class="megamenu hb-megamenu">
                                        <li><a href="shop-left-sidebar.html">Shop Page Layout</a>
                                            <ul>
                                                <li><a href="shop-3-column.html">Shop 3 Column</a></li>
                                                <li><a href="shop-4-column.html">Shop 4 Column</a></li>
                                                <li><a href="shop-left-sidebar.html">Shop Left Sidebar</a></li>
                                                <li><a href="shop-right-sidebar.html">Shop Right Sidebar</a></li>
                                                <li><a href="shop-list.html">Shop List</a></li>
                                                <li><a href="shop-list-left-sidebar.html">Shop List Left Sidebar</a></li>
                                                <li><a href="shop-list-right-sidebar.html">Shop List Right Sidebar</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-holder"><a href="{{ route('blogleftsidebar') }}">Blog</a>
                                    <ul class="hb-dropdown">
                                        <li class="sub-dropdown-holder"><a href="blog-left-sidebar.html">Blog Grid View</a>
                                            <ul class="hb-dropdown hb-sub-dropdown">
                                                <li><a href="blog-2-column.html">Blog 2 Column</a></li>
                                                <li><a href="blog-3-column.html">Blog 3 Column</a></li>
                                                <li><a href="blog-left-sidebar.html">Grid Left Sidebar</a></li>
                                                <li><a href="blog-right-sidebar.html">Grid Right Sidebar</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="megamenu-static-holder"><a href="{{ route('compare') }}">Comparar</a>
                                    <ul class="megamenu hb-megamenu">
                                        <li><a href="blog-left-sidebar.html">Blog Layouts</a>
                                            <ul>
                                                <li><a href="blog-2-column.html">Blog 2 Column</a></li>
                                                <li><a href="blog-3-column.html">Blog 3 Column</a></li>
                                                <li><a href="blog-left-sidebar.html">Grid Left Sidebar</a></li>
                                                <li><a href="blog-right-sidebar.html">Grid Right Sidebar</a></li>
                                                <li><a href="blog-list.html">Blog List</a></li>
                                                <li><a href="blog-list-left-sidebar.html">List Left Sidebar</a></li>
                                                <li><a href="blog-list-right-sidebar.html">List Right Sidebar</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('about-us') }}">Sobre Nosotros</a></li>
                                <li><a href="{{ route('contacto') }}">Contacto</a></li>
                                <li><a href="{{ route('shop3column') }}">Reloj inteligente</a></li>
                                <li><a href="{{ route('shop4column') }}">Accesorios</a></li>
                                {{-- Servicio al Cliente --}}
                                <li><a href="{{ route('faq') }}">Centro de ayuda</a></li>
                                {{-- Fin Servicio al Cliente --}}
                            </ul>
                        </nav>
                    </div>
                    <!-- Header Bottom Menu Area End Here -->
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
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End Here -->
</div>
