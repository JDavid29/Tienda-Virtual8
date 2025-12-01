<!-- Error 404 Area Start -->
    <div class="error404-area pt-30 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-wrapper text-center ptb-50 pt-xs-20">
                        <div class="error-text">
                            <h1>404</h1>
                            <h2>UPS! PAGINA NO ENCONTRADA</h2>
                            <p>Lo sentimos, pero la pagina que busca no existe, ha sido eliminada, ha<br> cambiado de nombre o no esta disponible temporalmente.</p>
                        </div>
                        <div class="search-error">
                            <form id="search-form" action="#">
                                <input type="text" placeholder="Buscar">
                                <button><i class="zmdi zmdi-search"></i></button>
                            </form>
                        </div>
                        <div class="error-button">
                            <a href="{{ route('home') ?? url('/') }}">VOLVER A LA PAGINA DE INICIO</a>
                            {{-- <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Error 404 Area End -->
