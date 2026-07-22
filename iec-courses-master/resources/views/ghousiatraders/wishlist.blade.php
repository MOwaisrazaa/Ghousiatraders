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
                        <span id="wishlistItemsCountText">8 Items</span>
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
                
                <!-- Wishlist Toolbar Actions -->
                <div class="wishlist-toolbar">
                    <div class="toolbar-left">
                        <label class="custom-checkbox-container">
                            <input type="checkbox" id="selectAllWishlist" checked>
                            <span class="checkmark"></span>
                            <span class="label-text" id="selectAllLabel">Select All (8)</span>
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
                    
                    <!-- Item 1 -->
                    <div class="wishlist-card" data-product-id="1">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/baby_lotion.png') }}" alt="Johnson's Baby Lotion" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Johnson's Baby Lotion</h3>
                            <span class="wishlist-card-spec">500ml</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 1,250</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="wishlist-card" data-product-id="2">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/sports_car_yellow.png') }}" alt="Mercedes B/O Car (AMG)" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Mercedes B/O Car (AMG)</h3>
                            <span class="wishlist-card-spec">Premium White Edition</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 29,999</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="wishlist-card" data-product-id="3">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/sippy_cup.png') }}" alt="Premium Sippy Cup" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Premium Sippy Cup</h3>
                            <span class="wishlist-card-spec">260ml</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 750</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4 -->
                    <div class="wishlist-card" data-product-id="4">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/baby_wipes.png') }}" alt="Baby Wipes" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Baby Wipes</h3>
                            <span class="wishlist-card-spec">80 Pcs</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 450</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 5 -->
                    <div class="wishlist-card" data-product-id="5">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/sport_bike.png') }}" alt="Sports B/O Bike" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Sports B/O Bike</h3>
                            <span class="wishlist-card-spec">(Rechargeable)</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 24,999</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 6 -->
                    <div class="wishlist-card" data-product-id="6">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/toy_jeep.png') }}" alt="Jeep Wrangler B/O Car" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Jeep Wrangler B/O Car</h3>
                            <span class="wishlist-card-spec">(4WD)</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 34,999</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 7 -->
                    <div class="wishlist-card" data-product-id="7">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/baby_products.png') }}" alt="Baby Diapers - Small" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Baby Diapers - Small</h3>
                            <span class="wishlist-card-spec">20 Pcs</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 1,150</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Item 8 -->
                    <div class="wishlist-card" data-product-id="8">
                        <div class="wishlist-card-header">
                            <label class="custom-checkbox-container card-select">
                                <input type="checkbox" class="wishlist-item-checkbox" checked>
                                <span class="checkmark"></span>
                            </label>
                            <button class="wishlist-heart-action active" aria-label="Remove from Wishlist">
                                <i data-lucide="heart" fill="currentColor"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-img-wrapper">
                            <img src="{{ asset('ghousiatraders/assets/baby_shampoo.png') }}" alt="Johnson's Baby Shampoo" class="wishlist-card-img">
                        </div>
                        <div class="wishlist-card-details">
                            <h3 class="wishlist-card-title">Johnson's Baby Shampoo</h3>
                            <span class="wishlist-card-spec">500ml</span>
                            <div class="wishlist-card-price-row">
                                <span class="wishlist-card-price">PKR 850</span>
                                <span class="stock-badge in-stock">In Stock</span>
                            </div>
                            <div class="wishlist-card-actions">
                                <button class="btn-primary wishlist-add-to-cart">Add to Cart</button>
                                <button class="btn-delete-item" aria-label="Delete Item"><i data-lucide="trash-2"></i></button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Empty State (Hidden initially) -->
                <div class="wishlist-empty-state" id="wishlistEmptyState" style="display: none;">
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
