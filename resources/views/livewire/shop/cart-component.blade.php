<div>
     {{-- para el icono se uso la version 5.0.0 --}}
    <a href="{{ route('cart') }}" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i>
    </a>
    {{-- Validacion al agregar productos al carrito. Posible modificacion --}}
    {{-- @auth
        {{ \Cart::session(auth()->id())->getContent()->count() }} contamos los productos diferentes en el carrito
        @else
        0
    @endauth --}}

    @php
        try {
            $cartCount = auth()->check() ? \Cart::session(auth()->id())->getContent()->count() : \Cart::getContent()->count();
        } catch (\Throwable $e) {
            $cartCount = 0;
        }
    @endphp
    {{ $cartCount }} {{-- contamos los productos diferentes en el carrito --}}
    {{-- {{ \Cart::session(auth()->id())->getTotalQuantity() }} total de productos en el cart --}}
</div>
