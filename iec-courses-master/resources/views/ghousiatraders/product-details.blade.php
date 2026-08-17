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
                            @php $topR = round($product['rating']); @endphp
                            @for($s = 1; $s <= 5; $s++)
                                <i data-lucide="star" class="{{ $s <= $topR ? 'star-filled' : '' }}" style="{{ $s <= $topR ? 'fill:#DFAC4D; color:#DFAC4D;' : 'color:#CBD5E1;' }}"></i>
                            @endfor
                        </div>
                        <span class="pdp-rating-score">
                            @if($product['reviews'] > 0)
                                ({{ number_format($product['rating'], 1) }})
                            @else
                                (No reviews yet)
                            @endif
                        </span>
                        <span class="pdp-rating-divider">|</span>
                        <a href="#pdpFullReviewsSection" class="pdp-reviews-link" id="scrollToReviewsLink">
                            {{ $product['reviews'] }} {{ $product['reviews'] == 1 ? 'Review' : 'Reviews' }}
                        </a>
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
                            <div class="trust-icon-box">
                                <i data-lucide="truck"></i>
                            </div>
                            <div class="trust-text">
                                <strong>Fast Delivery</strong>
                                <span>Across Pakistan</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon-box">
                                <i data-lucide="rotate-ccw"></i>
                            </div>
                            <div class="trust-text">
                                <strong>7 Days Easy Returns</strong>
                                <span>Hassle-free</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon-box">
                                <i data-lucide="shield-check"></i>
                            </div>
                            <div class="trust-text">
                                <strong>Secure Payments</strong>
                                <span>100% Protected</span>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon-box">
                                <i data-lucide="award"></i>
                            </div>
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
    <section class="pdp-tabs-reviews-section" id="pdpTabsSection">
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

            <!-- Right Main Column: Add Your Review Form Card -->
            <div class="pdp-add-review-card">
                <div class="add-review-header">
                    <h3>Add Your Review</h3>
                    <p class="add-review-subtext">Share your genuine experience with this product.</p>
                </div>

                @guest
                    <div class="review-auth-notice">
                        <div class="auth-notice-icon">
                            <i data-lucide="lock"></i>
                        </div>
                        <h4>Sign in required</h4>
                        <p>Please sign in to write a review for this product.</p>
                        <a href="{{ Route::has('login') ? route('login') : route('sign-in') }}" class="btn-pdp-signin">
                            <i data-lucide="log-in"></i> Sign In to Review
                        </a>
                    </div>
                @else
                    @if(!$userHasPurchased && !(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() || auth()->user()->hasRole(['Admin', 'Super Admin'])))
                        <div class="review-purchased-notice">
                            <div class="notice-icon">
                                <i data-lucide="shield-alert"></i>
                            </div>
                            <h4>Verified Purchase Required</h4>
                            <p>Only customers who have purchased this product can submit a review.</p>
                        </div>
                    @else
                        <form id="pdpReviewForm" action="{{ route('products.rate', ['course' => $product['db_id']]) }}" method="POST">
                            @csrf
                            <div id="reviewFormToast" class="review-form-toast" style="display: none;"></div>

                            <!-- Customer Identity -->
                            <div class="review-form-group">
                                <label class="review-form-label">Reviewer Name</label>
                                <input type="text" class="review-form-input readonly-input" value="{{ auth()->user()->name }}" readonly disabled>
                                <span class="input-help-text">Showing your logged-in account name.</span>
                            </div>

                            <!-- Star Rating Selector -->
                            <div class="review-form-group">
                                <label class="review-form-label">Your Rating <span class="req-star">*</span></label>
                                <div class="pdp-star-selector" id="pdpStarSelector">
                                    <input type="hidden" name="rating" id="reviewRatingInput" value="{{ $userReview ? $userReview->rating : 5 }}" required>
                                    @php $currentRating = $userReview ? $userReview->rating : 5; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="star-select-btn {{ $i <= $currentRating ? 'selected' : '' }}" data-value="{{ $i }}" aria-label="Rate {{ $i }} star">
                                            <i data-lucide="star"></i>
                                        </button>
                                    @endfor
                                    <span class="rating-val-text" id="ratingValText">{{ $currentRating }} of 5 Stars</span>
                                </div>
                            </div>

                            <!-- Review Title (Optional) -->
                            <div class="review-form-group">
                                <label class="review-form-label" for="reviewTitleInput">Review Title <span class="optional-tag">(Optional)</span></label>
                                <input type="text" name="title" id="reviewTitleInput" class="review-form-input" placeholder="e.g. Excellent build quality & fast delivery!" value="{{ $userReview ? $userReview->title : '' }}" maxlength="255">
                            </div>

                            <!-- Review Comment (Required) -->
                            <div class="review-form-group">
                                <label class="review-form-label" for="reviewCommentInput">Review Comment <span class="req-star">*</span></label>
                                <textarea name="comment" id="reviewCommentInput" class="review-form-textarea" rows="4" placeholder="Write your detailed feedback here..." required>{{ $userReview ? $userReview->comment : '' }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-pdp-submit-review" id="btnSubmitReview">
                                <i data-lucide="send"></i>
                                <span id="btnSubmitReviewText">{{ $userReview ? 'Update Your Review' : 'Submit Review' }}</span>
                            </button>
                        </form>
                    @endif
                @endguest
            </div>

        </div>
    </section>

    <!-- 5. Full-Width Customer Reviews Section -->
    <section class="pdp-fullwidth-reviews-section" id="pdpFullReviewsSection">
        <div class="section-container">
            <div class="pdp-fullwidth-reviews-card">
                
                <div class="reviews-fullwidth-header">
                    <div class="reviews-title-area">
                        <h2>Customer Reviews</h2>
                        <p class="reviews-subtitle">Real feedback from verified Ghousia Traders customers.</p>
                    </div>

                    <!-- Summary Stat Box -->
                    <div class="reviews-summary-row">
                        <div class="summary-score-column">
                            <span class="score-number">{{ number_format($averageRating, 1) }}</span>
                            <div class="score-stars">
                                @php $aVal = round($averageRating); @endphp
                                @for($s = 1; $s <= 5; $s++)
                                    <i data-lucide="star" class="{{ $s <= $aVal ? 'star-filled' : '' }}" style="{{ $s <= $aVal ? 'fill:#DFAC4D; color:#DFAC4D;' : 'color:#CBD5E1;' }}"></i>
                                @endfor
                            </div>
                            <span class="score-count-label">Based on {{ $ratingCount }} {{ $ratingCount == 1 ? 'approved review' : 'approved reviews' }}</span>
                        </div>

                        <!-- Rating Breakdown Bars -->
                        <div class="rating-breakdown-bars">
                            @for($star = 5; $star >= 1; $star--)
                                @php
                                    $cnt = $ratingBreakdown[$star] ?? 0;
                                    $pct = $ratingCount > 0 ? round(($cnt / $ratingCount) * 100) : 0;
                                @endphp
                                <div class="breakdown-row">
                                    <span class="star-label">{{ $star }} <i data-lucide="star" class="star-tiny" style="width:12px; height:12px; fill:#DFAC4D; color:#DFAC4D; display:inline-block; vertical-align:middle;"></i></span>
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: {{ $pct }}%;"></div>
                                    </div>
                                    <span class="count-label">{{ $cnt }} ({{ $pct }}%)</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <hr class="pdp-divider" style="margin: 24px 0;">

                <!-- Sorting Toolbar -->
                <div class="reviews-toolbar">
                    <div class="toolbar-left">
                        <span class="showing-count-text">Showing {{ $approvedRatings->total() }} {{ $approvedRatings->total() == 1 ? 'review' : 'reviews' }}</span>
                    </div>
                    <div class="toolbar-right">
                        <label for="reviewSortSelect" class="sort-label">Sort by:</label>
                        <select id="reviewSortSelect" class="review-sort-select" onchange="window.location.href='?sort=' + this.value + '#pdpFullReviewsSection';">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="highest_rating" {{ $sort === 'highest_rating' ? 'selected' : '' }}>Highest Rating</option>
                            <option value="lowest_rating" {{ $sort === 'lowest_rating' ? 'selected' : '' }}>Lowest Rating</option>
                        </select>
                    </div>
                </div>

                <!-- Reviews Grid List -->
                <div class="pdp-full-reviews-list">
                    @forelse($approvedRatings as $rItem)
                        @php
                            $uName = $rItem->user?->name ?: ($rItem->reviewer_name ?: 'Customer');
                            $parts = explode(' ', trim($uName));
                            $initials = strtoupper(substr($parts[0] ?? 'C', 0, 1) . substr($parts[1] ?? '', 0, 1));
                        @endphp
                        <div class="pdp-review-card-item">
                            <div class="pdp-review-card-header">
                                <div class="pdp-reviewer-avatar">{{ $initials }}</div>
                                <div class="pdp-reviewer-meta">
                                    <h5>
                                        <span>{{ $uName }}</span>
                                        @if($rItem->is_verified_purchase)
                                            <span class="verified-badge"><i data-lucide="check-circle-2"></i> Verified Purchase</span>
                                        @endif
                                    </h5>
                                    <div class="pdp-review-stars">
                                        @for($st = 1; $st <= 5; $st++)
                                            <i data-lucide="star" class="{{ $st <= $rItem->rating ? 'star-filled' : '' }}" style="{{ $st <= $rItem->rating ? 'fill:#DFAC4D; color:#DFAC4D;' : 'color:#CBD5E1;' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="pdp-review-date">{{ $rItem->created_at ? $rItem->created_at->format('M d, Y') : '' }}</span>
                            </div>

                            @if(!empty($rItem->title))
                                <h4 class="pdp-review-item-title">{{ $rItem->title }}</h4>
                            @endif

                            <p class="pdp-review-item-body">
                                {{ $rItem->comment }}
                            </p>
                        </div>
                    @empty
                        <div class="pdp-reviews-empty-state" style="text-align: center; padding: 40px 20px;">
                            <div class="empty-icon-box" style="margin-bottom: 12px; color: #CBD5E1;">
                                <i data-lucide="message-square-quote" style="width: 48px; height: 48px; display: inline-block;"></i>
                            </div>
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: #4A3B32; margin: 0;">No customer reviews yet.</h3>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($approvedRatings->hasPages())
                    <div class="pdp-reviews-pagination">
                        {{ $approvedRatings->links() }}
                    </div>
                @endif

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

        // Star Rating Selector Logic
        const starBtns = document.querySelectorAll('#pdpStarSelector .star-select-btn');
        const ratingHiddenInput = document.getElementById('reviewRatingInput');
        const ratingValText = document.getElementById('ratingValText');

        if (starBtns.length > 0 && ratingHiddenInput) {
            function updateStarDisplay(val) {
                starBtns.forEach(btn => {
                    const bVal = parseInt(btn.getAttribute('data-value'));
                    if (bVal <= val) {
                        btn.classList.add('selected');
                    } else {
                        btn.classList.remove('selected');
                    }
                });
                if (ratingValText) {
                    ratingValText.textContent = val + ' of 5 Stars';
                }
            }

            starBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedVal = parseInt(this.getAttribute('data-value'));
                    ratingHiddenInput.value = selectedVal;
                    updateStarDisplay(selectedVal);
                });

                btn.addEventListener('mouseenter', function() {
                    const hoverVal = parseInt(this.getAttribute('data-value'));
                    updateStarDisplay(hoverVal);
                });
            });

            const starContainer = document.getElementById('pdpStarSelector');
            if (starContainer) {
                starContainer.addEventListener('mouseleave', function() {
                    const currentVal = parseInt(ratingHiddenInput.value) || 5;
                    updateStarDisplay(currentVal);
                });
            }

            // Ensure initial load matches saved value
            const initialRating = parseInt(ratingHiddenInput.value) || 5;
            updateStarDisplay(initialRating);
        }

        // AJAX Review Form Submission
        const reviewForm = document.getElementById('pdpReviewForm');
        const submitBtn = document.getElementById('btnSubmitReview');
        const submitBtnText = document.getElementById('btnSubmitReviewText');
        const toastBox = document.getElementById('reviewFormToast');

        if (reviewForm) {
            const isUpdateMode = submitBtnText && submitBtnText.textContent.trim().toLowerCase().includes('update');
            const defaultBtnText = isUpdateMode ? 'Update Your Review' : 'Submit Review';

            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous inline error messages
                document.querySelectorAll('.field-error-msg').forEach(el => el.remove());

                if (submitBtn) {
                    submitBtn.disabled = true;
                    if (submitBtnText) submitBtnText.textContent = isUpdateMode ? 'Updating...' : 'Submitting...';
                }

                if (toastBox) {
                    toastBox.style.display = 'none';
                    toastBox.className = 'review-form-toast';
                }

                const formData = new FormData(reviewForm);

                fetch(reviewForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({ success: false, message: 'Server error' }));
                    if (res.ok && data.success) {
                        if (toastBox) {
                            toastBox.className = 'review-form-toast toast-success';
                            toastBox.textContent = data.message || 'Review submitted successfully!';
                            toastBox.style.display = 'block';
                        }
                        if (window.showStorefrontToast) {
                            window.showStorefrontToast(data.message || 'Review submitted successfully!', 'success');
                        }
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    } else {
                        if (toastBox) {
                            toastBox.className = 'review-form-toast toast-error';
                            toastBox.textContent = data.message || 'Submission failed. Please check your inputs.';
                            toastBox.style.display = 'block';
                        }
                        // Display inline validation errors
                        if (data.errors) {
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const inputElement = reviewForm.querySelector(`[name="${field}"]`);
                                if (inputElement) {
                                    const errSpan = document.createElement('span');
                                    errSpan.className = 'field-error-msg';
                                    errSpan.style.color = '#DC2626';
                                    errSpan.style.fontSize = '0.82rem';
                                    errSpan.style.display = 'block';
                                    errSpan.style.marginTop = '4px';
                                    errSpan.textContent = Array.isArray(messages) ? messages[0] : messages;
                                    inputElement.parentNode.appendChild(errSpan);
                                }
                            }
                        }
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            if (submitBtnText) submitBtnText.textContent = defaultBtnText;
                        }
                    }
                })
                .catch(err => {
                    if (toastBox) {
                        toastBox.className = 'review-form-toast toast-error';
                        toastBox.textContent = 'An unexpected error occurred. Please try again.';
                        toastBox.style.display = 'block';
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        if (submitBtnText) submitBtnText.textContent = defaultBtnText;
                    }
                });
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
