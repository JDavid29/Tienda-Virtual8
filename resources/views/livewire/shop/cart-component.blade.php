<div>
    <a href="{{ route('cart') }}" class="btn btn-primary position-relative">
        <i class="fas fa-shopping-cart"></i>
        @if($cartCount > 0)
            <span class="badge badge-danger position-absolute"
                style="top:-6px;right:-6px;font-size:10px;padding:3px 6px;border-radius:50%;">
                {{ $cartCount }}
            </span>
        @endif
    </a>
</div>
