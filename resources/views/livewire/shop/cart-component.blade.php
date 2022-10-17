<div>
    <a href="{{ route('cart') }}" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i> {{-- para el icono se uso la version 5.0.0 --}}
    </a>
    {{-- Validacion al agregar productos al carrito. Posible modificacion --}}
    {{-- @auth
        {{ \Cart::session(auth()->id())->getContent()->count() }} contamos los productos diferentes en el carrito
        @else
        0
    @endauth --}}

    {{ \Cart::session(auth()->id())->getContent()->count() }} {{-- contamos los productos diferentes en el carrito --}}
    {{-- {{ \Cart::session(auth()->id())->getTotalQuantity() }} total de productos en el cart --}}
</div>
