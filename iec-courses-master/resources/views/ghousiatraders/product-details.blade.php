@extends('ghousiatraders.layouts.app')

@section('title', $product['name'] . ' — Ghousia Traders')

@push('head')
    <link rel="stylesheet" href="{{ asset('ghousiatraders/assets/css/product-details.css') }}">
@endpush

@section('content')
<div class="product-detail-page">
    
    <!-- 1. Breadcrumb -->
    <div class="pdp-breadcrumb">
        <div class="section-container">
            <ul class="pdp-breadcrumb-list">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="separator">&gt;</li>
                <li><a href="{{ route('polani.collection') }}">Shop</a></li>
                <li class="separator">&gt;</li>
                <li><a href="{{ route('polani.collection', ['category' => $product['category_slug']]) }}">{{ $product['category_name'] }}</a></li>
                <li class="separator">&gt;</li>
                <li class="current">{{ $product['name'] }}</li>
            </ul>
        </div>
    </div>

    <!-- 2. Main Product Section (Two Columns: 52% / 48%) -->
    <section class="pdp-main-section">
        <div class="section-container">
            <div class="pdp-main-grid">
            
            <!-- Left Column: Product Gallery -->
            <div class="pdp-gallery-column">
                <div class="pdp-gallery-card">
                    @php
                        $badgeTag = null;
                        $displayPrice = $product['sale_price'] ?? $product['price'];
                        if ($displayPrice > 20000) {
                            $badgeTag = 'Best Seller';
                        } elseif ($product['sale_price'] && $product['sale_price'] < $product['price']) {
                            $badgeTag = 'Sale';
                        }
                    @endphp

                    @if($badgeTag)
                        <span class="pdp-badge badge-bestseller">{{ $badgeTag }}</span>
                    @endif

                    <button type="button" class="pdp-zoom-btn" id="pdpZoomBtn" title="Zoom Product Image" aria-label="Zoom Image">
                        <i data-lucide="search"></i>
                    </button>

                    <div class="pdp-main-image-wrap">
                        <img id="pdpMainImage" class="pdp-main-img" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="eager" onerror="this.src='{{ asset('ghousiatraders/assets/baby_products.png') }}'">
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Information -->
            <div class="pdp-info-column">
                <div class="pdp-info-card">
                    <h1 class="pdp-title">{{ $product['name'] }}</h1>

                    <div class="pdp-rating-row">
                        <div class="pdp-stars">
                            <i data-lucide="star"></i>
                            <i data-lucide="star"></i>
                            <i data-lucide="star"></i>
                            <i data-lucide="star"></i>
                            <i data-lucide="star"></i>
                        </div>
                        <span class="pdp-rating-score">({{ number_format($product['rating'] ?? 4.8, 1) }})</span>
                        <span class="pdp-rating-divider">|</span>
                        <a href="#pdpTabsSection" class="pdp-reviews-link" id="scrollToReviewsLink">128 Reviews</a>
                    </div>

                    <!-- Price Box -->
                    <div class="pdp-price-box">
                        @if($product['sale_price'] && $product['sale_price'] < $product['price'])
                            <span class="pdp-price">PKR {{ number_format($product['sale_price']) }}</span>
                            <span class="pdp-old-price">PKR {{ number_format($product['price']) }}</span>
                            <span class="pdp-save-badge">Save PKR {{ number_format($product['price'] - $product['sale_price']) }}</span>
                        @else
                            <span class="pdp-price">PKR {{ number_format($product['price']) }}</span>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @if($product['description'])
                        <p class="pdp-short-desc">
                            {{ $product['description'] }}
                        </p>
                    @endif

                    <!-- Stock & Meta Row -->
                    <div class="pdp-meta-row">
                        @php
                            $stockVal = (int) ($product['stock'] ?? 10);
                            $lowThreshold = (int) ($product['low_stock_threshold'] ?? 5);
                        @endphp
                        @if($stockVal <= 0)
                            <div class="pdp-stock-status out-of-stock" style="color: #dc2626; background: #fee2e2; font-weight:700; padding:4px 12px; border-radius:20px; display:inline-flex; align-items:center; gap:8px;">
                                <span class="stock-dot" style="background: #dc2626;"></span>
                                <span>Out of Stock</span>
                            </div>
                        @elseif($stockVal <= $lowThreshold)
                            <div class="pdp-stock-status low-stock" style="color: #d97706; background: #fef3c7; font-weight:700; padding:4px 12px; border-radius:20px; display:inline-flex; align-items:center; gap:8px;">
                                <span class="stock-dot" style="background: #d97706;"></span>
                                <span>Low Stock (Only {{ $stockVal }} left)</span>
                            </div>
                        @else
                            <div class="pdp-stock-status in-stock">
                                <span class="stock-dot"></span>
                                <span>In Stock</span>
                            </div>
                        @endif

                        <div class="pdp-sku-category">
                            @if($product['sku'])
                                <span class="meta-label">SKU:</span> <span class="meta-value">{{ $product['sku'] }}</span>
                                <span class="meta-divider">|</span>
                            @endif
                            <span class="meta-label">Category:</span> <a href="{{ route('polani.collection', ['category' => $product['category_slug']]) }}" class="meta-link">{{ $product['category_name'] }}</a>
                        </div>
                    </div>

                    <hr class="pdp-divider">

                    <!-- Variations (Dynamic if applicable for ride-on toys / cars) -->
                    @if(str_contains(strtolower($product['category_name']), 'car') || str_contains(strtolower($product['category_name']), 'bike') || str_contains(strtolower($product['name']), 'car') || str_contains(strtolower($product['name']), 'bike'))
                        <div class="pdp-variations">
                            <div class="variation-group">
                                <label class="variation-label">Color: <span class="selected-value" id="colorSelectedVal">White</span></label>
                                <div class="variation-options color-options">
                                    <button type="button" class="var-btn color-btn active" data-group="color" data-val="White">
                                        <span class="color-swatch swatch-white"></span>
                                        <span>White</span>
                                    </button>
                                    <button type="button" class="var-btn color-btn" data-group="color" data-val="Black">
                                        <span class="color-swatch swatch-black"></span>
                                        <span>Black</span>
                                    </button>
                                    <button type="button" class="var-btn color-btn" data-group="color" data-val="Red">
                                        <span class="color-swatch swatch-red"></span>
                                        <span>Red</span>
                                    </button>
                                </div>
                            </div>

                            <div class="variation-group">
                                <label class="variation-label">Age Group: <span class="selected-value" id="ageSelectedVal">2 - 4 Years</span></label>
                                <div class="variation-options">
                                    <button type="button" class="var-btn active" data-group="age" data-val="2 - 4 Years">2 - 4 Years</button>
                                    <button type="button" class="var-btn" data-group="age" data-val="4 - 6 Years">4 - 6 Years</button>
                                    <button type="button" class="var-btn" data-group="age" data-val="6+ Years">6+ Years</button>
                                </div>
                            </div>

                            <div class="variation-group">
                                <label class="variation-label">Battery Type: <span class="selected-value" id="batterySelectedVal">6V 4.5Ah</span></label>
                                <div class="variation-options">
                                    <button type="button" class="var-btn active" data-group="battery" data-val="6V 4.5Ah">6V 4.5Ah</button>
                                    <button type="button" class="var-btn" data-group="battery" data-val="12V 7Ah">12V 7Ah</button>
                                </div>
                            </div>
                        </div>
                        <hr class="pdp-divider">
                    @endif

                    <!-- Quantity & Purchasing Controls -->
                    <div class="pdp-purchase-actions">
                        <!-- Line 1: Quantity Selector -->
                        <div class="pdp-quantity-box">
                            <label class="qty-label" for="qtyInput">Quantity:</label>
                            <div class="qty-control">
                                <button type="button" class="qty-btn" id="qtyMinusBtn" aria-label="Decrease Quantity">&minus;</button>
                                <input type="number" id="qtyInput" value="1" min="1" max="{{ max(1, $stockVal) }}" readonly aria-label="Quantity">
                                <button type="button" class="qty-btn" id="qtyPlusBtn" aria-label="Increase Quantity">&plus;</button>
                            </div>
                        </div>

                        <!-- Line 2: Purchasing Buttons Row -->
                        <div class="pdp-buttons-row">
                            <button type="button" 
                                    class="btn-pdp-wishlist action-wishlist" 
                                    id="pdpWishlistToggle"
                                    data-product-slug="{{ $product['slug'] }}"
                                    data-name="{{ $product['name'] }}"
                                    title="Add to Wishlist"
                                    aria-label="Add to Wishlist">
                                <i data-lucide="heart" class="wishlist-icon"></i>
                                <span>Add to Wishlist</span>
                            </button>

                            <button type="button" 
                                    class="btn-pdp-cart add-to-cart-btn action-cart" 
                                    id="pdpAddToCartBtn"
                                    data-add-to-cart
                                    data-add-url="{{ route('polani.cart.add', ['slug' => $product['slug']]) }}"
                                    data-name="{{ $product['name'] }}"
                                    {{ $stockVal <= 0 ? 'disabled' : '' }}>
                                <i data-lucide="shopping-cart"></i>
                                <span>{{ $stockVal <= 0 ? 'Out of Stock' : 'Add to Cart' }}</span>
                            </button>
                        </div>
                    </div>

                    <hr class="pdp-divider">

                    <!-- Compact Purchase Trust Strip -->
                    <div class="pdp-trust-strip">
                        <div class="trust-item">
                            <i data-lucide="truck"></i>
                            <div class="trust-text">
                                <strong>Fast Delivery</strong>
                                <span>Across Pakistan</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i data-lucide="rotate-ccw"></i>
                            <div class="trust-text">
                                <strong>7 Days Easy Returns</strong>
                                <span>Hassle-free</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i data-lucide="shield-check"></i>
                            <div class="trust-text">
                                <strong>Secure Payments</strong>
                                <span>100% Protected</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <i data-lucide="award"></i>
                            <div class="trust-text">
                                <strong>100% Genuine</strong>
                                <span>Premium Quality</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

    <!-- 3. Product Feature Strip -->
    <section class="pdp-feature-strip">
        <div class="section-container">
            <div class="feature-strip-grid">
                @if(str_contains(strtolower($product['category_name']), 'car') || str_contains(strtolower($product['category_name']), 'bike') || str_contains(strtolower($product['name']), 'car') || str_contains(strtolower($product['name']), 'bike'))
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="battery-charging"></i></div>
                        <div class="feature-info">
                            <h4>Rechargeable Battery</h4>
                            <p>Long Lasting Power</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="gamepad-2"></i></div>
                        <div class="feature-info">
                            <h4>Remote Control</h4>
                            <p>Parental Control</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="sparkles"></i></div>
                        <div class="feature-info">
                            <h4>LED Lights</h4>
                            <p>Bright &amp; Stylish</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="smile"></i></div>
                        <div class="feature-info">
                            <h4>Safe &amp; Child Friendly</h4>
                            <p>EN71 Certified</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="music"></i></div>
                        <div class="feature-info">
                            <h4>Music Player</h4>
                            <p>Built-in Fun</p>
                        </div>
                    </div>
                @else
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="shield-check"></i></div>
                        <div class="feature-info">
                            <h4>Gentle Formula</h4>
                            <p>Dermatologically Tested</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="heart"></i></div>
                        <div class="feature-info">
                            <h4>Skin Protection</h4>
                            <p>Soothes &amp; Calms Skin</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="award"></i></div>
                        <div class="feature-info">
                            <h4>100% Genuine</h4>
                            <p>Original Imports</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="refresh-cw"></i></div>
                        <div class="feature-info">
                            <h4>Easy Exchanges</h4>
                            <p>7-Day Guarantee</p>
                        </div>
                    </div>
                    <div class="feature-strip-item">
                        <div class="feature-icon"><i data-lucide="truck"></i></div>
                        <div class="feature-info">
                            <h4>Nationwide Delivery</h4>
                            <p>Fast &amp; Safe Shipping</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 4. Product Tabs & Customer Reviews Layout Section -->
    <section class="pdp-content-reviews-grid" id="pdpTabsSection">
        <div class="section-container pdp-tabs-reviews-layout">
            
            <!-- Left Main Column: Tabbed Content -->
            <div class="pdp-tabs-container">
                <div class="pdp-tabs-header" role="tablist">
                    <button class="pdp-tab-btn active" role="tab" aria-selected="true" id="tab-btn-description" onclick="switchPdpTab('description')">
                        Description
                    </button>
                    <button class="pdp-tab-btn" role="tab" aria-selected="false" id="tab-btn-specifications" onclick="switchPdpTab('specifications')">
                        Specifications
                    </button>
                    <button class="pdp-tab-btn" role="tab" aria-selected="false" id="tab-btn-reviews" onclick="switchPdpTab('reviews')">
                        Reviews (128)
                    </button>
                    <button class="pdp-tab-btn" role="tab" aria-selected="false" id="tab-btn-shipping" onclick="switchPdpTab('shipping')">
                        Shipping &amp; Returns
                    </button>
                </div>

                <div class="pdp-tabs-body">
                    <!-- Tab 1: Description -->
                    <div class="pdp-tab-panel active" id="tab-description" role="tabpanel">
                        @if(!empty($product['long_description']))
                            <div class="tab-intro-text" style="font-size:0.95rem; line-height:1.8; color:#4A3B32;">
                                {!! htmlspecialchars_decode($product['long_description']) !!}
                            </div>
                        @else
                            <p class="tab-intro-text">
                                {{ $product['description'] ?: 'High quality product carefully curated for your family by Ghousia Traders.' }}
                            </p>
                        @endif

                        <ul class="pdp-feature-checklist" style="margin-top:20px;">
                            <li><i data-lucide="check-circle-2" class="check-icon"></i> <span>Premium quality materials ensuring durability &amp; safety</span></li>
                            <li><i data-lucide="check-circle-2" class="check-icon"></i> <span>Dermatologically tested and child safe</span></li>
                            <li><i data-lucide="check-circle-2" class="check-icon"></i> <span>Official import backed by Ghousia Traders warranty</span></li>
                        </ul>
                    </div>

                    <!-- Tab 2: Specifications -->
                    <div class="pdp-tab-panel" id="tab-specifications" role="tabpanel" style="display: none;">
                        <table class="pdp-specs-table">
                            <tbody>
                                <tr>
                                    <th>Product Name</th>
                                    <td>{{ $product['name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $product['category_name'] }}</td>
                                </tr>
                                @if($product['sku'])
                                    <tr>
                                        <th>SKU / Code</th>
                                        <td>{{ $product['sku'] }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Availability</th>
                                    <td>{{ $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' }}</td>
                                </tr>
                                <tr>
                                    <th>Free Shipping Threshold</th>
                                    <td>Orders above PKR {{ number_format(store_setting('shipping_free_threshold', 5000)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tab 3: Reviews Panel -->
                    <div class="pdp-tab-panel" id="tab-reviews" role="tabpanel" style="display: none;">
                        <div class="reviews-tab-content">
                            <div class="reviews-summary-box">
                                <div class="summary-score">4.8</div>
                                <div class="summary-stars">
                                    <i data-lucide="star" class="star-filled"></i>
                                    <i data-lucide="star" class="star-filled"></i>
                                    <i data-lucide="star" class="star-filled"></i>
                                    <i data-lucide="star" class="star-filled"></i>
                                    <i data-lucide="star" class="star-filled"></i>
                                    <span>Based on 128 Customer Reviews</span>
                                </div>
                            </div>

                            <div class="tab-reviews-list">
                                <div class="review-card-item">
                                    <div class="review-card-header">
                                        <div class="reviewer-avatar">HR</div>
                                        <div class="reviewer-meta">
                                            <h5>Hamza R. <span class="verified-badge"><i data-lucide="check"></i> Verified Purchase</span></h5>
                                            <div class="review-stars">
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                            </div>
                                        </div>
                                        <span class="review-date">May 12, 2024</span>
                                    </div>
                                    <p class="review-body">
                                        Excellent build quality! My kids love it. Fast delivery across Lahore within 2 days.
                                    </p>
                                </div>

                                <div class="review-card-item">
                                    <div class="review-card-header">
                                        <div class="reviewer-avatar">UK</div>
                                        <div class="reviewer-meta">
                                            <h5>Usman K. <span class="verified-badge"><i data-lucide="check"></i> Verified Purchase</span></h5>
                                            <div class="review-stars">
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                                <i data-lucide="star" class="star-filled"></i>
                                            </div>
                                        </div>
                                        <span class="review-date">Apr 28, 2024</span>
                                    </div>
                                    <p class="review-body">
                                        Really happy with the purchase. Smooth performance and genuine product packaging.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Shipping & Returns -->
                    <div class="pdp-tab-panel" id="tab-shipping" role="tabpanel" style="display: none;">
                        <div class="shipping-policy-content">
                            <h4><i data-lucide="truck"></i> Delivery Information</h4>
                            <p>We offer nationwide delivery across Pakistan via reliable courier services. Orders placed before 3:00 PM are processed on the same business day.</p>
                            <ul>
                                <li><strong>Major Cities (Lahore, Karachi, Islamabad, Rawalpindi):</strong> 2 – 4 Business Days</li>
                                <li><strong>Other Cities &amp; Regions:</strong> 3 – 5 Business Days</li>
                                <li><strong>Free Delivery:</strong> On all orders above PKR {{ number_format(store_setting('shipping_free_threshold', 5000)) }}</li>
                            </ul>

                            <h4><i data-lucide="rotate-ccw"></i> 7 Days Replacement &amp; Return Policy</h4>
                            <p>We want you to be 100% satisfied with your purchase. If the product arrives damaged or defective, we offer a hassle-free 7-day replacement guarantee.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Column: Customer Reviews Card (Matching Reference Image) -->
            <div class="pdp-reviews-sidebar-card">
                <div class="reviews-sidebar-header">
                    <h3>Customer Reviews</h3>
                    <a href="javascript:void(0)" class="view-all-reviews-link" onclick="switchPdpTab('reviews'); document.getElementById('pdpTabsSection').scrollIntoView({behavior:'smooth'});">View All</a>
                </div>

                <div class="reviews-sidebar-list">
                    <div class="sidebar-review-item">
                        <div class="review-avatar-row">
                            <div class="avatar-circle">HR</div>
                            <div class="avatar-info">
                                <strong class="user-name">Hamza R.</strong>
                                <span class="review-subdate">May 12, 2024</span>
                            </div>
                        </div>
                        <div class="sidebar-review-stars">
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                        </div>
                        <p class="sidebar-review-text">
                            “Excellent build quality! My son loves it. Battery backup is also great.”
                        </p>
                    </div>

                    <div class="sidebar-review-item">
                        <div class="review-avatar-row">
                            <div class="avatar-circle">UK</div>
                            <div class="avatar-info">
                                <strong class="user-name">Usman K.</strong>
                                <span class="review-subdate">Apr 28, 2024</span>
                            </div>
                        </div>
                        <div class="sidebar-review-stars">
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                        </div>
                        <p class="sidebar-review-text">
                            “Really happy with the purchase. Smooth ride and easy to assemble.”
                        </p>
                    </div>

                    <div class="sidebar-review-item">
                        <div class="review-avatar-row">
                            <div class="avatar-circle">AM</div>
                            <div class="avatar-info">
                                <strong class="user-name">Adeel M.</strong>
                                <span class="review-subdate">Apr 10, 2024</span>
                            </div>
                        </div>
                        <div class="sidebar-review-stars">
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                            <i data-lucide="star" class="star-filled"></i>
                        </div>
                        <p class="sidebar-review-text">
                            “Very stylish and premium look. Perfect gift for kids!”
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- 5. Related Products Section -->
    @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <section class="pdp-related-section">
            <div class="section-container">
                <div class="section-header-title" style="margin-bottom: 24px;">
                    <h2 class="section-title-serif" style="font-family: 'Lora', serif; font-size: 1.8rem; font-weight: 700; color: #351b0d;">Related Products</h2>
                </div>

                <div class="related-products-grid">
                    @foreach($relatedProducts as $relProduct)
                        @include('ghousiatraders.components.product-card', ['product' => $relProduct])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Thumbnail switching logic
        const mainImg = document.getElementById('pdpMainImage');
        const thumbBtns = document.querySelectorAll('.pdp-thumb-item');
        const prevBtn = document.getElementById('pdpGalleryPrev');
        const nextBtn = document.getElementById('pdpGalleryNext');

        if (thumbBtns.length > 0 && mainImg) {
            let currentIndex = 0;
            const images = Array.from(thumbBtns).map(btn => btn.getAttribute('data-img'));

            function setActiveThumb(idx) {
                thumbBtns.forEach((btn, i) => {
                    if (i === idx) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                if (images[idx]) {
                    mainImg.src = images[idx];
                }
            }

            thumbBtns.forEach((btn, idx) => {
                btn.addEventListener('click', () => {
                    currentIndex = idx;
                    setActiveThumb(currentIndex);
                });
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    setActiveThumb(currentIndex);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % images.length;
                    setActiveThumb(currentIndex);
                });
            }
        }

        // Variations button toggle
        document.querySelectorAll('.var-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.getAttribute('data-group');
                const val = this.getAttribute('data-val');
                if (group && val) {
                    const groupContainer = this.closest('.variation-group');
                    if (groupContainer) {
                        groupContainer.querySelectorAll('.var-btn').forEach(b => b.classList.remove('active'));
                    }
                    this.classList.add('active');
                    const targetValSpan = document.getElementById(group + 'SelectedVal');
                    if (targetValSpan) targetValSpan.textContent = val;
                }
            });
        });

        // Quantity controls
        const qtyInput = document.getElementById('qtyInput');
        const qtyMinusBtn = document.getElementById('qtyMinusBtn');
        const qtyPlusBtn = document.getElementById('qtyPlusBtn');

        if (qtyInput && qtyMinusBtn && qtyPlusBtn) {
            qtyMinusBtn.addEventListener('click', () => {
                let val = parseInt(qtyInput.value) || 1;
                if (val > 1) {
                    qtyInput.value = val - 1;
                }
            });

            qtyPlusBtn.addEventListener('click', () => {
                let val = parseInt(qtyInput.value) || 1;
                let max = parseInt(qtyInput.getAttribute('max')) || 99;
                if (val < max) {
                    qtyInput.value = val + 1;
                }
            });
        }

        // Sync quantity to Add to Cart URL
        const cartBtn = document.getElementById('pdpAddToCartBtn');
        if (cartBtn && qtyInput) {
            cartBtn.addEventListener('click', () => {
                const baseUrl = cartBtn.getAttribute('data-add-url');
                const qty = qtyInput.value || 1;
                cartBtn.setAttribute('data-add-url', baseUrl + '?quantity=' + qty);
            });
        }
    });

    // Tab switching function
    function switchPdpTab(tabName) {
        document.querySelectorAll('.pdp-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.pdp-tab-panel').forEach(panel => {
            panel.classList.remove('active');
            panel.style.display = 'none';
        });

        const targetBtn = document.getElementById('tab-btn-' + tabName);
        const targetPanel = document.getElementById('tab-' + tabName);

        if (targetBtn) targetBtn.classList.add('active');
        if (targetPanel) {
            targetPanel.classList.add('active');
            targetPanel.style.display = 'block';
        }
    }
</script>
@endsection
