@extends('ghousiatraders.layouts.app')

@section('title', 'Track Your Order | Ghousia Traders')

@push('head')
<style>
    /* Premium Track Order Styles */
    .track-order-page {
        background-color: #fffcf8;
        padding: 40px 0 80px 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #351b0d;
    }

    .track-container {
        max-width: var(--container-width, 1200px);
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Breadcrumb styling */
    .track-breadcrumb {
        font-size: 0.88rem;
        color: #8a7355;
        margin-bottom: 24px;
        font-weight: 500;
    }

    .track-breadcrumb a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .track-breadcrumb a:hover {
        color: #d7a64a;
    }

    .track-breadcrumb span {
        margin: 0 8px;
        color: #d1c1ad;
    }

    /* Hero Banner Card */
    .track-hero-card {
        background: #fff8ee;
        border: 1px solid rgba(215, 166, 74, 0.25);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 40px;
        margin-bottom: 40px;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
        gap: 40px;
        align-items: center;
        overflow: hidden;
    }

    .track-hero-left {
        display: flex;
        flex-direction: column;
    }

    .track-hero-title {
        font-size: 2.6rem;
        font-weight: 800;
        color: #351b0d;
        margin: 0 0 12px;
        line-height: 1.25;
    }

    .track-hero-desc {
        color: #654c38;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0 0 24px;
    }

    /* Tracking Form */
    .track-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }

    .track-input-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .track-input-group label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #351b0d;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .track-input {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid rgba(215, 166, 74, 0.35);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.9rem;
        color: #351b0d;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .track-input:focus {
        border-color: #d7a64a;
        box-shadow: 0 0 0 3px rgba(215, 166, 74, 0.15);
    }

    .track-submit-btn {
        background: #44240f;
        color: #fffaf3;
        border: none;
        padding: 12px 28px;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        width: fit-content;
        min-height: 44px;
    }

    .track-submit-btn:hover {
        background: #351b0d;
        transform: translateY(-1px);
    }

    .track-hero-right {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .track-hero-img {
        width: 100%;
        max-width: 360px;
        height: auto;
        object-fit: contain;
        border-radius: 12px;
    }

    /* Results layout */
    .tracking-results-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(300px, 0.75fr);
        gap: 30px;
        align-items: start;
    }

    .tracking-left-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .tracking-right-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Card styling */
    .tracking-card {
        background: #ffffff;
        border: 1px solid rgba(215, 166, 74, 0.22);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    }

    .tracking-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.15);
        padding-bottom: 12px;
    }

    .tracking-card-header i {
        color: #d7a64a;
        width: 20px;
        height: 20px;
    }

    .tracking-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #351b0d;
    }

    /* Order status detailed summary */
    .order-status-details {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
        background: rgba(215, 166, 74, 0.05);
        padding: 16px 20px;
        border-radius: 12px;
    }

    .status-detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .status-detail-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #8a7355;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-detail-value {
        font-size: 0.88rem;
        font-weight: 700;
        color: #351b0d;
    }

    /* Timeline progress bar */
    .timeline-progress-wrapper {
        position: relative;
        padding: 20px 0;
        margin: 10px 0;
    }

    .timeline-line {
        position: absolute;
        top: 36px;
        left: 45px;
        right: 45px;
        height: 3px;
        background: #e2d8cd;
        z-index: 1;
    }

    .timeline-line-active {
        position: absolute;
        top: 36px;
        left: 45px;
        height: 3px;
        background: #8a7355;
        z-index: 2;
        transition: width 0.3s ease;
    }

    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 3;
    }

    .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100px;
        text-align: center;
    }

    .timeline-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e2d8cd;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        color: #c4b5a2;
    }

    .timeline-circle i {
        width: 14px;
        height: 14px;
    }

    .timeline-step.completed .timeline-circle {
        background: #44240f;
        border-color: #44240f;
        color: #fff;
    }

    .timeline-step.current .timeline-circle {
        border-color: #d7a64a;
        color: #d7a64a;
        background: #fff;
        box-shadow: 0 0 10px rgba(215, 166, 74, 0.35);
    }

    .timeline-step-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #8a7355;
        margin-bottom: 4px;
    }

    .timeline-step-date {
        font-size: 0.68rem;
        color: #ab957a;
    }

    /* Shipment Details Card */
    .shipment-details-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .shipment-items-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        flex: 1;
    }

    .shipment-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .shipment-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #8a7355;
        text-transform: uppercase;
    }

    .shipment-value {
        font-size: 0.88rem;
        font-weight: 700;
        color: #351b0d;
    }

    .shipment-status-badge {
        background: #ecfdf5;
        color: #059669;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.78rem;
        font-weight: 700;
        width: fit-content;
    }

    .tcs-logo {
        display: inline-flex;
        align-items: center;
        font-weight: 900;
        font-size: 1.1rem;
        letter-spacing: -0.02em;
    }
    .tcs-logo span:first-child {
        color: #e11d48;
    }
    .tcs-logo span:last-child {
        color: #2563eb;
        font-style: italic;
    }

    .contact-courier-btn {
        border: 1px solid rgba(215, 166, 74, 0.45);
        color: #44240f;
        padding: 8px 16px;
        font-size: 0.78rem;
        font-weight: 700;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
        min-height: 38px;
        display: inline-flex;
        align-items: center;
    }

    .contact-courier-btn:hover {
        background: rgba(215, 166, 74, 0.1);
    }

    /* Ordered Items Table */
    .ordered-items-table-wrapper {
        overflow-x: auto;
        margin-top: 10px;
    }

    .ordered-items-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .ordered-items-table th {
        padding: 12px 16px;
        border-bottom: 2px solid rgba(215, 166, 74, 0.15);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #8a7355;
    }

    .ordered-items-table td {
        padding: 16px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
        vertical-align: middle;
    }

    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-thumb {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(215, 166, 74, 0.15);
        background: #fbf8f5;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .product-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #351b0d;
    }

    .product-desc {
        font-size: 0.75rem;
        color: #654c38;
    }

    .price-text, .total-text {
        font-size: 0.88rem;
        font-weight: 700;
        color: #351b0d;
    }

    /* Delivery Updates Timeline */
    .delivery-updates-list {
        display: flex;
        flex-direction: column;
        position: relative;
        padding-left: 24px;
        margin-top: 10px;
    }

    .delivery-updates-list::before {
        content: '';
        position: absolute;
        top: 8px;
        bottom: 8px;
        left: 7px;
        width: 2px;
        background: #e2d8cd;
    }

    .update-item {
        position: relative;
        padding-bottom: 24px;
        display: flex;
        gap: 24px;
    }

    .update-item:last-child {
        padding-bottom: 0;
    }

    .update-bullet {
        position: absolute;
        left: -23px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #e2d8cd;
        border: 3px solid #fff;
        z-index: 2;
    }

    .update-item.active .update-bullet {
        background: #10b981;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
    }

    .update-item.past .update-bullet {
        background: #8a7355;
    }

    .update-time-col {
        width: 140px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #8a7355;
        white-space: nowrap;
    }

    .update-item.active .update-time-col {
        color: #10b981;
    }

    .update-content-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .update-heading {
        font-size: 0.88rem;
        font-weight: 700;
        color: #351b0d;
    }

    .update-item.active .update-heading {
        color: #10b981;
    }

    .update-description {
        font-size: 0.78rem;
        color: #654c38;
    }

    /* Sidebar Summary */
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        color: #654c38;
        margin-bottom: 12px;
    }

    .summary-row.total-row {
        font-size: 1.15rem;
        font-weight: 800;
        color: #351b0d;
        border-top: 1.5px solid rgba(215, 166, 74, 0.15);
        padding-top: 16px;
        margin-top: 16px;
        margin-bottom: 16px;
    }

    .savings-badge {
        background: #ecfdf5;
        color: #047857;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Sidebar Blocks */
    .sidebar-address-block, .sidebar-payment-block {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 0.88rem;
        color: #654c38;
    }

    .sidebar-address-name {
        font-weight: 700;
        color: #351b0d;
    }

    .sidebar-payment-method {
        font-weight: 700;
        color: #351b0d;
    }

    /* Help Sidebar buttons */
    .help-support-desc {
        font-size: 0.85rem;
        color: #654c38;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .help-actions-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-contact-support {
        background: #44240f;
        color: #fffaf3;
        border: none;
        width: 100%;
        padding: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        min-height: 44px;
        box-sizing: border-box;
    }

    .btn-contact-support:hover {
        background: #351b0d;
    }

    .btn-continue-shopping {
        border: 1px solid rgba(215, 166, 74, 0.45);
        color: #44240f;
        background: transparent;
        width: 100%;
        padding: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        min-height: 44px;
        box-sizing: border-box;
    }

    .btn-continue-shopping:hover {
        background: rgba(215, 166, 74, 0.1);
    }

    /* Recent orders */
    .recent-orders-card {
        margin-bottom: 24px;
    }

    .recent-orders-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .recent-order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border: 1px solid rgba(215, 166, 74, 0.15);
        border-radius: 8px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
    }

    .recent-order-item:hover {
        background: rgba(215, 166, 74, 0.05);
        border-color: #d7a64a;
    }

    .recent-order-id {
        font-weight: 700;
        color: #351b0d;
    }

    .recent-order-meta {
        font-size: 0.78rem;
        color: #8a7355;
    }

    .recent-order-status {
        background: #fdf5e6;
        color: #b45309;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .recent-order-status.status-completed {
        background: #ecfdf5;
        color: #047857;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .track-hero-card {
            grid-template-columns: 1fr;
            padding: 30px;
            gap: 30px;
        }

        .track-hero-right {
            min-height: auto;
        }

        .tracking-results-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .track-form-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .order-status-details {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .timeline-line {
            display: none;
        }

        .timeline-steps {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
            padding-left: 20px;
        }

        .timeline-step {
            flex-direction: row;
            width: 100%;
            text-align: left;
            gap: 12px;
        }

        .timeline-circle {
            margin-bottom: 0;
        }

        .shipment-items-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .shipment-details-row {
            align-items: flex-start;
        }

        .contact-courier-btn {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
  @php
    $isAuthenticated = $isAuthenticated ?? auth()->check();
    $orders = $orders ?? collect();
    $orderModel = null;
    $billing = [];
    if ($order) {
        $orderModel = \App\Models\Order::find($order['orderId']);
        $billing = json_decode($orderModel->billing_address ?? '{}', true) ?: [];
    }
  @endphp

  <section class="track-order-page">
    <div class="track-container">
      
      <!-- Breadcrumb -->
      <div class="track-breadcrumb">
          <a href="{{ route('home') }}">Home</a>
          <span>&gt;</span>
          Track Order
      </div>

      <!-- Hero Card Banner -->
      <div class="track-hero-card">
          <div class="track-hero-left">
              <h1 class="track-hero-title">Track Your Order</h1>
              <p class="track-hero-desc">Enter your order details and see the latest delivery progress in real time.</p>
              
              <form class="track-form" method="GET" action="{{ route('polani.track-order') }}" id="trackOrderForm">
                  <div class="track-form-grid">
                      <div class="track-input-group">
                          <label for="order_number">Order ID</label>
                          <input class="track-input" type="text" name="order_number" id="order_number" placeholder="e.g. GT-24851" value="{{ $query['order_number'] ?? '' }}" required />
                      </div>
                      <div class="track-input-group">
                          <label for="contact_input">Email / Phone Number</label>
                          <input class="track-input" type="text" id="contact_input" placeholder="e.g. ali@example.com or 0321-1234567" value="{{ $query['email'] ?: ($query['phone'] ?: '') }}" required />
                      </div>
                      <!-- Hidden inputs for splitting -->
                      <input type="hidden" name="email" id="hidden_email" value="{{ $query['email'] ?? '' }}">
                      <input type="hidden" name="phone" id="hidden_phone" value="{{ $query['phone'] ?? '' }}">
                  </div>
                  <div class="track-action-row">
                      <button class="track-submit-btn" type="submit">
                          <span>Track Order</span>
                          <i data-lucide="arrow-right"></i>
                      </button>
                  </div>
              </form>
          </div>
          
          <div class="track-hero-right">
              <img src="{{ asset('ghousiatraders/track-order-hero.png') }}" alt="Track Delivery" class="track-hero-img">
          </div>
      </div>

      <!-- Error Message Notice -->
      @if($error)
          <div class="tracking-card" style="border-color: #f87171; background-color: #fef2f2; margin-bottom: 30px;">
              <div style="display: flex; align-items: center; gap: 10px; color: #b91c1c; font-weight: 700;">
                  <i data-lucide="alert-circle"></i>
                  <span>{{ $error }}</span>
              </div>
          </div>
      @endif

      <!-- Auth Users Recent Orders list -->
      @if($isAuthenticated && !$order && $orders->isNotEmpty())
          <div class="tracking-card recent-orders-card">
              <div class="tracking-card-header">
                  <i data-lucide="list"></i>
                  <h3 class="tracking-card-title">Your Orders</h3>
              </div>
              <div class="recent-orders-list">
                  @foreach($orders as $trackedOrder)
                      @php
                          $rawOrder = \App\Models\Order::find($trackedOrder['orderId']);
                      @endphp
                      <a class="recent-order-item" href="{{ route('polani.track-order', ['order_number' => 'GT-'.$trackedOrder['orderId'], 'email' => $rawOrder ? json_decode($rawOrder->billing_address, true)['email'] : '']) }}">
                          <div>
                              <span class="recent-order-id">#GT-{{ $trackedOrder['orderId'] }}</span>
                              <span class="recent-order-meta">&bull; {{ $trackedOrder['orderDate'] }} &bull; Subtotal: PKR {{ number_format($trackedOrder['total']) }}</span>
                          </div>
                          <span class="recent-order-status @if($trackedOrder['status'] === 'completed') status-completed @endif">
                              {{ $trackedOrder['statusLabel'] }}
                          </span>
                      </a>
                  @endforeach
              </div>
          </div>
      @endif

      <!-- Two-Column Active Tracking Results -->
      @if($order)
        <div class="tracking-results-grid">
            
            <!-- Left Column Content -->
            <div class="tracking-left-column">
                
                <!-- Order Status Timeline Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="clipboard-list"></i>
                        <h3 class="tracking-card-title">Order Status</h3>
                    </div>
                    
                    @php
                      $orderDate = \Carbon\Carbon::parse($order['orderDate']);
                      $confirmTime = $orderDate->format('d M, h:i A');
                      $packedTime = $orderDate->copy()->addHours(5)->format('d M, h:i A');
                      $shippedTime = $orderDate->copy()->addDays(1)->addHours(2)->format('d M, h:i A');
                      $deliveryTime = $orderDate->copy()->addDays(2)->addHours(4)->format('d M, h:i A');
                      
                      $statusStep = (int) ($order['statusStep'] ?? 1);
                      if ($statusStep === 0) {
                          $statusStep = 1;
                      }
                      
                      // Calculate active timeline line percentage
                      $linePercent = match($statusStep) {
                          1 => 0,
                          2 => 33.33,
                          3 => 66.66,
                          4 => 100,
                          default => 0
                      };
                    @endphp
                    
                    <div class="order-status-details">
                        <div class="status-detail-item">
                            <span class="status-detail-label">Order No.</span>
                            <span class="status-detail-value">#GT-{{ $order['orderId'] }}</span>
                        </div>
                        <div class="status-detail-item">
                            <span class="status-detail-label">Order Date</span>
                            <span class="status-detail-value">{{ $orderDate->format('d M Y') }}</span>
                        </div>
                        <div class="status-detail-item">
                            <span class="status-detail-label">Est. Delivery</span>
                            <span class="status-detail-value">{{ $orderDate->copy()->addDays(4)->format('d M Y') }}</span>
                        </div>
                        <div class="status-detail-item">
                            <span class="status-detail-label">Current Status</span>
                            <span class="status-detail-value" style="color:#d7a64a;">{{ $order['statusLabel'] }}</span>
                        </div>
                    </div>
                    
                    <!-- Horizontal Timeline -->
                    <div class="timeline-progress-wrapper">
                        <div class="timeline-line"></div>
                        <div class="timeline-line-active" style="width: calc({{ $linePercent }}% - 10px);"></div>
                        
                        <div class="timeline-steps">
                            <!-- Step 1: Confirmed -->
                            <div class="timeline-step @if($statusStep > 1) completed @elseif($statusStep === 1) current @endif">
                                <div class="timeline-circle">
                                    @if($statusStep > 1)
                                        <i data-lucide="check"></i>
                                    @else
                                        <i data-lucide="truck"></i>
                                    @endif
                                </div>
                                <span class="timeline-step-label">Order Confirmed</span>
                                <span class="timeline-step-date">{{ $confirmTime }}</span>
                            </div>
                            
                            <!-- Step 2: Packed -->
                            <div class="timeline-step @if($statusStep > 2) completed @elseif($statusStep === 2) current @endif">
                                <div class="timeline-circle">
                                    @if($statusStep > 2)
                                        <i data-lucide="check"></i>
                                    @elseif($statusStep === 2)
                                        <i data-lucide="truck"></i>
                                    @else
                                        <i data-lucide="package"></i>
                                    @endif
                                </div>
                                <span class="timeline-step-label">Packed</span>
                                <span class="timeline-step-date">{{ $statusStep >= 2 ? $packedTime : 'Pending' }}</span>
                            </div>
                            
                            <!-- Step 3: Shipped -->
                            <div class="timeline-step @if($statusStep > 3) completed @elseif($statusStep === 3) current @endif">
                                <div class="timeline-circle">
                                    @if($statusStep > 3)
                                        <i data-lucide="check"></i>
                                    @elseif($statusStep === 3)
                                        <i data-lucide="truck"></i>
                                    @else
                                        <i data-lucide="plane"></i>
                                    @endif
                                </div>
                                <span class="timeline-step-label">Shipped</span>
                                <span class="timeline-step-date">{{ $statusStep >= 3 ? $shippedTime : 'Pending' }}</span>
                            </div>
                            
                            <!-- Step 4: Out for Delivery -->
                            <div class="timeline-step @if($statusStep > 4) completed @elseif($statusStep === 4) current @endif">
                                <div class="timeline-circle">
                                    @if($statusStep > 4)
                                        <i data-lucide="check"></i>
                                    @else
                                        <i data-lucide="truck"></i>
                                    @endif
                                </div>
                                <span class="timeline-step-label">Out for Delivery</span>
                                <span class="timeline-step-date">{{ $statusStep >= 4 ? $deliveryTime : 'Pending' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipment Details Card -->
                <div class="tracking-card">
                    <div class="shipment-details-row">
                        <div class="shipment-items-grid">
                            <div class="shipment-item">
                                <span class="shipment-label">Courier</span>
                                <div class="tcs-logo"><span>T</span><span>CS</span></div>
                            </div>
                            <div class="shipment-item">
                                <span class="shipment-label">Tracking ID</span>
                                <span class="shipment-value">TCS-903{{ $order['orderId'] }}28</span>
                            </div>
                            <div class="shipment-item">
                                <span class="shipment-label">Current Status</span>
                                <span class="shipment-status-badge">{{ $order['statusLabel'] }}</span>
                            </div>
                            <div class="shipment-item">
                                <span class="shipment-label">Location</span>
                                <span class="shipment-value">{{ $billing['city'] ?? 'Lahore Hub' }}</span>
                            </div>
                            <div class="shipment-item">
                                <span class="shipment-label">Last Update</span>
                                <span class="shipment-value">Today, 10:45 AM</span>
                            </div>
                        </div>
                        <a href="tel:03211234567" class="contact-courier-btn">Contact Courier</a>
                    </div>
                </div>

                <!-- Ordered Items Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="shopping-bag"></i>
                        <h3 class="tracking-card-title">Ordered Items</h3>
                    </div>
                    <div class="ordered-items-table-wrapper">
                        <table class="ordered-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order['items'] as $item)
                                    @php
                                        $courseModel = \App\Models\Course::where('slug', $item['slug'])->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="product-cell">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="product-thumb">
                                                <div class="product-info">
                                                    <span class="product-title">{{ $item['name'] }}</span>
                                                    <span class="product-desc">{{ $courseModel->description ?? 'Premium product from Ghousia Traders.' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="price-text">PKR {{ number_format($item['price']) }}</span></td>
                                        <td><span class="price-text">{{ $item['quantity'] }}</span></td>
                                        <td><span class="total-text" style="color: #44240f;">PKR {{ number_format($item['total']) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Delivery Updates Timeline Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="clock"></i>
                        <h3 class="tracking-card-title">Delivery Updates</h3>
                    </div>
                    <div class="delivery-updates-list">
                        <!-- Update 4 -->
                        <div class="update-item @if($statusStep >= 4) active @endif">
                            <div class="update-bullet"></div>
                            <div class="update-time-col">{{ $statusStep >= 4 ? $deliveryTime : '' }}</div>
                            <div class="update-content-col">
                                <span class="update-heading">Out for delivery</span>
                                <span class="update-description">Your order is out for delivery and will reach you today.</span>
                            </div>
                        </div>
                        
                        <!-- Update 3 -->
                        <div class="update-item @if($statusStep >= 3) past @endif">
                            <div class="update-bullet"></div>
                            <div class="update-time-col">{{ $statusStep >= 3 ? $shippedTime : '' }}</div>
                            <div class="update-content-col">
                                <span class="update-heading">Shipped</span>
                                <span class="update-description">Your order has been shipped from Lahore Hub.</span>
                            </div>
                        </div>

                        <!-- Update 2 -->
                        <div class="update-item @if($statusStep >= 2) past @endif">
                            <div class="update-bullet"></div>
                            <div class="update-time-col">{{ $statusStep >= 2 ? $packedTime : '' }}</div>
                            <div class="update-content-col">
                                <span class="update-heading">Packed</span>
                                <span class="update-description">Your order has been packed and is ready to ship.</span>
                            </div>
                        </div>

                        <!-- Update 1 -->
                        <div class="update-item @if($statusStep >= 1) past @endif">
                            <div class="update-bullet"></div>
                            <div class="update-time-col">{{ $confirmTime }}</div>
                            <div class="update-content-col">
                                <span class="update-heading">Order Confirmed</span>
                                <span class="update-description">We've received your order and are preparing it.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Columns -->
            <div class="tracking-right-sidebar">
                
                <!-- Order Summary Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="receipt"></i>
                        <h3 class="tracking-card-title">Order Summary</h3>
                    </div>
                    @php
                        $subtotal = $orderModel ? (float) $orderModel->total : $order['total'];
                        $discount = $orderModel ? (float) $orderModel->discount : 0;
                        $shippingCost = (float) ($billing['shipping_cost'] ?? 0);
                        $finalTotal = $orderModel ? (float) $orderModel->final_total : $order['total'];
                    @endphp
                    <div class="summary-row">
                        <span>Subtotal ({{ count($order['items']) }} items)</span>
                        <strong>PKR {{ number_format($subtotal) }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <strong>PKR {{ number_format($shippingCost) }}</strong>
                    </div>
                    @if($discount > 0)
                        <div class="summary-row" style="color: #059669;">
                            <span>Discount</span>
                            <strong>- PKR {{ number_format($discount) }}</strong>
                        </div>
                    @endif
                    <div class="summary-row total-row">
                        <span>Total</span>
                        <strong style="color: #44240f;">PKR {{ number_format($finalTotal) }}</strong>
                    </div>
                    @if($discount > 0)
                        <div class="savings-badge">
                            <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                            <span>You saved PKR {{ number_format($discount) }} on this order</span>
                        </div>
                    @endif
                </div>

                <!-- Delivery Address Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="map-pin"></i>
                        <h3 class="tracking-card-title">Delivery Address</h3>
                    </div>
                    <div class="sidebar-address-block">
                        <span class="sidebar-address-name">{{ $billing['first_name'] ?? '' }} {{ $billing['last_name'] ?? '' }}</span>
                        <span>{{ $billing['phone'] ?? $order['phone'] ?? '' }}</span>
                        <span>
                            {{ $billing['address'] ?? '' }}@if(!empty($billing['address2'])), {{ $billing['address2'] }}@endif,
                            {{ $billing['area'] ?? '' }}, {{ $billing['city'] ?? '' }}
                        </span>
                        <span>{{ $billing['country'] ?? 'Pakistan' }}</span>
                    </div>
                </div>

                <!-- Payment Method Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="credit-card"></i>
                        <h3 class="tracking-card-title">Payment Method</h3>
                    </div>
                    <div class="sidebar-payment-block">
                        <span class="sidebar-payment-method">{{ $order['paymentMethod'] }}</span>
                        <span>
                            @if(strtolower($order['paymentMethod']) === 'cash on delivery')
                                Pay in cash upon delivery
                            @else
                                Payment processed successfully
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Need Help Card -->
                <div class="tracking-card">
                    <div class="tracking-card-header">
                        <i data-lucide="help-circle"></i>
                        <h3 class="tracking-card-title">Need Help?</h3>
                    </div>
                    <p class="help-support-desc">We're here to help you with your order. Our support team is ready to assist you.</p>
                    <div class="help-actions-stack">
                        <a href="https://wa.me/923211234567" target="_blank" class="btn-contact-support">
                            <i data-lucide="phone"></i>
                            <span>Contact Support</span>
                        </a>
                        <a href="{{ route('polani.collection') }}" class="btn-continue-shopping">
                            <i data-lucide="shopping-bag"></i>
                            <span>Continue Shopping</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
      @endif

    </div>
  </section>

  <!-- Bottom Horizontal Benefits Bar -->
  <section class="feature-bar-section">
      <div class="section-container feature-bar-container">
          <div class="feature-bar-grid">
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="award"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>100% Genuine Products</h4>
                      <p>Original and high quality</p>
                  </div>
              </div>
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="truck"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>Fast Delivery</h4>
                      <p>Across Pakistan</p>
                  </div>
              </div>
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="refresh-cw"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>Easy Returns</h4>
                      <p>Within 7 Days</p>
                  </div>
              </div>
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="shield-check"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>Secure Payments</h4>
                      <p>Safe & reliable</p>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <!-- Newsletter Pre-Footer Section -->
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Intercept Tracking Form submit to split contact input into email or phone
    const form = document.getElementById('trackOrderForm');
    const contactInput = document.getElementById('contact_input');
    const hiddenEmail = document.getElementById('hidden_email');
    const hiddenPhone = document.getElementById('hidden_phone');

    if (form && contactInput) {
        form.addEventListener('submit', () => {
            const val = contactInput.value.trim();
            if (val.includes('@')) {
                hiddenEmail.value = val;
                hiddenPhone.value = '';
            } else {
                hiddenEmail.value = '';
                hiddenPhone.value = val;
            }
            
            // Add Loading State Spinner on Button
            const submitBtn = form.querySelector('.track-submit-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.8';
                submitBtn.innerHTML = '<span style="display:inline-block; width:14px; height:14px; border:2px solid #fff; border-top-color:transparent; border-radius:50%; animation:spin 0.6s linear infinite; margin-right:8px; vertical-align:middle;"></span>Loading...';
            }
        });
    }
});
</script>
@endpush
