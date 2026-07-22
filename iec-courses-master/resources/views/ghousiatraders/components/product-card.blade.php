@props(['product'])

@php
    // Safe extraction of fields whether product is passed as array or Eloquent model
    if (is_array($product)) {
        $slug = $product['slug'] ?? $product['id'] ?? '';
        $name = $product['name'] ?? '';
        $price = $product['price'] ?? 0;
        $image = $product['image'] ?? asset('ghousiatraders/assets/baby_products.png');
        $tag = $product['badge'] ?? null;
    } else {
        $slug = $product->slug;
        $name = $product->name;
        $price = $product->weekly_price; // Mapped from database column
        $image = $product->image_path ? asset($product->image_path) : asset('ghousiatraders/assets/baby_products.png');
        
        // Custom badge assignment based on price thresholds or tags
        $tag = null;
        if ($price > 20000) {
            $tag = 'Best Seller';
        } elseif ($price < 1000) {
            $tag = 'New';
        }
    }

    $detailUrl = route('polani.product', ['slug' => $slug]);
    $addCartUrl = route('polani.cart.add', ['slug' => $slug]);
@endphp

<div class="product-card">
    @if($tag)
        <span class="product-tag {{ strtolower($tag) === 'best seller' ? 'tag-best' : 'tag-new' }}">
            {{ $tag }}
        </span>
    @endif
    
    <div class="product-img-wrapper">
        <a href="{{ $detailUrl }}">
            <img src="{{ $image }}" alt="{{ $name }}" loading="lazy">
        </a>
    </div>
    
    <div class="product-details">
        <h3 class="product-name">
            <a href="{{ $detailUrl }}" style="color: inherit; text-decoration: none;">
                {{ $name }}
            </a>
        </h3>
        <div class="product-footer">
            <span class="product-price">
                PKR {{ number_format($price) }}
            </span>
            <div class="product-card-actions">
                <button class="card-action-btn action-wishlist" aria-label="Add to Wishlist" title="Add to Wishlist" data-product-slug="{{ $slug }}">
                    <i data-lucide="heart"></i>
                </button>
                <button class="add-to-cart-btn card-action-btn action-cart" 
                        aria-label="Add to Cart" 
                        title="Add to Cart" 
                        data-add-to-cart
                        data-add-url="{{ $addCartUrl }}"
                        data-name="{{ $name }}">
                    <i data-lucide="shopping-cart"></i>
                </button>
            </div>
        </div>
    </div>
</div>
