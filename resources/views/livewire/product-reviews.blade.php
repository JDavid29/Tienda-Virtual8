<div>
    <div class="product-details-comment-block">
        <div class="comment-review">
            <span>Calificación</span>
            <ul class="rating">
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li class="no-star"><i class="fa fa-star-o"></i></li>
                <li class="no-star"><i class="fa fa-star-o"></i></li>
            </ul>
        </div>

        <div class="comment-author-infos pt-25">
            @if($reviews && $reviews->count())
                <span>{{ $reviews->first()->user->name ?? 'Usuario' }}</span>
                <em>{{ $reviews->first()->created_at->format('d-m-Y') }}</em>
            @else
                <span>No hay reseñas aún</span>
            @endif
        </div>

        {{-- Mostrar la reseña del usuario actual primero, si existe --}}
        @if(isset($userReview) && $userReview)
            <div class="user-review mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>Tú</strong>
                        <div class="small text-muted">{{ $userReview->created_at->format('d M Y') }}</div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click.prevent="startEdit">Editar reseña</button>
                        <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="if(confirm('¿Eliminar tu reseña? Esta acción no se puede deshacer.')) { Livewire.emit('deleteReview'); }">Eliminar</button>
                    </div>
                </div>
                <h5 class="mt-2 mb-1">{{ $userReview->titulo_opinion ?? 'Sin título' }}</h5>
                <p class="mb-1">{{ $userReview->comentario }}</p>
                <p class="mb-0">Calificación:
                    @for($i=1;$i<=5;$i++)
                        @if($i <= $userReview->calificacion)
                            <i class="fa fa-star" style="color:#f1c40f"></i>
                        @else
                            <i class="fa fa-star-o"></i>
                        @endif
                    @endfor
                </p>
            </div>
        @endif

        <div class="comment-details">
            @forelse($reviews ?? [] as $review)
                <div class="single-review">
                    <h4 class="title-block">{{ $review->titulo_opinion ?? 'Sin título' }}</h4>
                    <p class="meta">Por <strong>{{ $review->user->name ?? 'Usuario' }}</strong> · <small>{{ $review->created_at->format('d M Y') }}</small></p>
                    <p>{{ $review->comentario }}</p>
                    <p>Calificación:
                        @for($i=1;$i<=5;$i++)
                            @if($i <= $review->calificacion)
                                <i class="fa fa-star" style="color:#f1c40f"></i>
                            @else
                                <i class="fa fa-star-o"></i>
                            @endif
                        @endfor
                    </p>
                    <hr>
                </div>
            @empty
                <p class="text-muted">Aún no hay reseñas de otros usuarios para este producto.</p>
            @endforelse
        </div>

        <div class="review-btn">
            <a class="review-links" href="#" wire:click.prevent="startCreate">¡ESCRIBE TU RESEÑA!</a>
        </div>

        <!-- Review Modal -->
            <div class="modal fade" id="productReviewModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Escribe tu reseña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <a href="{{ $product && $product->cover_img ? (\Illuminate\Support\Str::startsWith($product->cover_img, ['http://','https://']) ? $product->cover_img : asset(ltrim($product->cover_img,'/'))) : asset('images/product/large-size/1.jpg') }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ $product && $product->cover_img ? (\Illuminate\Support\Str::startsWith($product->cover_img, ['http://','https://']) ? $product->cover_img : asset(ltrim($product->cover_img,'/'))) : asset('images/product/large-size/1.jpg') }}" alt="{{ $product->nombre ?? 'Product' }}" style="width:100%;height:auto;object-fit:cover;border-radius:4px;">
                                </a>
                                <div class="li-review-product-desc mt-2">
                                    <p class="li-product-name"><strong>{{ $product->nombre ?? '' }}</strong></p>
                                    <p class="small text-muted">{{ Illuminate\Support\Str::limit($product->descripcion ?? '', 180) }}</p>
                                </div>
                            </div>

                                    <div class="col-md-7">
                                <form id="reviewForm">
                                    <div class="form-group">
                                        <label for="rating">Tu calificación</label>
                                            <div class="rating-stars" aria-hidden="false">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" class="star-btn btn btn-link p-0" data-value="{{ $i }}" title="{{ $i }} estrella{{ $i>1? 's' : '' }}">
                                                        @if($rating >= $i)
                                                            <i class="fa fa-star" style="color:#f1c40f;font-size:1.4rem"></i>
                                                        @else
                                                            <i class="fa fa-star-o" style="font-size:1.4rem"></i>
                                                        @endif
                                                    </button>
                                                @endfor
                                            </div>
                                        @error('rating') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Título</label>
                                        <input id="title" type="text" class="form-control" wire:model.defer="title" placeholder="Resumen breve (opcional)">
                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">Tu comentario</label>
                                        <textarea id="comment" class="form-control" rows="5" wire:model.defer="comment" placeholder="Comparte tu experiencia..."></textarea>
                                        @error('comment') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cerrar</button>
                                        <button type="button" id="reviewSubmitBtn" class="btn btn-primary">@if($editing) Actualizar reseña @else Enviar reseña @endif</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('closeProductReviewModal', function(){
                try {
                    if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
                        $('#productReviewModal').modal('hide');
                    } else {
                        var m = document.getElementById('productReviewModal');
                        if (m) m.classList.remove('show');
                    }
                } catch (err) { console.error(err); }
            });

            window.addEventListener('openProductReviewModal', function(){
                try {
                    if (typeof $ !== 'undefined' && typeof $.fn.modal === 'function') {
                        $('#productReviewModal').modal('show');
                    } else {
                        var m = document.getElementById('productReviewModal');
                        if (m) m.classList.add('show');
                    }
                } catch (err) { console.error(err); }
            });

            // Client-side star control
            var selectedRating = null;
            function initStars(container) {
                var stars = container.querySelectorAll('.rating-stars .star-btn');
                // determine initial selection from server-rendered filled stars
                selectedRating = 0;
                stars.forEach(function(b, idx){
                    var i = idx + 1;
                    var icon = b.querySelector('i');
                    if (icon && icon.classList.contains('fa-star')) {
                        selectedRating = i > selectedRating ? i : selectedRating;
                    }
                });

                stars.forEach(function(b){
                    b.addEventListener('mouseover', function(){
                        var val = parseInt(b.getAttribute('data-value')) || 0;
                        highlightStars(stars, val);
                    });
                    b.addEventListener('mouseout', function(){
                        highlightStars(stars, selectedRating);
                    });
                    b.addEventListener('click', function(e){
                        e.preventDefault();
                        selectedRating = parseInt(b.getAttribute('data-value')) || 0;
                        highlightStars(stars, selectedRating);
                    });
                });
            }

            function highlightStars(stars, val) {
                stars.forEach(function(b, idx){
                    var icon = b.querySelector('i');
                    if (!icon) return;
                    if (idx < val) {
                        icon.classList.remove('fa-star-o'); icon.classList.add('fa-star'); icon.style.color = '#f1c40f';
                    } else {
                        icon.classList.remove('fa-star'); icon.classList.add('fa-star-o'); icon.style.color = '';
                    }
                });
            }

            // initialize stars on load
            var reviewModal = document.getElementById('productReviewModal');
            if (reviewModal) {
                initStars(reviewModal);
            }

            // when modal opens, re-init (Livewire may update DOM)
            window.addEventListener('openProductReviewModal', function(){
                setTimeout(function(){
                    var rm = document.getElementById('productReviewModal');
                    if (rm) initStars(rm);
                }, 50);
            });

            // submit: emit setRating then submitFromClient
            var submitBtn = document.getElementById('reviewSubmitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    var val = selectedRating || 5;
                    if (typeof Livewire !== 'undefined') {
                        Livewire.emit('setRating', val);
                        setTimeout(function(){ Livewire.emit('submitFromClient'); }, 80);
                    } else {
                        console.error('Livewire not available to submit review');
                    }
                });
            }
        });
    </script>

            <div class="mt-3">
                @if(isset($reviews))
                    {{ $reviews->links() }}
                @endif
            </div>
</div>
