@extends('ghousiatraders.layouts.app')

@section('title', 'Checkout — Ghousia Traders')

@section('content')
    <main>
        <!-- Checkout Header Section -->
        <section class="checkout-header-section" style="background-color: var(--bg-alt); padding: 40px 0 20px;">
            <div class="section-container">
                <div class="checkout-header-row" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
                    <div>
                        @include('ghousiatraders.components.breadcrumb', [
                            'current' => 'Checkout'
                        ])
                        <h1 class="checkout-title" style="font-size:2.2rem; font-weight:800; color:var(--primary); margin-top:8px;">Checkout</h1>
                        <p style="color:var(--text-muted); margin-top:4px;">Complete your order securely and quickly.</p>
                    </div>
                    <!-- Checkout Steps Indicator -->
                    <div class="checkout-steps-indicator" style="display:flex; align-items:center; gap:16px;">
                        <div class="step-item completed" style="display:flex; align-items:center; gap:8px; font-weight:600; color:var(--text-muted);">
                            <span class="step-num" style="width:28px; height:28px; border-radius:50%; border:2px solid var(--border-color); display:flex; align-items:center; justify-content:center; font-size:0.85rem;">1</span>
                            <span class="step-label">Cart</span>
                        </div>
                        <div style="width:40px; height:2px; background-color:var(--border-color);"></div>
                        <div class="step-item active" style="display:flex; align-items:center; gap:8px; font-weight:700; color:var(--primary);">
                            <span class="step-num" style="width:28px; height:28px; border-radius:50%; border:2px solid var(--primary); display:flex; align-items:center; justify-content:center; font-size:0.85rem; background-color:var(--primary); color:#fff;">2</span>
                            <span class="step-label">Checkout</span>
                        </div>
                        <div style="width:40px; height:2px; background-color:var(--border-color);"></div>
                        <div class="step-item" style="display:flex; align-items:center; gap:8px; font-weight:600; color:var(--text-muted);">
                            <span class="step-num" style="width:28px; height:28px; border-radius:50%; border:2px solid var(--border-color); display:flex; align-items:center; justify-content:center; font-size:0.85rem;">3</span>
                            <span class="step-label">Order Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Livewire Checkout Component -->
        <livewire:checkout />
    </main>
@endsection
