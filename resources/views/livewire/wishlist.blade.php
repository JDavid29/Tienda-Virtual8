<div>
    <div class="wishlist-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="wishlist-table-wrapper table-responsive">
                        <table class="table wishlist-table mb-0">
                            <thead>
                                <tr>
                                    <th class="li-product-remove">Eliminar</th>
                                    <th class="li-product-thumbnail">Imagen</th>
                                    <th class="cart-product-name">Producto</th>
                                    <th class="li-product-price">Precio</th>
                                    <th class="li-product-stock-status hide-xs">Stock</th>
                                    <th class="li-product-add-cart">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $wish)
                                    @php $producto = $wish->producto; @endphp
                                    <tr>
                                        <td class="li-product-remove">
                                            <a href="#"
                                               wire:click.prevent="deleteItem({{ $wish->id }})"
                                               wire:loading.attr="disabled"
                                               wire:target="deleteItem({{ $wish->id }})"
                                               title="Eliminar">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>

                                        <td class="li-product-thumbnail">
                                            <a href="#">
                                                @include('partials.product-image', [
                                                    'image'         => $producto->cover_img ?? null,
                                                    'alt'           => $producto->nombre ?? 'Producto',
                                                    'default'       => 'images/default.jpg',
                                                    'attributesHtml' => 'width="70" height="70"',
                                                ])
                                            </a>
                                        </td>

                                        <td class="li-product-name">
                                            <a href="#">{{ $producto->nombre ?? 'Sin nombre' }}</a>
                                        </td>

                                        <td class="li-product-price">
                                            <span class="amount">BOB {{ number_format($producto->precio ?? 0, 2) }}</span>
                                        </td>

                                        <td class="li-product-stock-status hide-xs">
                                            @php $cantidad = (int) ($producto->cantidad ?? 0); @endphp
                                            @if ($cantidad > 0)
                                                <span class="badge-stock badge-in-stock">En stock ({{ $cantidad }})</span>
                                            @else
                                                <span class="badge-stock badge-out-stock">Sin stock</span>
                                            @endif
                                        </td>

                                        <td class="li-product-add-cart">
                                            <a href="#"
                                               wire:click.prevent="agregarCarrito({{ $producto->id }})"
                                               wire:loading.attr="disabled"
                                               wire:target="agregarCarrito({{ $producto->id }})">
                                                Añadir al carrito
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="wishlist-empty">
                                            <i class="fa fa-heart-o"></i>
                                            Tu lista de deseos está vacía.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
