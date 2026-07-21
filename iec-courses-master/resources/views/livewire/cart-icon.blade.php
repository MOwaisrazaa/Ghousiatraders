<div>
    @auth
    <a href="{{ route('shopping-cart') }}" class="btn btn-sm btn-primary mb-0 me-1">
        <i class="fas fa-shopping-cart me-2"></i>
        Cart
        <span class="badge bg-danger text-white ms-2">{{ $cartCount }}</span>
    </a>
    @else
    <a href="{{ route('sign-in') }}" class="btn btn-sm btn-primary mb-0 me-1">
        <i class="fas fa-shopping-cart me-2"></i>
        Cart
    </a>
    @endauth
</div>
