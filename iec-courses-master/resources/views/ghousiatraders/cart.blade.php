@extends('ghousiatraders.layouts.app')

@section('title', 'Shopping Cart — Ghousia Traders')

@section('content')
    <main>
        <!-- Cart Hero Banner -->
        <section class="catalog-hero">
            <div class="catalog-hero-inner">
                <div class="catalog-hero-text">
                    @include('ghousiatraders.components.breadcrumb', [
                        'current' => 'Your Cart'
                    ])
                    <h1 class="catalog-hero-title">Your Cart</h1>
                    <p class="catalog-hero-desc">Review your items and proceed to secure checkout.</p>
                </div>
                <div class="catalog-hero-img">
                    <img src="{{ asset('ghousiatraders/assets/shop_hero.png') }}" alt="Cart Collection">
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
                        <div class="f-bar-icon-box"><i data-lucide="shield-check"></i></div>
                        <div class="f-bar-content"><h4>100% Genuine Products</h4><p>Original and high quality</p></div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="truck"></i></div>
                        <div class="f-bar-content"><h4>Fast Delivery</h4><p>Across Pakistan</p></div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="refresh-cw"></i></div>
                        <div class="f-bar-content"><h4>Easy Returns</h4><p>Within 7 Days</p></div>
                    </div>
                    <div class="f-bar-item">
                        <div class="f-bar-icon-box"><i data-lucide="lock"></i></div>
                        <div class="f-bar-content"><h4>Secure Payments</h4><p>Safe & reliable</p></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
