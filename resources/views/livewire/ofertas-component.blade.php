<div>
    {{-- Toast --}}
    <div id="cart-toast" style="display:none;position:fixed;bottom:24px;right:24px;z-index:9999;
        background:#28a745;color:#fff;padding:12px 20px;border-radius:8px;
        box-shadow:0 4px 12px rgba(0,0,0,0.2);font-size:14px;min-width:200px;">
        🛒 <span id="cart-toast-msg">Producto agregado</span>
    </div>

    @if(session('message'))
        <div class="alert alert-success alert-dismissible text-center mb-0" role="alert" style="border-radius:0;">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="content-wraper pt-60 pb-60">
        <div class="container">

            {{-- Encabezado --}}
            <div class="row mb-30">
                <div class="col-12">
                    <div style="border-left:4px solid #fed700;padding-left:14px;">
                        <h3 style="font-weight:700;margin-bottom:4px;">🏷️ Ofertas del Día</h3>
                        <p class="text-muted" style="margin:0;">Los productos más recientes con los mejores precios.</p>
                    </div>
                </div>
            </div>

            {{-- Grid de productos --}}
            <div class="row">
                @forelse ($productos as $producto)
                    <div class="col-lg-3 col-md-4 col-sm-6 mt-40">
                        <div class="single-product-wrap">
                            <div class="product-image">
                                <a href="{{ route('single-product', $producto->id) }}">
                                    @include('partials.product-image', [
                                        'image'          => $producto->cover_img ?? null,
                                        'alt'            => $producto->nombre ?? 'Producto',
                                        'default'        => 'images/product/large-size/1.jpg',
                                        'attributesHtml' => ''
                                    ])
                                </a>
                                <span class="sticker" style="background:#e74c3c;">Oferta</span>
                            </div>
                            <div class="product_desc">
                                <div class="product_desc_info">
                                    <h4>
                                        <a class="product_name" href="{{ route('single-product', $producto->id) }}">
                                            {{ \Illuminate\Support\Str::limit($producto->nombre, 50) }}
                                        </a>
                                    </h4>
                                    <div class="price-box">
                                        <span class="new-price">Bs. {{ number_format($producto->precio, 2) }}</span>
                                    </div>
                                </div>
                                <div class="add-actions">
                                    <ul class="add-actions-link">
                                        <li class="add-cart active">
                                            <a href="#" wire:click.prevent="agregarCarrito({{ $producto->id }})">
                                                Agregar al Carrito
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('single-product', $producto->id) }}" title="Ver detalle">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('wishlist') }}" title="Wishlist">
                                                <i class="fa fa-heart-o"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('compare') }}" title="Comparar">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-60">
                        <i class="fa fa-tag fa-3x text-muted mb-20"></i>
                        <h5 class="text-muted">No hay ofertas disponibles por el momento.</h5>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="row mt-40">
                <div class="col-12 d-flex justify-content-center">
                    {{ $productos->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('product-added', function(e) {
            var toast = document.getElementById('cart-toast');
            document.getElementById('cart-toast-msg').innerText = (e.detail.nombre || 'Producto') + ' agregado al carrito';
            toast.style.display = 'block';
            setTimeout(function(){ toast.style.display = 'none'; }, 3000);
        });
    </script>
</div>
