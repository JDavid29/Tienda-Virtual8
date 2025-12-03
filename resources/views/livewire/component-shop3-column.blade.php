<div>
    <!-- Begin Li's Content Wraper Area -->
    <div class="content-wraper pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Begin Li's Banner Area -->
                    <div class="single-banner shop-page-banner">
                        <a href="#">
                            <img src="images/bg-banner/2.jpg" alt="Li's Static Banner">
                        </a>
                    </div>

                    <!-- shop-top-bar start -->
                    <div class="shop-top-bar mt-30">
                        <div class="shop-bar-inner">
                            <div class="product-view-mode">
                                <ul class="nav shop-item-filter-list" role="tablist">
                                    <li class="active" role="presentation"><a aria-selected="true" class="active show" data-toggle="tab" role="tab" aria-controls="grid-view" href="#grid-view"><i class="fa fa-th"></i></a></li>
                                    <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="list-view" href="#list-view"><i class="fa fa-th-list"></i></a></li>
                                </ul>
                            </div>
                            <div class="toolbar-amount">
                                <span>Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} de {{ $products->total() ?? 0 }}</span>
                            </div>
                        </div>
                        <!-- product-select-box start -->
                        <div class="product-select-box">
                            <div class="product-short">
                                <p>Ordenar por:</p>
                                <select class="nice-select">
                                    <option value="trending">Pertinencia</option>
                                    <option value="sales">Nombre (A - Z)</option>
                                    <option value="sales">Nombre (Z - A)</option>
                                    <option value="rating">Precio (Low &gt; High)</option>
                                    <option value="date">Calificacion (mas baja)</option>
                                </select>
                            </div>
                        </div>
                        <!-- product-select-box end -->
                    </div>
                    <!-- shop-top-bar end -->

                    <!-- shop-products-wrapper start -->
                    <div class="shop-products-wrapper">
                        <div class="tab-content">
                            <div id="grid-view" class="tab-pane fade active show" role="tabpanel">
                                <div class="product-area shop-product-area">
                                    <div class="row">
                                        @if(isset($products) && $products->count())
                                            @foreach($products as $prod)
                                                <div class="col-lg-4 col-md-4 col-sm-6 mt-40">
                                                    <div class="single-product-wrap">
                                                        <div class="product-image">
                                                            <a href="#">
                                                                <img src="{{ $prod->cover_img ? asset('storage/'.$prod->cover_img) : asset('images/default.jpg') }}" alt="{{ $prod->nombre }}">
                                                            </a>
                                                            <span class="sticker">New</span>
                                                        </div>
                                                        <div class="product_desc">
                                                            <div class="product_desc_info">
                                                                <div class="product-review">
                                                                    <h5 class="manufacturer"><a href="#">{{ $prod->category->name ?? 'Sin categoría' }}</a></h5>
                                                                    {{-- la valoracion con estrellas --}}
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
                                                                <h4><a class="product_name" href="#">{{ $prod->nombre }}</a></h4>
                                                                <div class="price-box">
                                                                    <span class="new-price">BOB {{ number_format($prod->precio ?? 0, 2) }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="add-actions">
                                                                <ul class="add-actions-link">
                                                                    <li class="add-cart"><a href="#" class="btn-add-cart" data-product-id="{{ $prod->id }}" wire:click.prevent="addToCart({{ $prod->id }})">Añadir Al Carrito</a></li>
                                                                    <li><a href="#" title="vista rapida" class="quick-view-btn" wire:click.prevent="$emitTo('quick-view','show', {{ $prod->id }})"><i class="fa fa-eye"></i></a></li>
                                                                    <li><a href="#" class="links-details btn-wishlist" data-product-id="{{ $prod->id }}" wire:click.prevent="addToWishlist({{ $prod->id }})"><i class="fa fa-heart-o"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">No hay productos en la categoría Smartwatch.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div id="list-view" class="tab-pane product-list-view fade" role="tabpanel">
                                <div class="row">
                                    <div class="col">
                                        @if(isset($products) && $products->count())
                                            @foreach($products as $prod)
                                                <div class="row product-layout-list mb-4">
                                                    <div class="col-lg-3 col-md-5">
                                                        <div class="product-image">
                                                            <a href="#">
                                                                <img src="{{ $prod->cover_img ? asset('storage/'.$prod->cover_img) : asset('images/default.jpg') }}" alt="{{ $prod->nombre }}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-7">
                                                        <div class="product_desc">
                                                            <div class="product_desc_info">
                                                                <h4><a class="product_name" href="#">{{ $prod->nombre }}</a></h4>
                                                                <div class="price-box">
                                                                    <span class="new-price">BOB {{ number_format($prod->precio ?? 0, 2) }}</span>
                                                                </div>
                                                                <p>{{ Str::limit($prod->descripcion ?? '', 150) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 d-flex align-items-center">
                                                        <div class="shop-add-action">
                                                                <ul class="add-actions-link">
                                                                <li class="add-cart"><a href="#" class="btn-add-cart" data-product-id="{{ $prod->id }}" wire:click.prevent="addToCart({{ $prod->id }})">Añadir Al Carrito</a></li>
                                                                <li class="wishlist"><a href="#" class="btn-wishlist" data-product-id="{{ $prod->id }}" wire:click.prevent="addToWishlist({{ $prod->id }})"><i class="fa fa-heart-o"></i>Añadir a la lista de deseos</a></li>
                                                                <li><a class="quick-view" href="#" wire:click.prevent="$emitTo('quick-view','show', {{ $prod->id }})"><i class="fa fa-eye"></i>Quick view</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div>No hay productos para mostrar.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="paginatoin-area mt-4">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <p class="mb-0">Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} de {{ $products->total() ?? 0 }} artículo(s)</p>
                                        <div>
                                            {{ $products->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- shop-products-wrapper end -->
                </div>
            </div>
        </div>
    </div>
    <!-- Content Wraper Area End Here -->

</div>

    <!-- Toast / Animation container -->
    <div id="live-notify-container" style="position:fixed;top:20px;right:20px;z-index:9999;pointer-events:none"></div>

    <style>
        .lv-toast{pointer-events:auto;min-width:220px;background:#fff;padding:10px 14px;border-radius:6px;box-shadow:0 6px 18px rgba(0,0,0,0.12);margin-top:10px;font-size:14px;display:flex;align-items:center}
        .lv-toast-success{border-left:4px solid #28a745}
        .lv-toast-error{border-left:4px solid #dc3545}
        .lv-toast .lv-msg{margin-left:8px}
        .lv-pulse{animation:lv-pop .45s ease forwards}
        @keyframes lv-pop{0%{transform:scale(1)}50%{transform:scale(1.25)}100%{transform:scale(1)}}
        .lv-pulse-heart{color:#e74c3c;transform-origin:center}
    </style>

    <script>
        (function(){
            function showToast(type, message){
                var container = document.getElementById('live-notify-container');
                if(!container) return;
                var toast = document.createElement('div');
                toast.className = 'lv-toast ' + (type === 'success' ? 'lv-toast-success' : 'lv-toast-error');
                toast.innerHTML = '<strong>' + (type === 'success' ? 'Éxito' : 'Error') + '</strong><div class="lv-msg">' + (message || '') + '</div>';
                container.appendChild(toast);
                setTimeout(function(){ toast.style.opacity = '0'; toast.style.transition = 'opacity .4s'; }, 3000);
                setTimeout(function(){ try{ container.removeChild(toast);}catch(e){} }, 3500);
            }

            window.addEventListener('notify', function(e){
                var detail = (e && e.detail) ? e.detail : {};
                var type = detail.type || 'success';
                var message = detail.message || '';
                var action = detail.action || null;
                var productId = detail.productId || null;

                // show toast
                showToast(type, message);

                // animate target button if present
                if(productId && action === 'wishlist'){
                    var sel = document.querySelector('.btn-wishlist[data-product-id="' + productId + '"]');
                    if(sel){
                        var icon = sel.querySelector('i');
                        if(icon){
                            icon.classList.add('lv-pulse-heart');
                            icon.classList.add('lv-pulse');
                            setTimeout(function(){ icon.classList.remove('lv-pulse'); }, 600);
                        }
                    }
                }

                if(productId && action === 'cart'){
                    var sel2 = document.querySelector('.btn-add-cart[data-product-id="' + productId + '"]');
                    if(sel2){
                        sel2.classList.add('lv-pulse');
                        setTimeout(function(){ sel2.classList.remove('lv-pulse'); }, 600);
                    }
                }
            });
        })();
    </script>

