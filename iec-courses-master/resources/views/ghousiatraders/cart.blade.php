@extends('ghousiatraders.layouts.app')

@section('title', 'Shopping Cart — Ghousia Traders')

@section('content')
    <main class="cart-page-main">
        <!-- Cart Hero Banner (Matches Wishlist Page Hero Banner Style) -->
        <section class="section-container wishlist-hero cart-hero">
            <div class="wishlist-hero-card">
                <div class="wishlist-hero-content">
                    @include('ghousiatraders.components.breadcrumb', [
                        'current' => 'Your Cart'
                    ])
                    <h1 class="wishlist-title">Your Cart</h1>
                    <p class="wishlist-subtitle">Review your items and proceed to secure checkout.</p>
                    <div class="wishlist-count-badge">
                        <i class="fas fa-shopping-bag"></i>
                        <span id="cartHeroItemsCountText">{{ $cartCount ?? 0 }} {{ ($cartCount ?? 0) === 1 ? 'Item' : 'Items' }}</span>
                    </div>
                </div>
                <div class="wishlist-hero-image-wrapper">
                    <img src="{{ asset('ghousiatraders/assets/shop_hero.png') }}" alt="Your Cart" class="wishlist-hero-image">
                </div>
            </div>
        </section>

        <!-- Livewire Cart Component -->
        <livewire:shoppingcart />

        <!-- Bottom Feature Bar -->
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
