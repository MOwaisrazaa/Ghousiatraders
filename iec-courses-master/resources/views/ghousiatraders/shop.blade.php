@extends('ghousiatraders.layouts.app')

@section('title', 'Shop — Ghousia Traders')

@section('content')
    <main>
        <!-- Shop Hero Banner -->
        <section class="catalog-hero">
            <div class="catalog-hero-inner">
                <div class="catalog-hero-text">
                    <h1 class="catalog-hero-title">
                        {{ $searchQuery ? "Search Results for '{$searchQuery}'" : 'Shop' }}
                    </h1>
                    <p class="catalog-hero-desc">Discover our wide range of premium baby care products and exciting ride-on toys.</p>
                </div>
                <div class="catalog-hero-img">
                    <img src="{{ asset('ghousiatraders/assets/shop_hero.png') }}" alt="Shop Collection">
                </div>
            </div>
            <!-- Hero Highlights -->
            <div class="catalog-highlights">
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="award"></i></div>
                    <div class="highlight-text">
                        <strong>Premium Quality</strong>
                        <span>Original and high quality</span>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="truck"></i></div>
                    <div class="highlight-text">
                        <strong>Fast Delivery</strong>
                        <span>Across Pakistan</span>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="refresh-cw"></i></div>
                    <div class="highlight-text">
                        <strong>Easy Returns</strong>
                        <span>Within 7 Days</span>
                    </div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="lock"></i></div>
                    <div class="highlight-text">
                        <strong>Secure Payments</strong>
                        <span>Safe & reliable</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Catalog Body: Sidebar + Grid -->
        <section class="catalog-body">
            <div class="section-container catalog-container">
                <!-- Breadcrumb -->
                @include('ghousiatraders.components.breadcrumb', [
                    'current' => $searchQuery ? 'Search' : 'Shop'
                ])

                <div class="catalog-layout">
                    <!-- Left Sidebar Filters -->
                    <aside class="catalog-sidebar" id="filterSidebar">
                        <div class="filter-group">
                            <h4 class="filter-title">Categories</h4>
                            <ul class="filter-list" style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.collection') }}" style="text-decoration: none; color: var(--primary); font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                                        <span>All Products</span>
                                    </a>
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.babycare') }}" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center;">
                                        <span>Baby Care Items</span>
                                    </a>
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.bikes') }}" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center;">
                                        <span>B/O Bikes</span>
                                    </a>
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.cars') }}" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center;">
                                        <span>B/O Cars</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <!-- Catalog Main Listing -->
                    <div class="catalog-main">
                        <div class="catalog-header">
                            <button class="mobile-filter-btn" id="mobileFilterBtn">☰ Filters</button>
                            <div class="catalog-results-count" id="catalogResultsCount">
                                Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                            </div>
                        </div>

                        <!-- Product Grid -->
                        @if($products->total() > 0)
                            <div class="products-grid">
                                @foreach($products as $product)
                                    @include('ghousiatraders.components.product-card', ['product' => $product])
                                @endforeach
                            </div>

                            <!-- Custom Pagination -->
                            <div style="margin-top: 40px; display: flex; justify-content: center;">
                                {{ $products->links('ghousiatraders.components.pagination') }}
                            </div>
                        @else
                            @include('ghousiatraders.components.empty-state', [
                                'title' => 'No Products Found',
                                'message' => $searchQuery ? "We couldn't find any products matching '{$searchQuery}'. Please try a different term." : 'No products are currently available in the shop.'
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom Feature Bar -->
        <section class="feature-bar-section">
            <div class="section-container feature-bar-container">
                <div class="feature-bar-grid">
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="award"></i></div>
                        <div class="f-bar-content">
                            <h4>Premium Quality</h4>
                            <p>Carefully selected products for the best experience</p>
                        </div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="shield-check"></i></div>
                        <div class="f-bar-content">
                            <h4>Safe & Secure</h4>
                            <p>Your safety and satisfaction are our top priority</p>
                        </div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="truck"></i></div>
                        <div class="f-bar-content">
                            <h4>Fast Delivery</h4>
                            <p>Quick and reliable delivery at your doorstep</p>
                        </div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="refresh-cw"></i></div>
                        <div class="f-bar-content">
                            <h4>Easy Returns</h4>
                            <p>Hassle-free returns within 7 days</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
