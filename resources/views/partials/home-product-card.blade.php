<div class="col-lg-12">
    <div class="single-product-wrap">
        <div class="product-image">
            <a href="{{ route('single-product', $prod->id) }}">
                @include('partials.product-image', [
                    'image'         => $prod->cover_img ?? null,
                    'alt'           => $prod->nombre,
                    'default'       => 'images/default.jpg',
                    'attributesHtml'=> ''
                ])
            </a>
            <span class="sticker">Nuevo</span>
        </div>
        <div class="product_desc">
            <div class="product_desc_info">
                <h4>
                    <a class="product_name" href="{{ route('single-product', $prod->id) }}">
                        {{ Str::limit($prod->nombre, 55) }}
                    </a>
                </h4>
                <div class="price-box">
                    <span class="new-price">Bs{{ number_format($prod->precio, 2) }}</span>
                </div>
            </div>
            <div class="add-actions">
                <ul class="add-actions-link">
                    <li class="add-cart active">
                        <a href="#" wire:click.prevent="agregarCarrito({{ $prod->id }})">Añadir</a>
                    </li>
                    <li>
                        <a href="{{ route('single-product', $prod->id) }}" title="Ver detalle">
                            <i class="fa fa-eye"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wishlist') }}" title="Lista de deseos">
                            <i class="fa fa-heart-o"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
