<!-- Begin Main Menu Area -->
<div class="main-menu-area hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="menu-area">
                    <nav>
                        <ul>
                            <li class="active"><a href="{{ url('/') }}">Inicio</a></li>
                            <li><a href="/shop">Tienda</a></li> {{-- ✅ URL simple --}}
                            <li class="dropdown-holder">
                                <a href="#">Categorías</a>
                                <ul class="hb-dropdown">
                                    <li><a href="/category/portatiles">Portátiles</a></li>
                                    <li><a href="/category/smartphones">Smartphones</a></li>
                                    <li><a href="/category/tablets">Tablets</a></li>
                                    <li><a href="/category/accesorios">Accesorios</a></li>
                                </ul>
                            </li>
                            <li><a href="/about">Nosotros</a></li>
                            <li><a href="/contact">Contacto</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Menu Area End Here -->
