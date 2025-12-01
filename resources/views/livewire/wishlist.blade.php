<div>
    <!--Wishlist Area Strat-->
    <div class="wishlist-area pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="#">
                        <div class="table-content table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="li-product-remove">Eliminar</th>
                                        <th class="li-product-thumbnail">Imágenes</th>
                                        <th class="cart-product-name">Producto</th>
                                        <th class="li-product-price">Precio Unitario</th>
                                        <th class="li-product-stock-status">Estado Del Stock</th>
                                        <th class="li-product-add-cart">Añadir A La Cesta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Recorre los elementos de la lista de deseos (cada item tiene relación 'producto') --}}
                                    @forelse ($items as $wish)
                                    @php $producto = $wish->producto; @endphp
                                    <tr>
                                        <td class="li-product-remove">
                                            <a href="#" wire:click.prevent="deleteItem({{ $wish->id }})" wire:target="deleteItem" role="button">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                        <td class="li-product-thumbnail">
                                            <a href="#">
                                                @if(isset($producto->cover_img) && $producto->cover_img)
                                                    <img src="{{ asset('storage/'.$producto->cover_img) }}" width="70" alt="{{ $producto->nombre }}">
                                                @else
                                                    <img src="{{ asset('images/default.jpg') }}" width="70" alt="Imagen no disponible">
                                                @endif
                                            </a>
                                        </td>
                                        <td class="li-product-name"><a href="#">{{ $producto->nombre ?? 'Sin nombre' }}</a></td>
                                        <td class="li-product-price"><span class="amount">BOB {{ number_format($producto->precio ?? 0, 2) }}</span></td>
                                        <td class="li-product-stock-status"><span class="in-stock">{{ $producto->stock ?? 'N/A' }}</span></td>
                                        <td class="li-product-add-cart">
                                            <a href="#" wire:click.prevent="agregarCarrito({{ $producto->id }})" wire:target="agregarCarrito" role="button">Añadir Al Carrito</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">Tu lista de deseos está vacía.</td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Wishlist Area End-->

</div>
