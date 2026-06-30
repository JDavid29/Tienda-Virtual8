<div>
    <style>
        .livewire-toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 11000;
        }
        .livewire-toast {
            min-width: 240px;
            margin-bottom: .5rem;
            padding: .6rem 1rem;
            border-radius: 6px;
            color: #fff;
            box-shadow: 0 6px 18px rgba(0,0,0,.12);
            opacity: 0;
            transform: translateY(-6px);
            transition: opacity .18s ease, transform .18s ease;
            font-size: 14px;
        }
        .livewire-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .livewire-toast.success { background: #28a745; }
        .livewire-toast.error { background: #dc3545; }

        .flash-pulse {
            animation: flashPulse .9s ease-in-out;
        }
        @keyframes flashPulse {
            0% { transform: scale(1); box-shadow: none; }
            30% { transform: scale(1.06); box-shadow: 0 6px 18px rgba(0,0,0,.12); }
            100% { transform: scale(1); box-shadow: none; }
        }
    </style>
    <!-- content-wraper start -->
    <div class="content-wraper">
        <div class="container">
            <div class="row single-product-area">
                <div class="col-lg-5 col-md-6">
                    <!-- Product Details Left -->
                    <div class="product-details-left">
                        @php
                            $img = $product->cover_img ?? null;
                            if ($img) {
                                if (\Illuminate\Support\Str::startsWith($img, ['http://','https://'])) {
                                    $imgUrl = $img;
                                } elseif (\Illuminate\Support\Str::startsWith($img, ['/', 'images/', 'img/', 'storage/', 'uploads/'])) {
                                    $imgUrl = asset(ltrim($img, '/'));
                                } else {
                                    $imgUrl = asset('storage/' . ltrim($img, '/'));
                                }
                            } else {
                                $imgUrl = asset('images/product/large-size/1.jpg');
                            }
                        @endphp
                        <div class="product-details-images slider-navigation-1">
                            <div class="lg-image">
                                <a class="popup-img venobox vbox-item" href="{{ $imgUrl }}" data-gall="myGallery">
                                    <img src="{{ $imgUrl }}" alt="{{ $product->nombre ?? 'product image' }}">
                                </a>
                            </div>
                        </div>
                        <div class="product-details-thumbs slider-thumbs-1">
                            <div class="sm-image"><img src="{{ $imgUrl }}" alt="product image thumb"></div>
                        </div>
                    </div>
                    <!--// Product Details Left -->
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="product-details-view-content pt-60">
                        <div class="product-info">
                            <h2>{{ $product->nombre ?? 'Producto sin título' }}</h2>
                            <span class="product-details-ref">Referencia: {{ $product->id ?? 'N/A' }} - {{ $product->category->name ?? '' }}</span>
                            <div class="rating-box pt-20">
                                <ul class="rating rating-with-review-item">
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li class="no-star"><i class="fa fa-star-o"></i></li>
                                    <li class="no-star"><i class="fa fa-star-o"></i></li>
                                    <li class="review-item">
                                        <a href="#reviews" onclick="event.preventDefault(); (function(){ var tab = document.querySelector('.li-product-menu a[href="#reviews"]'); if (tab) { try { if (typeof $ !== 'undefined' && typeof $.fn.tab === 'function') { $(tab).tab('show'); } else { tab.click(); } } catch(e){ try{ tab.click(); }catch(err){} } } var el = document.querySelector('#reviews'); if (el) el.scrollIntoView({behavior:'smooth'}); })();">Leer reseñas</a>
                                    </li>
                                    <li class="review-item">
                                        <a href="#" onclick="event.preventDefault(); if (typeof Livewire !== 'undefined') { Livewire.emit('startCreate'); } else { console.error('Livewire not available'); }">Escribir reseña</a>
                                    </li>
                                </ul>
                            </div>
                                    <div class="price-box pt-20">
                                <span class="new-price new-price-2">BOB {{ number_format($product->precio ?? 0, 2) }}</span>
                            </div>
                            {{-- ⚠️  XSS ALMACENADO: {!! !!} renderiza HTML sin escapar desde la BD.
                                 Un admin puede guardar <script>malicioso</script> en descripcion.
                                 FIX: usar {{ }} si no se necesita HTML, o instalar HTMLPurifier
                                 para sanear etiquetas antes de guardar/mostrar. --}}
                            <div class="product-desc">
                                <p>
                                    <span>{!! $product->descripcion ?? 'Sin descripción disponible.' !!}</span>
                                </p>
                            </div>
                            <div class="product-variants">
                                <div class="produt-variants-size">
                                    <label>Dimension</label>
                                    <select class="nice-select">
                                        <option value="1" title="S" selected="selected">40x60cm</option>
                                        <option value="2" title="M">60x90cm</option>
                                        <option value="3" title="L">80x120cm</option>
                                    </select>
                                </div>
                            </div>
                            <div class="single-add-to-cart">
                                <form action="#" class="cart-quantity" wire:submit.prevent="addToCart">
                                    <div class="quantity">
                                        <label>Cantidad</label>
                                        <div class="cart-plus-minus">
                                            <input name="quantity" class="cart-plus-minus-box" type="number" min="1" step="1" wire:model.defer="quantity">
                                            <div class="dec qtybutton"><a href="#" wire:click.prevent="decreaseQuantity"><i class="fa fa-angle-down"></i></a></div>
                                            <div class="inc qtybutton"><a href="#" wire:click.prevent="increaseQuantity"><i class="fa fa-angle-up"></i></a></div>
                                        </div>
                                    </div>
                                    <button class="add-to-cart" type="submit">Añadir al carrito</button>
                                </form>
                                @php $stock = $product->cantidad ?? 0; @endphp
                                @if($stock > 0)
                                    <span style="display:inline-block;margin-top:8px;padding:4px 12px;background:#fff3cd;color:#856404;border:1px solid #ffc107;border-radius:20px;font-size:13px;font-weight:600;">
                                        <i class="fa fa-exclamation-circle" style="margin-right:4px;"></i>Solo quedan {{ $stock }}
                                    </span>
                                @else
                                    <span style="display:inline-block;margin-top:8px;padding:4px 12px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:20px;font-size:13px;font-weight:600;">
                                        <i class="fa fa-times-circle" style="margin-right:4px;"></i>Sin stock
                                    </span>
                                @endif
                            </div>
                            <div class="product-additional-info pt-25">
                                <a class="wishlist-btn" href="#" wire:click.prevent="addToWishlist"><i class="fa fa-heart-o"></i>Añadir a la lista de deseos</a>
                                <div class="product-social-sharing pt-25">
                                    <ul>
                                        <li class="facebook"><a href="#"><i class="fa fa-facebook"></i>Facebook</a></li>
                                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i>Twitter</a></li>
                                        <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i>Google +</a></li>
                                        <li class="instagram"><a href="#"><i class="fa fa-instagram"></i>Instagram</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="block-reassurance">
                                <ul>
                                    <li>
                                        <div class="reassurance-item">
                                            <div class="reassurance-icon">
                                                <i class="fa fa-check-square-o"></i>
                                            </div>
                                            <p>Política de seguridad (editar con el módulo de tranquilidad del cliente)</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="reassurance-item">
                                            <div class="reassurance-icon">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <p>Política de entrega (editar con el módulo de tranquilidad del cliente)</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="reassurance-item">
                                            <div class="reassurance-icon">
                                                <i class="fa fa-exchange"></i>
                                            </div>
                                            <p> Política de devoluciones (editar con el módulo de tranquilidad del cliente)</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wraper end -->
    <!-- Begin Product Area -->
    <div class="product-area pt-35">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="li-product-tab">
                        <ul class="nav li-product-menu">
                            <li><a class="active" data-toggle="tab" href="#description"><span>Descripción</span></a></li>
                            <li><a data-toggle="tab" href="#product-details"><span>Detalles del producto</span></a></li>
                            <li><a data-toggle="tab" href="#reviews"><span>Reseñas</span></a></li>
                        </ul>
                    </div>
                    <!-- Begin Li's Tab Menu Content Area -->
                </div>
            </div>
            <div class="tab-content">
                <div id="description" class="tab-pane active show" role="tabpanel">
                    <div class="product-description">
                        {{-- ⚠️  XSS ALMACENADO: igual que descripcion, este campo acepta HTML crudo.
                             FIX: sanear con HTMLPurifier o cambiar a {{ }} si no se usa HTML rico. --}}
                        <div>{!! $product->descripcion_larga ?? 'Sin descripción detallada disponible.' !!}</div>
                    </div>
                </div>
                <div id="product-details" class="tab-pane" role="tabpanel">
                    <div class="product-details-manufacturer">
                        @php
                            $refUrl = $product->referencia ?? $product->id ?? null;
                            if ($refUrl && !\Illuminate\Support\Str::startsWith($refUrl, ['http://','https://'])) {
                                $refUrl = 'http://' . ltrim($refUrl, '/');
                            }
                        @endphp
                        <a href="{{ $refUrl ?? '#' }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $imgUrl }}" alt="{{ $product->nombre ?? 'Product' }}" style="max-width:84px;height:auto;border-radius:4px;">
                        </a>
                        <p><span>Referencia</span> {{ $product->referencia ?? $product->id ?? 'N/A' }}</p>
                    </div>
                </div>
                <div id="reviews" class="tab-pane" role="tabpanel">
                    @livewire('product-reviews', ['productId' => $product->id ?? null])
                </div>
            </div>
        </div>
    </div>
    <!-- Product Area End Here -->
    <!-- Begin Li's Laptop Product Area -->
    <section class="product-area li-laptop-product pt-30 pb-50">
        <div class="container">
            <div class="row">
                <!-- Begin Li's Section Area -->
                <div class="col-lg-12">
                    <div class="li-section-title">
                        <h2>
                            <span>{{ $relatedProducts->count() }} otros productos en la misma categoría:</span>
                        </h2>
                    </div>
                    <div class="row">
                        <div class="product-active owl-carousel">
                                @forelse($relatedProducts as $rp)
                                @php $defaultRelatedImage = 'images/default.png'; @endphp
                                <div class="col-lg-12">
                                    <div class="single-product-wrap">
                                        <div class="product-image">
                                            <a href="{{ route('single-product', ['id' => $rp->id]) }}">
                                                @include('partials.product-image', [
                                                    'image' => $rp->cover_img ?? null,
                                                    'alt' => $rp->nombre ?? 'Product',
                                                    'default' => $defaultRelatedImage,
                                                    'attributesHtml' => ''
                                                ])
                                            </a>
                                            @if(isset($rp->created_at) && \Carbon\Carbon::parse($rp->created_at)->greaterThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()))
                                                <span class="sticker">Nuevo</span>
                                            @endif
                                        </div>
                                        <div class="product_desc">
                                            <div class="product_desc_info">
                                                <div class="product-review">
                                                    <h5 class="manufacturer">
                                                        <a href="#">{{ $rp->category->name ?? '' }}</a>
                                                    </h5>
                                                    <div class="rating-box">
                                                        <ul class="rating">
                                                            <li><i class="fa fa-star-o"></i></li>
                                                            <li><i class="fa fa-star-o"></i></li>
                                                            <li><i class="fa fa-star-o"></i></li>
                                                            <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                            <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h4><a class="product_name" href="{{ route('single-product', ['id' => $rp->id]) }}">{{ $rp->nombre }}</a></h4>
                                                <div class="price-box">
                                                    <span class="new-price">{{ number_format($rp->precio ?? 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="add-actions">
                                                <ul class="add-actions-link">
                                                    <li class="add-cart active"><a href="#" wire:click.prevent="addToCart({{ $rp->id }})">Añadir Al Carrito</a></li>
                                                    <li><a href="#" title="Vista rápida" class="quick-view-btn" wire:click.prevent="openQuickView({{ $rp->id }})"><i class="fa fa-eye"></i></a></li>
                                                    <li><a class="links-details" href="#" wire:click.prevent="addToWishlist({{ $rp->id }})"><i class="fa fa-heart-o"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p>No hay otros productos en esta categoría.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- Li's Section Area End Here -->
            </div>
        </div>
    </section>
    <!-- Li's Laptop Product Area End Here -->
    {{-- live-notify handled globally via partial included in layout --}}

    <script>
        (function(){
            // Ensure Livewire receives the latest input value when the form is submitted
            document.addEventListener('submit', function(e){
                try {
                    var form = e.target;
                    if (form && form.classList && form.classList.contains('cart-quantity')) {
                        var input = form.querySelector('input[name="quantity"]');
                        if (input) {
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                } catch(err) {
                    // silent
                }
            }, true);

            // Some UI plugins modify the input value without emitting events.
            // Listen for clicks on the plus/minus controls and force input/change.
            document.addEventListener('click', function(e){
                try {
                    var el = e.target;
                    // find closest cart-plus-minus container
                    var box = el.closest && el.closest('.cart-plus-minus');
                    if (box) {
                        var input = box.querySelector('input[name="quantity"]');
                        if (input) {
                            // small delay allows plugin to update value first
                            setTimeout(function(){
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                            }, 1);
                        }
                    }
                } catch(err) {
                    // silent
                }
            });
        })();
    </script>

</div>
