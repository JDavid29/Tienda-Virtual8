<div>
    <!--Shopping Cart Area Strat-->
    <div class="Shopping-cart-area pt-60 pb-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="#">
                        <div class="table-content table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="li-product-remove">Eliminar</th>
                                        <th class="li-product-thumbnail">Imagenes</th>
                                        <th class="cart-product-name">Producto</th>
                                        <th class="li-product-price">Precio Unitario</th>
                                        <th class="li-product-quantity">Cantidad</th>
                                        <th class="li-product-subtotal">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartItems as $index => $item)
                                    <tr>
                                        <td class="li-product-remove"><a href="#" wire:click.prevent="deleteItem({{ $item["id"] }})" wire:target="deleteItem" role="button"><i class="fa fa-times"></i></a></td>
                                        {{-- visualizacion de la imagen en carrito --}}
                                        <td class="li-product-thumbnail">
                                            @php
                                                // pick first available image from possible attribute shapes
                                                $attrs = $item['attributes'] ?? [];
                                                $img = null;
                                                if (is_array($attrs)) {
                                                    $img = $attrs['image'] ?? ($attrs[0]['image'] ?? ($attrs['cover_img'] ?? null));
                                                } elseif (is_object($attrs)) {
                                                    $img = $attrs->image ?? ($attrs->cover_img ?? null);
                                                }
                                            @endphp
                                            @if(! empty($img))
                                                @php
                                                    if (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])) {
                                                        $src = $img;
                                                    } elseif (\Illuminate\Support\Str::startsWith($img, ['/storage/', '/'])) {
                                                        $src = asset(ltrim($img, '/'));
                                                    } else {
                                                        $src = asset('storage/' . ltrim($img, '/'));
                                                    }
                                                @endphp
                                                <img src="{{ $src }}" width="70" alt="{{ $item['name'] }}">
                                            @else
                                                <img src="{{ asset('images/default.png') }}" width="70" alt="Imagen no disponible">
                                            @endif
                                        </td>
                                        <td class="li-product-name"><a href="#">{{ \Illuminate\Support\Str::limit($item['name'], 60, '...') }}</a></td>
                                        <td class="li-product-price"><span class="amount">Bs {{ number_format($item['price'], 2) }}</span></td>
                                        <td class="quantity">
                                            <label>Cantidad</label>
                                            <div class="">
                                                <input class=""
                                                type="number" wire:model.lazy="cartItems.{{ $index }}.quantity" wire:change="updateQuantity('{{ $item['id'] }}', $event.target.value)" min="1">
                                                {{-- <div class="dec qtybutton" wire:click="updateQuantity({{ $item['id'] }})"><i class="fa fa-angle-down"></i></div>
                                                <div class="inc qtybutton" wire:click="updateQuantity({{ $item['id'] }})"><i class="fa fa-angle-up"></i></div> --}}
                                            </div>
                                            {{-- <div class="cart-plus-minus">
                                                <input type="number" min="1"
                                                wire:model.live="cartItems.{{ $index }}.quantity"
                                                wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                                class="form-control w-75">
                                            </div> --}}
                                        </td>
                                        <td class="product-subtotal"><span class="amount">BOB {{ number_format($item['price'] * $item['quantity'], 2) }}</span></td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="coupon-all">
                                    <div class="coupon">
                                        <input id="coupon_code" wire:model.defer="couponCode" class="input-text" name="coupon_code" value="" placeholder="Código de cupón" type="text">
                                        {{-- <input class="button" name="apply_coupon" value="Aplicar cupon" type="submit"> --}}
                                        <button type="button" wire:click="applyCoupon" class="btn btn-primary ml-2">Aplicar cupón</button>
                                    </div>
                                    <div class="coupon2">
                                        {{-- <input class="button" name="update_cart" value="Actualizar carrito" type="submit"> --}}
                                        <button wire:click="updateCart" class="btn btn-secondary">Actualizar carrito</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 ml-auto">
                                <div class="cart-page-total">
                                    <h2>Totales Del Carrito</h2>
                                    <ul>
                                        <li>Total Parcial <span>BOB. {{ number_format($subtotal, 2) }}</span></li>
                                        <li>Descuento <span>BOB. {{ number_format($discount, 2) }}</span></li>
                                        <li>Total <span>BOB. {{ number_format($cartTotal, 2) }}</span></li>
                                    </ul>
                                    <br>
                                    @if(auth()->check())
                                        <a href="{{ route('verificar') }}">Proceder al pago</a>
                                    @else
                                        <div class="d-flex">
                                            <a href="{{ route('login.client') }}" class="btn btn-primary mr-2">Iniciar sesión</a>
                                            <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Shopping Cart Area End-->

</div>
