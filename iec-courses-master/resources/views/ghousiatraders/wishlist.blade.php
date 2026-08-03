@extends('ghousiatraders.layouts.app')

@section('title', 'My Wishlist — Ghousia Traders')

@section('content')
    <main class="wishlist-page-main">
        
        <!-- Wishlist Hero Banner -->
        <section class="section-container wishlist-hero">
            <div class="wishlist-hero-card">
                <div class="wishlist-hero-content">
                    @include('ghousiatraders.components.breadcrumb', [
                        'current' => 'Wishlist'
                    ])
                    <h1 class="wishlist-title">My Wishlist</h1>
                    <p class="wishlist-subtitle">Save your favorite items and buy them later.</p>
                    <div class="wishlist-count-badge">
                        <i class="fas fa-heart"></i>
                        <span id="wishlistItemsCountText">{{ count($products) }} {{ count($products) === 1 ? 'Item' : 'Items' }}</span>
                    </div>
                </div>
                <div class="wishlist-hero-image-wrapper">
                    <img src="{{ asset('ghousiatraders/assets/shop_hero.png') }}" alt="My Wishlist" class="wishlist-hero-image">
                </div>
            </div>
        </section>

        <!-- Wishlist Main Grid Area -->
        <section class="section-container wishlist-grid-section">
            <div class="wishlist-inner-container">
                
                @if(count($products) > 0)
                    <!-- Wishlist Grid -->
                    <div class="wishlist-grid" id="wishlistGrid">
                        @foreach($products as $product)
                            @php
                                $img = $product->image_path ? asset($product->image_path) : asset('ghousiatraders/assets/baby_products.png');
                                $price = $product->weekly_price;
                                $slug = $product->slug;
                            @endphp
                            <div class="wishlist-card product-card" data-product-id="{{ $product->id }}" data-product-slug="{{ $slug }}">
                                <div class="wishlist-card-header">
                                    <span class="stock-badge in-stock">In Stock</span>
                                    <button class="wishlist-heart-action active" aria-label="Remove from Wishlist" data-product-slug="{{ $slug }}" data-product-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="wishlist-card-img-wrapper">
                                    <a href="{{ route('polani.product', $slug) }}">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" class="wishlist-card-img">
                                    </a>
                                </div>
                                <div class="wishlist-card-details">
                                    <a href="{{ route('polani.product', $slug) }}" style="text-decoration: none; color: inherit;">
                                        <h3 class="wishlist-card-title product-name">{{ $product->name }}</h3>
                                    </a>
                                    <span class="wishlist-card-spec">Ride-On Toy / Baby Care</span>
                                    <div class="wishlist-card-price-row">
                                        <span class="wishlist-card-price product-price">PKR {{ number_format($price) }}</span>
                                    </div>
                                    <div class="wishlist-card-actions">
                                        <button class="btn btn-primary wishlist-add-to-cart action-cart" 
                                                data-add-to-cart 
                                                data-add-url="{{ route('polani.cart.add', ['slug' => $slug]) }}" 
                                                data-name="{{ $product->name }}"
                                                style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                            <i class="fas fa-shopping-bag" style="font-size:0.85rem;"></i>
                                            <span>Add to Cart</span>
                                        </button>
                                        <button class="btn-delete-item btn-delete-wishlist-item" aria-label="Delete Item" data-product-slug="{{ $slug }}" data-product-id="{{ $product->id }}" data-name="{{ $product->name }}" title="Remove from Wishlist">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Empty State (Displayed if empty or after clearing items) -->
                <div class="wishlist-empty-state" id="wishlistEmptyState" style="display: {{ count($products) == 0 ? 'flex' : 'none' }};">
                    <div class="empty-icon-wrapper" style="width: 80px; height: 80px; border-radius: 50%; background-color: #FFF8EE; color: #D7A64A; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; font-size: 2rem;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h2 style="font-family: var(--font-heading); font-size: 1.8rem; color: #351B0D; margin-bottom: 10px; font-weight: 800;">Your wishlist is empty</h2>
                    <p style="color: #6B7280; margin-bottom: 25px; max-width: 420px; font-size: 0.98rem; line-height: 1.5;">Save your favorite items here to purchase them later.</p>
                    <a href="{{ route('polani.collection') }}" class="btn btn-primary btn-shop-now" style="text-decoration: none; padding: 12px 28px; font-weight: 700;">Go to Shop</a>
                </div>

            </div>
        </section>

        <!-- Trust Benefits Strip -->
        <section class="feature-bar-section">
            <div class="section-container feature-bar-container">
                <div class="feature-bar-grid">
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i class="fas fa-award"></i></div>
                        <div class="f-bar-content"><h4>100% Genuine Products</h4><p>Original and high quality</p></div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i class="fas fa-truck"></i></div>
                        <div class="f-bar-content"><h4>Fast Delivery</h4><p>Across Pakistan</p></div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i class="fas fa-redo-alt"></i></div>
                        <div class="f-bar-content"><h4>Easy Returns</h4><p>Within 7 Days</p></div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i class="fas fa-shield-alt"></i></div>
                        <div class="f-bar-content"><h4>Secure Payments</h4><p>Safe & reliable</p></div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
