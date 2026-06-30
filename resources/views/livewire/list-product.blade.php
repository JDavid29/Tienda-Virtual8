<div>

    <!-- Toast de confirmación -->
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible text-center mb-0" role="alert" style="border-radius:0;">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

<div class="content-wraper pt-60 pb-60">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Begin Li's Banner Area
                            <div class="single-banner shop-page-banner">
                                <a href="#">
                                    <img src="images/bg-banner/2.jpg" alt="Li's Static Banner">
                                </a>
                            </div>
                            Li's Banner Area End Here -->
                            @if(!empty($categoryName))
                                <h3 class="mt-30 mb-10" style="font-weight:700;border-left:4px solid #fed700;padding-left:12px;">
                                    {{ $categoryName }}
                                </h3>
                            @endif

                            <!-- shop-top-bar start -->
                            <div class="shop-top-bar mt-30">
                                <div class="shop-bar-inner">
                                    <!--<div class="product-view-mode">
                                        <ul class="nav shop-item-filter-list" role="tablist">
                                            <li class="active" role="presentation"><a aria-selected="true" class="active show" data-toggle="tab" role="tab" aria-controls="grid-view" href="#grid-view"><i class="fa fa-th"></i></a></li>
                                            <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="list-view" href="#list-view"><i class="fa fa-th-list"></i></a></li>
                                        </ul>
                                    </div>-->
                                    <div class="toolbar-amount">
                                        <span>Showing 1 to 9 of 15</span>
                                    </div>
                                </div>
                                <!-- product-select-box start -->
                                <div class="product-select-box">
                                    <div class="product-short">
                                        <p>Sort By:</p>
                                        <select class="nice-select">
                                            <option value="trending">Relevance</option>
                                            <option value="sales">Name (A - Z)</option>
                                            <option value="sales">Name (Z - A)</option>
                                            <option value="rating">Price (Low &gt; High)</option>
                                            <option value="date">Rating (Lowest)</option>
                                            <option value="price-asc">Model (A - Z)</option>
                                            <option value="price-asc">Model (Z - A)</option>
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
                                                @foreach ($productos as $producto)
                                                <div class="col-lg-3 col-md-4 col-sm-6 mt-40">
                                                    <!-- single-product-wrap start -->
                                                    <div class="single-product-wrap">
                                                        <div class="product-image">
                                                            <a href="{{ route('single-product', $producto->id) }}">
                                                                @include('partials.product-image', [
                                                                    'image' => $producto->cover_img ?? null,
                                                                    'alt' => $producto->nombre ?? "Li's Product Image",
                                                                    'default' => 'images/product/large-size/1.jpg',
                                                                    'attributesHtml' => ''
                                                                ])
                                                            </a>
                                                            <span class="sticker">New</span>
                                                        </div>
                                                        <div class="product_desc">
                                                            <div class="product_desc_info">
                                                                <div class="product-review">
                                                                    <h5 class="manufacturer">
                                                                        <a href="product-details.html">Graphic Corner</a>
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
                                                                <h4><a class="product_name" href="{{ route('single-product', $producto->id) }}">{{ $producto->nombre }}</a></h4>
                                                                <div class="price-box">
                                                                    <span class="new-price">BOB. {{ $producto->precio }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="add-actions">
                                                                <ul class="add-actions-link">
                                                                    <li class="add-cart active">
                                                                        <a href="#"
                                                                            wire:click.prevent="agregarCarrito({{ $producto->id }})"
                                                                            role="button">
                                                                            Añadir
                                                                        </a>
                                                                    </li>
                                                                    <li><a href="#" title="Vista rápida" class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-eye"></i></a></li>
                                                                    <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- single-product-wrap end -->
                                                </div>

                                                @endforeach


                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                    window.addEventListener('product-added', e => {
                                        const toast = document.getElementById('cart-toast');
                                        document.getElementById('cart-toast-msg').textContent = (e.detail.nombre || 'Producto') + ' agregado al carrito';
                                        toast.style.display = 'block';
                                        setTimeout(() => { toast.style.display = 'none'; }, 3000);
                                    });
                                    </script>
                                    <!-- Begin Li's Pagination Area -->


                                    <div class="paginatoin-area">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <p>Showing 1-12 of 13 item(s)</p>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <ul class="pagination-box">
                                                    <li><a href="#" class="Previous"><i class="fa fa-chevron-left"></i> Previous</a>
                                                    </li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li>
                                                      <a href="#" class="Next"> Next <i class="fa fa-chevron-right"></i></a>
                                                    </li>
                                                </ul>
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

</div>{{-- fin raíz Livewire --}}
