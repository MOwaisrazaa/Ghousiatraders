@extends('ghousiatraders.layouts.app')

@section('title', $pageTitle)

@section('content')
    <main>
        <!-- Category Hero Banner -->
        <section class="catalog-hero">
            <div class="catalog-hero-inner">
                <div class="catalog-hero-text">
                    <h1 class="catalog-hero-title">{{ $collectionLabel }}</h1>
                    <p class="catalog-hero-desc">{{ $pageDescription }}</p>
                </div>
                <div class="catalog-hero-img">
                    <img src="{{ asset($heroImage) }}" alt="{{ $collectionLabel }}">
                </div>
            </div>
            <div class="catalog-highlights">
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="award"></i></div>
                    <div class="highlight-text"><strong>Premium Quality</strong><span>Trusted & Loved</span></div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="heart"></i></div>
                    <div class="highlight-text"><strong>Skin Friendly</strong><span>Gentle on Skin</span></div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="package"></i></div>
                    <div class="highlight-text"><strong>Everyday Essentials</strong><span>For Daily Care</span></div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-icon"><i data-lucide="shield-check"></i></div>
                    <div class="highlight-text"><strong>Safe & Gentle Care</strong><span>For Your Baby</span></div>
                </div>
            </div>
        </section>

        <!-- Catalog Body -->
        <section class="catalog-body">
            <div class="section-container catalog-container">
                <!-- Breadcrumbs -->
                @include('ghousiatraders.components.breadcrumb', [
                    'items' => [
                        ['label' => 'Shop', 'url' => route('polani.collection')]
                    ],
                    'current' => $collectionLabel
                ])

                <div class="catalog-layout">
                    <!-- Left Sidebar Filters -->
                    <aside class="catalog-sidebar" id="filterSidebar">
                        <div class="filter-group">
                            <h4 class="filter-title">Categories</h4>
                            <ul class="filter-list" style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.collection') }}" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between; align-items: center;">
                                        <span>All Products</span>
                                    </a>
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.babycare') }}" style="text-decoration: none; color: {{ $pageKey === 'baby-care' ? 'var(--primary)' : 'inherit' }}; font-weight: {{ $pageKey === 'baby-care' ? '700' : '400' }}; display: flex; justify-content: space-between; align-items: center;">
                                        <span>Baby Care Items</span>
                                    </a>
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.bikes') }}" style="text-decoration: none; color: {{ $pageKey === 'bo-bikes' ? 'var(--primary)' : 'inherit' }}; font-weight: {{ $pageKey === 'bo-bikes' ? '700' : '400' }}; display: flex; justify-content: space-between; align-items: center;">
                                        <span>B/O Bikes</span>
                                    </a>
                                </li>
                                <li style="margin-bottom: 10px;">
                                    <a href="{{ route('polani.cars') }}" style="text-decoration: none; color: {{ $pageKey === 'bo-cars' ? 'var(--primary)' : 'inherit' }}; font-weight: {{ $pageKey === 'bo-cars' ? '700' : '400' }}; display: flex; justify-content: space-between; align-items: center;">
                                        <span>B/O Cars</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <!-- Catalog Main Content -->
                    <div class="catalog-main">
                        <div class="catalog-header">
                            <button class="mobile-filter-btn" id="mobileFilterBtn">☰ Filters</button>
                            <div class="catalog-results-count" id="catalogResultsCount">
                                Showing {{ count($products) }} products
                            </div>
                        </div>

                        <!-- Product Grid -->
                        @if(count($products) > 0)
                            <div class="products-grid">
                                @foreach($products as $product)
                                    @include('ghousiatraders.components.product-card', ['product' => $product])
                                @endforeach
                            </div>
                        @else
                            @include('ghousiatraders.components.empty-state', [
                                'title' => 'No Products Found',
                                'message' => "We couldn't find any products in the '{$collectionLabel}' category right now."
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
