@extends('ghousiatraders.layouts.app')

@section('title', 'My Wishlist — Ghousia Traders')

@section('content')
    <main class="main-content">
        
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
                        <i data-lucide="heart" fill="currentColor"></i>
                        <span id="wishlistItemsCountText">{{ count($products) }} Items</span>
                    </div>
                </div>
                <div class="wishlist-hero-image-wrapper">
                    <img src="{{ asset('ghousiatraders/assets/wishlist_hero.png') }}" alt="My Wishlist Items" class="wishlist-hero-image">
                </div>
            </div>
        </section>

        <!-- Wishlist Main Grid Area -->
        <section class="section-container wishlist-grid-section">
            <div class="wishlist-inner-container">
                
                @if(count($products) > 0)
                    <!-- Wishlist Toolbar Actions -->
                    <div class="wishlist-toolbar">
                        <div class="toolbar-left">
                            <label class="custom-checkbox-container">
                                <input type="checkbox" id="selectAllWishlist" checked>
                                <span class="checkmark"></span>
                                <span class="label-text" id="selectAllLabel">Select All ({{ count($products) }})</span>
                            </label>
                        </div>
                        <div class="toolbar-right">
                            <button class="toolbar-btn btn-share" id="shareWishlistBtn">
                                <i data-lucide="share-2"></i>
                                <span>Share Wishlist</span>
                            </button>
                            <button class="toolbar-btn btn-clear" id="clearWishlistBtn">
                                <i data-lucide="trash-2"></i>
                                <span>Clear Wishlist</span>
                            </button>
                        </div>
                    </div>

                    <!-- Wishlist Grid -->
                    <div class="wishlist-grid" id="wishlistGrid">
                        @foreach($products as $product)
                            @php
                                $img = $product->image_path ? asset($product->image_path) : asset('ghousiatraders/assets/baby_products.png');
                                $price = $product->weekly_price;
                                $slug = $product->slug;
                            @endphp
                            <div class="wishlist-card" data-product-id="{{ $product->id }}" data-product-slug="{{ $slug }}">
                                <div class="wishlist-card-header">
                                    <label class="custom-checkbox-container card-select">
                                        <input type="checkbox" class="wishlist-item-checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                    <button class="wishlist-heart-action active" aria-label="Remove from Wishlist" data-product-slug="{{ $slug }}">
                                        <i data-lucide="heart" fill="currentColor"></i>
                                    </button>
                                </div>
                                <div class="wishlist-card-img-wrapper">
                                    <img src="{{ $img }}" alt="{{ $product->name }}" class="wishlist-card-img">
                                </div>
                                <div class="wishlist-card-details">
                                    <h3 class="wishlist-card-title">{{ $product->name }}</h3>
                                    <span class="wishlist-card-spec">Ride-On Toy / Baby Care</span>
                                    <div class="wishlist-card-price-row">
                                        <span class="wishlist-card-price">PKR {{ number_format($price) }}</span>
                                        <span class="stock-badge in-stock">In Stock</span>
                                    </div>
                                    <div class="wishlist-card-actions">
                                        <button class="btn-primary wishlist-add-to-cart action-cart" 
                                                data-add-to-cart 
                                                data-add-url="{{ route('polani.cart.add', ['slug' => $slug]) }}" 
                                                data-name="{{ $product->name }}">Add to Cart</button>
                                        <button class="btn-delete-item" aria-label="Delete Item" data-product-slug="{{ $slug }}"><i data-lucide="trash-2"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Empty State (Hidden initially if items exist) -->
                <div class="wishlist-empty-state" id="wishlistEmptyState" style="display: {{ count($products) == 0 ? 'block' : 'none' }};">
                    <div class="empty-icon-wrapper">
                        <i data-lucide="heart"></i>
                    </div>
                    <h2>Your wishlist is empty</h2>
                    <p>Save your favorite items here to purchase them later.</p>
                    <a href="{{ route('polani.collection') }}" class="btn-primary btn-shop-now" style="text-decoration: none;">Go to Shop</a>
                </div>

            </div>
        </section>

        <!-- 8. Features Highlight -->
        <section class="features-highlight">
            <div class="features-container">
                <div class="feature-item">
                    <div class="feature-icon-box"><i data-lucide="award"></i></div>
                    <div class="feature-text">
                        <h4>100% Genuine Products</h4>
                        <p>Original and high quality</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i data-lucide="truck"></i></div>
                    <div class="feature-text">
                        <h4>Fast Delivery</h4>
                        <p>Across Pakistan</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i data-lucide="rotate-ccw"></i></div>
                    <div class="feature-text">
                        <h4>Easy Returns</h4>
                        <p>Within 7 Days</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon-box"><i data-lucide="shield-check"></i></div>
                    <div class="feature-text">
                        <h4>Secure Payments</h4>
                        <p>Safe & reliable</p>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
