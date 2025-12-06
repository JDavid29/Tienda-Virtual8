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
                    <!-- Li's Banner Area End Here -->
                    <!-- shop-top-bar start -->
                    <div class="shop-top-bar mt-30">
                        <div class="shop-bar-inner">
                            <div class="product-view-mode">
                                <!-- shop-item-filter-list start -->
                                <ul class="nav shop-item-filter-list" role="tablist">
                                    <li class="active" role="presentation"><a aria-selected="true" class="active show" data-toggle="tab" role="tab" aria-controls="grid-view" href="#grid-view"><i class="fa fa-th"></i></a></li>
                                    <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="list-view" href="#list-view"><i class="fa fa-th-list"></i></a></li>
                                </ul>
                                <!-- shop-item-filter-list end -->
                            </div>
                            <div class="toolbar-amount">
                                <span>Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} de {{ $products->total() ?? 0 }}</span>
                            </div>
                        </div>
                        <!-- product-select-box start -->
                        <div class="product-select-box">
                            <div class="product-short">
                                <p>Ordenar por:</p>
                                <select wire:model="sort" class="nice-select">
                                    <option value="trending">Pertinencia</option>
                                    <option value="name_asc">Nombre (A - Z)</option>
                                    <option value="name_desc">Nombre (Z - A)</option>
                                    <option value="price_asc">Precio (Bajo &gt; Alto)</option>
                                    <option value="price_desc">Precio (Alto &gt; Bajo)</option>
                                    <option value="rating_desc">Calificación (Mayor primero)</option>
                                    <option value="rating_asc">Calificación (Menor primero)</option>
                                    <option value="date_desc">Fecha (Más reciente)</option>
                                    <option value="date_asc">Fecha (Más antiguo)</option>
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
                                        @forelse($products as $prod)
                                            <div class="col-lg-3 col-md-4 col-sm-6 mt-40">
                                                <div class="single-product-wrap">
                                                    <div class="product-image">
                                                        <a href="#" wire:click.prevent="viewProduct({{ $prod->id }})">
                                                            @include('partials.product-image', [
                                                                'image' => $prod->cover_img ?? null,
                                                                'alt' => $prod->nombre ?? '',
                                                                'default' => 'images/default.jpg',
                                                                'attributesHtml' => ''
                                                            ])
                                                        </a>
                                                        {{-- es nuevo si tiene menos de 30 dias --}}
                                                        <span class="sticker">Nuevo</span>
                                                    </div>
                                                    <div class="product_desc">
                                                        <div class="product_desc_info">
                                                            <div class="product-review">
                                                                <h5 class="manufacturer">
                                                                    <a href="#">{{ $prod->category->name ?? 'Sin categoría' }}</a>
                                                                </h5>
                                                                <div class="rating-box">
                                                                    <ul class="rating">
                                                                        @php $avg = $prod->resenas_avg_calificacion ?? 0; @endphp
                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                            @if ($avg >= $i)
                                                                                <li><i class="fa fa-star"></i></li>
                                                                            @elseif ($avg >= $i - 0.5)
                                                                                <li><i class="fa fa-star-half-o"></i></li>
                                                                            @else
                                                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                                            @endif
                                                                        @endfor
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <h4><a class="product_name" href="#" wire:click.prevent="viewProduct({{ $prod->id }})">{{ $prod->nombre }}</a></h4>
                                                            <div class="price-box">
                                                                <span class="new-price">${{ number_format($prod->precio, 2) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="add-actions">
                                                            <ul class="add-actions-link">
                                                                <li class="add-cart"><a href="#" wire:click.prevent="addToCart({{ $prod->id }})">Añadir Al Carrito</a></li>
                                                                <li><a href="#" title="Vista rápida" wire:click.prevent="$emitTo('quick-view','show', {{ $prod->id }})"><i class="fa fa-eye"></i></a></li>
                                                                <li><a class="links-details" href="#" wire:click.prevent="addToWishlist({{ $prod->id }})"><i class="fa fa-heart-o"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12"><p>No hay productos en accesorios.</p></div>
                                        @endforelse
                                    </div>
                                </div>
                            <div class="paginatoin-area">
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
