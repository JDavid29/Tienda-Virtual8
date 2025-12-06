<div>
    <div wire:ignore.self class="modal fade modal-wrapper" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-inner-area row">
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <div class="product-details-left">
                                <div class="product-details-images slider-navigation-1">
                                    @if($product && $product->cover_img)
                                        <div class="lg-image">
                                                @include('partials.product-image', [
                                                    'image' => $product->cover_img ?? null,
                                                    'alt' => $product->nombre ?? 'Producto',
                                                    'default' => 'images/product/large-size/1.jpg',
                                                    'attributesHtml' => ''
                                                ])
                                        </div>
                                    @else
                                        <div class="lg-image">
                                            <img src="{{ asset('images/default.jpg') }}" alt="{{ $product->nombre ?? 'Producto' }}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-6 col-sm-6">
                            <div class="product-details-view-content pt-60">
                                <div class="product-info">
                                    <h2>{{ $product->nombre ?? 'Sin información' }}</h2>
                                    @if($product)
                                        <span class="product-details-ref">Referencia: {{ $product->id }}</span>
                                        <div class="rating-box pt-20">
                                            <ul class="rating rating-with-review-item">
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                <li class="review-item"><a href="#">Leer reseña</a></li>
                                                <li class="review-item"><a href="#">Escribir reseña</a></li>
                                            </ul>
                                        </div>
                                        <div class="price-box pt-20">
                                            <span class="new-price new-price-2">BOB {{ number_format($product->precio ?? 0, 2) }}</span>
                                        </div>
                                        <div class="product-desc">
                                            <p>
                                                <span>{{ $product->descripcion ?? '' }}</span>
                                            </p>
                                        </div>
                                    @else
                                        <div class="price-box pt-20">
                                            <span class="new-price new-price-2">-</span>
                                        </div>
                                        <div class="product-desc"><p>Producto no encontrado.</p></div>
                                    @endif

                                    <div class="single-add-to-cart">
                                        <form action="#" class="cart-quantity">
                                            <div class="quantity">
                                                <label>Cantidad</label>
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" type="number" min="1" step="1" wire:model.defer="quantity">
                                                    <div class="dec qtybutton"><a href="#" wire:click.prevent="decreaseQuantity"><i class="fa fa-angle-down"></i></a></div>
                                                    <div class="inc qtybutton"><a href="#" wire:click.prevent="increaseQuantity"><i class="fa fa-angle-up"></i></a></div>
                                                </div>
                                            </div>
                                            <button class="add-to-cart" type="button" wire:click.prevent="addToCart">Añadir al carrito</button>
                                        </form>
                                    </div>
                                    {{-- lista de deseos y redes sociales --}}
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            window.addEventListener('openQuickViewModal', function(){
                try{
                    $('#exampleModalCenter').modal('show');
                }catch(e){
                    // fallback: use vanilla if jQuery not available
                    var modal = document.getElementById('exampleModalCenter');
                    if(modal){
                        modal.classList.add('show');
                        modal.style.display = 'block';
                        // accessibility: mark as visible
                        modal.setAttribute('aria-hidden', 'false');
                        modal.setAttribute('aria-modal', 'true');
                        // move focus to close button
                        var btn = modal.querySelector('button.close');
                        if(btn) btn.focus();
                    }
                }
            });

            // When modal hides, tell Livewire to clear product if desired (optional)
            document.addEventListener('livewire:load', function(){
                var m = document.getElementById('exampleModalCenter');
                if(m){
                    // If Bootstrap triggers hidden.bs.modal, that's fine. Also handle manual close in fallback.
                    m.addEventListener('hidden.bs.modal', function(){
                        // ensure aria attributes reset
                        m.setAttribute('aria-hidden', 'true');
                        m.removeAttribute('aria-modal');
                    });
                    var closeBtn = m.querySelector('button.close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function(){
                            // fallback hide behavior: remove show and hide
                            if (typeof $ === 'undefined' || !($.fn && $.fn.modal)) {
                                m.classList.remove('show');
                                m.style.display = 'none';
                                m.setAttribute('aria-hidden', 'true');
                                m.removeAttribute('aria-modal');
                            }
                        });
                    }
                }
            });
        })();
    </script>
</div>
