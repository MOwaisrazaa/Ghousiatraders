@extends('ghousiatraders.layouts.app')

@section('title', 'Ghousia Traders | Little Essentials, Big Joy')

@section('content')
    @php
        $heroBanner = \App\Models\CarouselSlide::getPrimarySlide('home');
    @endphp

    <!-- 3. Hero Banner Section / Slider -->
    <section class="homepage-hero">
        <div class="hero-slide hero-slide--baby-care">
            <!-- Clean High-Res Background Image -->
            <div class="hero-slide__image">
                <img src="{{ $heroBanner ? $heroBanner->getImagePath() : asset('ghousiatraders/assets/baby-care-banner.jpg') }}" alt="{{ $heroBanner->title ?? 'Ghousia Traders Baby Care Essentials banner' }}" class="hero-slide__bg-img" loading="eager">
                <div class="hero-slide__gradient-overlay"></div>
            </div>

            <!-- HTML/CSS Overlay Content Layer -->
            <div class="hero-slide__container">
                <div class="hero-slide__content">
                    <!-- Eyebrow Decoration & Text -->
                    <div class="hero-slide__eyebrow">
                        <span class="hero-slide__line"></span>
                        <svg class="hero-slide__leaf-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                        </svg>
                        <span class="hero-slide__eyebrow-text">{{ $heroBanner->eyebrow ?? 'GENTLE CARE FOR LITTLE ONES' }}</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="hero-slide__heading">
                        {!! nl2br(e($heroBanner->title ?? 'Baby Care Essentials')) !!}
                    </h1>

                    <!-- Description -->
                    <p class="hero-slide__desc">
                        {{ $heroBanner->subtitle ?? 'Soft, safe and everyday essentials for your baby’s comfort, care and happy little moments.' }}
                    </p>

                    <!-- CTA Button Link -->
                    <div class="hero-slide__actions">
                        <a href="{{ $heroBanner->cta_url ?? route('polani.babycare') }}" class="hero-slide__btn">
                            <span>{{ $heroBanner->cta_text ?? 'Explore Baby Care' }}</span>
                            <i data-lucide="arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Shop by Category Section -->
    <section class="category-section" id="categories">
        <div class="section-container">
            <div class="section-divider-header">
                <span class="divider-line"></span>
                <h2 class="section-title-serif">Shop by Category</h2>
                <span class="divider-line"></span>
            </div>

            <div class="category-grid">
                <!-- Category 1: Baby Care Items -->
                @include('ghousiatraders.components.category-card', [
                    'name' => 'Baby Care Items',
                    'slug' => 'baby-care',
                    'image' => 'ghousiatraders/assets/baby_products.png',
                    'icon' => 'baby',
                    'route' => route('polani.babycare')
                ])

                <!-- Category 2: B/O Bikes -->
                @include('ghousiatraders.components.category-card', [
                    'name' => 'B/O Bikes',
                    'slug' => 'bikes',
                    'image' => 'ghousiatraders/assets/ride_on_toys.png',
                    'icon' => 'bike',
                    'route' => route('polani.bikes')
                ])

                <!-- Category 3: B/O Cars -->
                @include('ghousiatraders.components.category-card', [
                    'name' => 'B/O Cars',
                    'slug' => 'cars',
                    'image' => 'ghousiatraders/assets/toy_jeep.png',
                    'icon' => 'car',
                    'route' => route('polani.cars')
                ])
            </div>
        </div>
    </section>

    <!-- 5. Horizontal Feature Bar -->
    <section class="feature-bar-section">
        <div class="section-container feature-bar-container">
            <div class="feature-bar-grid">
                <!-- Feature 1 -->
                <div class="f-bar-item">
                    <div class="f-bar-icon-box">
                        <i data-lucide="award"></i>
                    </div>
                    <div class="f-bar-content">
                        <h4>Premium Quality</h4>
                        <p>Carefully selected products for the best experience</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="f-bar-item">
                    <div class="f-bar-icon-box">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <div class="f-bar-content">
                        <h4>Safe & Secure</h4>
                        <p>Your safety and satisfaction are our top priority</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="f-bar-item">
                    <div class="f-bar-icon-box">
                        <i data-lucide="truck"></i>
                    </div>
                    <div class="f-bar-content">
                        <h4>Fast Delivery</h4>
                        <p>Quick and reliable delivery at your doorstep</p>
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="f-bar-item">
                    <div class="f-bar-icon-box">
                        <i data-lucide="refresh-cw"></i>
                    </div>
                    <div class="f-bar-content">
                        <h4>Easy Returns</h4>
                        <p>Hassle-free returns within 7 days</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Best Sellers Section -->
    <section class="best-sellers-section" id="shop">
        <div class="section-container">
            <div class="section-header-centered">
                <span class="section-badge-pill"><i data-lucide="crown" style="width: 13px; height: 13px;"></i> Top Choice</span>
                <h2 class="section-title-serif">Best Sellers</h2>
            </div>

            <div class="products-grid">
                @foreach($products->take(5) as $product)
                    @include('ghousiatraders.components.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Centered Bottom Pill Button -->
            <div class="section-bottom-cta">
                <a href="{{ route('polani.collection') }}" class="btn-pill-cta">View All Bestsellers <i data-lucide="arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- 7. Baby Care Items Showcase -->
    @if(isset($babyCareProducts) && $babyCareProducts->isNotEmpty())
        <section class="best-sellers-section category-showcase-section" id="baby-care-items">
            <div class="section-container">
                <div class="section-header-centered">
                    <span class="section-badge-pill"><i data-lucide="baby" style="width: 13px; height: 13px;"></i> Gentle Care</span>
                    <h2 class="section-title-serif">Baby Care Items</h2>
                </div>

                <div class="products-grid">
                    @foreach($babyCareProducts->take(5) as $product)
                        @include('ghousiatraders.components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Centered Bottom Pill Button -->
                <div class="section-bottom-cta">
                    <a href="{{ route('polani.babycare') }}" class="btn-pill-cta">View All Baby Care <i data-lucide="arrow-right"></i></a>
                </div>
            </div>
        </section>
    @endif

    <!-- 8. B/O Bikes Section -->
    @if(isset($bikesProducts) && $bikesProducts->isNotEmpty())
        <section class="best-sellers-section category-showcase-section" id="bo-bikes">
            <div class="section-container">
                <div class="section-header-centered">
                    <span class="section-badge-pill"><i data-lucide="bike" style="width: 13px; height: 13px;"></i> Sport & Adventure</span>
                    <h2 class="section-title-serif">B/O Bikes</h2>
                </div>

                <div class="products-grid">
                    @foreach($bikesProducts->take(5) as $product)
                        @include('ghousiatraders.components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Centered Bottom Pill Button -->
                <div class="section-bottom-cta">
                    <a href="{{ route('polani.bikes') }}" class="btn-pill-cta">View All B/O Bikes <i data-lucide="arrow-right"></i></a>
                </div>
            </div>
        </section>
    @endif

    <!-- 9. B/O Cars Section -->
    @if(isset($carsProducts) && $carsProducts->isNotEmpty())
        <section class="best-sellers-section category-showcase-section" id="bo-cars">
            <div class="section-container">
                <div class="section-header-centered">
                    <span class="section-badge-pill"><i data-lucide="car" style="width: 13px; height: 13px;"></i> Luxury Ride-Ons</span>
                    <h2 class="section-title-serif">B/O Cars</h2>
                </div>

                <div class="products-grid">
                    @foreach($carsProducts->take(5) as $product)
                        @include('ghousiatraders.components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <!-- Centered Bottom Pill Button -->
                <div class="section-bottom-cta">
                    <a href="{{ route('polani.cars') }}" class="btn-pill-cta">View All B/O Cars <i data-lucide="arrow-right"></i></a>
                </div>
            </div>
        </section>
    @endif

    <!-- 10. Special Offer Coupon Section -->
    <section class="special-offer-section">
        <div class="section-container">
            <div class="special-offer-card">
                <!-- Left block info -->
                <div class="offer-left">
                    <div class="offer-gift-box">
                        <i data-lucide="gift"></i>
                    </div>
                    <div class="offer-desc-box">
                        <h3>Special Offer!</h3>
                        <p>Get up to 15% OFF on selected ride-on toys. Hurry up! Limited time only.</p>
                    </div>
                </div>

                <!-- Center Coupon Code -->
                <div class="offer-center">
                    <span class="coupon-label">Use Code:</span>
                    <div class="coupon-box" id="couponCodeContainer">
                        <span class="coupon-text" id="promoCode">JOY15</span>
                        <button class="coupon-copy-btn" id="copyCodeBtn" aria-label="Copy Code">
                            <i data-lucide="copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Button link -->
                <div class="offer-right">
                    <a href="{{ route('polani.collection') }}" class="btn btn-primary coupon-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                        Shop the Offer <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Newsletter Section (Pre-Footer) -->
    <section class="pre-footer-cta-section homepage-newsletter">
        <div class="section-container">
            <div class="newsletter-fullwidth-card">
                <div class="cta-icon-container">
                    <i data-lucide="mail" class="cta-icon"></i>
                </div>
                <div class="cta-content">
                    <h3 class="cta-title">Stay Updated with Ghousia Traders</h3>
                    <p class="cta-desc">
                        Subscribe to our newsletter for exclusive offers, new arrivals, and parenting tips.
                    </p>
                    <form class="newsletter-form" id="newsletterForm" onsubmit="event.preventDefault(); alert('Thank you for subscribing to our newsletter!');">
                        <input type="email" placeholder="Enter your email address" required id="newsletterEmail">
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                    <div class="newsletter-msg" id="newsletterMsg"></div>
                </div>
            </div>
        </div>
    </section>
@endsection
