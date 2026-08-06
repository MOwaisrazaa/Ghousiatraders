@extends('ghousiatraders.layouts.app')

@section('title', 'Order Confirmed — Ghousia Traders')

@section('content')
@php
    $orderedItems = $orderedItems ?? [];
    $recommendations = $recommendations ?? collect();
    $billingAddress = $billingAddress ?? [];
    $statusTone = $statusTone ?? 'success';
    $isSuccess = $statusTone === 'success';
    $orderNo = $orderNumber ?? ('GT-' . now()->format('Y') . '-' . sprintf('%05d', $order->id));
    $orderDateStr = $orderDate ?? optional($order->created_at)->format('F j, Y g:i A');
    $deliveryEstimate = $estimatedDelivery ?? (now()->addDays(3)->format('M j') . ' - ' . now()->addDays(5)->format('M j, Y'));
    $custEmail = $billingAddress['email'] ?? $order->user->email ?? 'customer@example.com';
    $custPhone = $billingAddress['phone'] ?? $order->user->phone ?? '';
    $custAddressStr = !empty($billingAddress['lines']) ? implode(', ', $billingAddress['lines']) : 'Address Not Specified';
    $payMethodName = $paymentLabel ?? ($paymentMethod->name ?? 'Online Payment');
    $subtotalVal = (float) ($subtotal ?? $order->total ?? 0);
    $finalVal = (float) ($totalAmount ?? $order->final_total ?? $order->total ?? 0);
    $discountVal = (float) ($order->discount ?? 0);
    $couponCodeStr = $order->coupon_code ?? null;
    $itemCount = count($orderedItems);

    // Map order status for progress stepper
    $rawStatus = strtolower($order->status ?? 'pending');
    $stepStatus = 1; // Default 1: Order Confirmed
    if (in_array($rawStatus, ['processing', 'packed', 'preparing'])) {
        $stepStatus = 2;
    } elseif (in_array($rawStatus, ['shipped', 'dispatch', 'in_transit'])) {
        $stepStatus = 3;
    } elseif (in_array($rawStatus, ['out_for_delivery'])) {
        $stepStatus = 4;
    } elseif (in_array($rawStatus, ['completed', 'delivered', 'paid'])) {
        $stepStatus = $rawStatus === 'paid' ? 2 : 5;
    }
@endphp

<div class="gt-order-confirmation-wrapper" style="background-color: #F8F5F0; padding: 40px 0 60px; font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="section-container" style="max-width: 1240px; margin: 0 auto; padding: 0 20px;">

        <!-- 1. TOP CONFIRMATION HERO BANNER -->
        <div class="gt-confirm-hero-card" style="background: #F7F3ED; border: 1.5px solid #EAE3D9; border-radius: 20px; padding: 36px 40px; display: flex; align-items: center; justify-content: space-between; gap: 30px; margin-bottom: 30px; position: relative; overflow: hidden; box-shadow: 0 4px 20px rgba(92, 62, 33, 0.03);">
            <!-- Left Side: Check Icon + Thank You Title + Email -->
            <div style="flex: 1; max-width: 620px; z-index: 2;">
                <div style="display: flex; align-items: flex-start; gap: 20px;">
                    <div class="gt-check-circle-icon" style="width: 68px; height: 68px; border-radius: 50%; background: #2E7D32; color: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 6px 16px rgba(46, 125, 50, 0.25);">
                        <i data-lucide="check" style="width: 36px; height: 36px; stroke-width: 3;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 2.3rem; font-weight: 800; color: #3A2518; margin: 0 0 6px 0; line-height: 1.15; letter-spacing: -0.02em;">Thank You!</h1>
                        <h2 style="font-size: 1.75rem; font-weight: 700; color: #3A2518; margin: 0 0 12px 0; line-height: 1.25;">Your Order Has Been Confirmed.</h2>
                        <p style="font-size: 0.95rem; color: #66594E; margin: 0; line-height: 1.55;">
                            We have successfully received your order.<br>
                            Your order is now awaiting processing. We’ll update its status as soon as it moves to the next stage.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Lifestyle Illustration Image -->
            <div class="gt-hero-image-wrapper" style="width: 440px; height: 180px; border-radius: 16px; overflow: hidden; border: 1px solid #E6DED4; flex-shrink: 0; position: relative; box-shadow: 0 8px 24px rgba(92, 62, 33, 0.06);">
                <img src="{{ asset('ghousiatraders/assets/order_confirm_hero.jpg') }}" alt="Ghousia Traders Package and Plush Toy" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>

        @if(isset($message) && $message && $statusTone === 'pending')
            <div class="payment-pending-instructions" style="background: #FFFDF9; border: 1.5px solid #E6D7C3; border-left: 5px solid #5C3E21; border-radius: 14px; padding: 20px 24px; margin-bottom: 30px; box-shadow: 0 4px 14px rgba(92, 62, 33, 0.03);">
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #5C3E21; margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle"></i> Payment Instructions
                </h3>
                <p style="margin: 0; font-size: 0.92rem; color: #4A3A2C; line-height: 1.6; white-space: pre-line;">{{ $message }}</p>
            </div>
        @endif

        <!-- 2. MAIN CONTENT GRID (TWO COLUMNS DESKTOP) -->
        <div class="gt-order-main-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 28px; align-items: start;">

            <!-- ================= LEFT COLUMN ================= -->
            <div class="gt-order-left-column" style="display: flex; flex-direction: column; gap: 28px;">

                <!-- A. Order Information Card -->
                <div class="gt-confirm-card" style="background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <i data-lucide="file-text" style="width: 22px; height: 22px; color: #5C3E21;"></i>
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #3A2518; margin: 0;">Order Information</h3>
                    </div>

                    <!-- Structured 3-Row Grid Container (Row 1: 2 boxes, Row 2: 2 boxes, Row 3: 1 full-width box) -->
                    <div class="gt-order-info-cards-grid">
                        
                        <!-- Row 1, Box 1: Order Number -->
                        <div class="gt-order-info-card">
                            <div class="gt-card-top-row">
                                <div class="gt-card-icon-circle">
                                    <i data-lucide="package" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="gt-card-label">Order Number</div>
                                    <div class="gt-card-value">{{ $orderNo }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1, Box 2: Order Date -->
                        <div class="gt-order-info-card">
                            <div class="gt-card-top-row">
                                <div class="gt-card-icon-circle">
                                    <i data-lucide="calendar" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="gt-card-label">Order Date</div>
                                    <div class="gt-card-value">{{ $orderDateStr }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2, Box 1: Estimated Delivery -->
                        <div class="gt-order-info-card">
                            <div class="gt-card-top-row">
                                <div class="gt-card-icon-circle">
                                    <i data-lucide="truck" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="gt-card-label">Estimated Delivery</div>
                                    <div class="gt-card-value">{{ $deliveryEstimate }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2, Box 2: Payment Method -->
                        <div class="gt-order-info-card">
                            <div class="gt-card-top-row">
                                <div class="gt-card-icon-circle">
                                    <i data-lucide="credit-card" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="gt-card-label">Payment Method</div>
                                    <div class="gt-card-value">{{ $payMethodName }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: 1 Full-Width Box for Shipping Address -->
                        <div class="gt-order-info-card gt-order-info-card-full">
                            <div class="gt-card-top-row">
                                <div class="gt-card-icon-circle">
                                    <i data-lucide="map-pin" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div class="gt-card-label">Shipping Address</div>
                                    <div class="gt-card-value" style="line-height: 1.4;">{{ $custAddressStr }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- B. Order Progress Card -->
                <div class="gt-confirm-card" style="background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <i data-lucide="clock" style="width: 22px; height: 22px; color: #5C3E21;"></i>
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #3A2518; margin: 0;">Order Progress</h3>
                    </div>
                    <p style="font-size: 0.85rem; color: #7A6E65; margin: 0 0 24px 0;">We’ll keep you updated every step of the way.</p>

                    <!-- Horizontal Progress Timeline (5 Steps) -->
                    <div class="gt-progress-stepper-wrapper" style="position: relative; padding: 0 10px;">
                        <div class="gt-stepper-line" style="position: absolute; top: 22px; left: 40px; right: 40px; height: 3px; background: #EFE8DF; z-index: 1;">
                            <div class="gt-stepper-line-active" style="height: 100%; background: #5C3E21; width: {{ max(0, min(100, ($stepStatus - 1) * 25)) }}%;"></div>
                        </div>

                        <div class="gt-stepper-nodes" style="display: flex; justify-content: space-between; position: relative; z-index: 2;">
                            
                            <!-- Step 1: Order Confirmed -->
                            <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <div class="gt-step-icon {{ $stepStatus >= 1 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 1 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 1 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 1 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                    <i data-lucide="check-circle-2" style="width: 20px; height: 20px;"></i>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Order Confirmed</span>
                                <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 1 ? (optional($order->created_at)->format('M j, g:i A') ?? 'Completed') : 'Pending' }}</span>
                            </div>

                            <!-- Step 2: Packed -->
                            <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <div class="gt-step-icon {{ $stepStatus >= 2 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 2 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 2 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 2 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                    <i data-lucide="package" style="width: 20px; height: 20px;"></i>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Packed</span>
                                <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 2 ? 'Processing' : 'Pending' }}</span>
                            </div>

                            <!-- Step 3: Shipped -->
                            <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <div class="gt-step-icon {{ $stepStatus >= 3 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 3 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 3 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 3 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                    <i data-lucide="truck" style="width: 20px; height: 20px;"></i>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Shipped</span>
                                <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 3 ? 'In Transit' : 'Pending' }}</span>
                            </div>

                            <!-- Step 4: Out for Delivery -->
                            <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <div class="gt-step-icon {{ $stepStatus >= 4 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 4 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 4 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 4 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                    <i data-lucide="map-pin" style="width: 20px; height: 20px;"></i>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Out for Delivery</span>
                                <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 4 ? 'Out for Delivery' : 'Pending' }}</span>
                            </div>

                            <!-- Step 5: Delivered -->
                            <div class="gt-step-node" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                                <div class="gt-step-icon {{ $stepStatus >= 5 ? 'completed' : '' }}" style="width: 44px; height: 44px; border-radius: 50%; background: {{ $stepStatus >= 5 ? '#5C3E21' : '#FFFFFF' }}; border: 2px solid {{ $stepStatus >= 5 ? '#5C3E21' : '#DCD2C5' }}; color: {{ $stepStatus >= 5 ? '#FFFFFF' : '#A09386' }}; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                                    <i data-lucide="home" style="width: 20px; height: 20px;"></i>
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 700; color: #3A2518;">Delivered</span>
                                <span style="font-size: 0.72rem; color: #8A7E74; margin-top: 2px;">{{ $stepStatus >= 5 ? 'Delivered' : 'Pending' }}</span>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- C. We're Here for You Card -->
                <div class="gt-confirm-card" style="background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
                        <div style="flex: 1;">
                            <h3 style="font-size: 1.2rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0;">We’re Here for You</h3>
                            <p style="font-size: 0.85rem; color: #7A6E65; margin: 0 0 20px 0;">Your satisfaction is our priority.</p>

                            <!-- 4 Support Feature Blocks (2x2 Grid) -->
                            <div class="gt-support-blocks-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                
                                <div class="gt-support-block" style="display: flex; align-items: flex-start; gap: 12px; background: #FAF7F2; padding: 14px 16px; border-radius: 12px; border: 1px solid #F0E8DF;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E5DCD0; color: #5C3E21; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i data-lucide="shield-check" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 0.88rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0;">Secure Payment</h4>
                                        <p style="font-size: 0.76rem; color: #7A6E65; margin: 0; line-height: 1.3;">Your transactions are safe with us.</p>
                                    </div>
                                </div>

                                <div class="gt-support-block" style="display: flex; align-items: flex-start; gap: 12px; background: #FAF7F2; padding: 14px 16px; border-radius: 12px; border: 1px solid #F0E8DF;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E5DCD0; color: #5C3E21; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i data-lucide="refresh-cw" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 0.88rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0;">Easy Returns</h4>
                                        <p style="font-size: 0.76rem; color: #7A6E65; margin: 0; line-height: 1.3;">Hassle-free returns within 7 days.</p>
                                    </div>
                                </div>

                                <div class="gt-support-block" style="display: flex; align-items: flex-start; gap: 12px; background: #FAF7F2; padding: 14px 16px; border-radius: 12px; border: 1px solid #F0E8DF;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E5DCD0; color: #5C3E21; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i data-lucide="truck" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 0.88rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0;">Fast Delivery</h4>
                                        <p style="font-size: 0.76rem; color: #7A6E65; margin: 0; line-height: 1.3;">Quick & reliable delivery across Pakistan.</p>
                                    </div>
                                </div>

                                <div class="gt-support-block" style="display: flex; align-items: flex-start; gap: 12px; background: #FAF7F2; padding: 14px 16px; border-radius: 12px; border: 1px solid #F0E8DF;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E5DCD0; color: #5C3E21; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i data-lucide="headphones" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 0.88rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0;">Customer Support</h4>
                                        <p style="font-size: 0.76rem; color: #7A6E65; margin: 0; line-height: 1.3;">Need help? We're just a call away.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Right Lifestyle Support Image -->
                        <div class="gt-support-image-wrapper" style="width: 180px; height: 170px; border-radius: 14px; overflow: hidden; flex-shrink: 0; border: 1px solid #E6DED4; box-shadow: 0 4px 14px rgba(92, 62, 33, 0.05);">
                            <img src="{{ asset('ghousiatraders/assets/order_confirm_support.jpg') }}" alt="Ghousia Support Visual" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <!-- D. You May Also Like Section -->
                @if($recommendations && $recommendations->count() > 0)
                    <div class="gt-confirm-card" style="background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #3A2518; margin: 0 0 20px 0;">You May Also Like</h3>

                        <div class="gt-recommendations-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                            @foreach($recommendations->take(4) as $recProduct)
                                <div class="gt-rec-product-card" style="background: #FAF7F2; border: 1px solid #F0E8DF; border-radius: 14px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s, box-shadow 0.2s;">
                                    <div>
                                        <div style="width: 100%; height: 120px; background: #FFFFFF; border-radius: 10px; padding: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; overflow: hidden;">
                                            <img src="{{ asset($recProduct->image_path ?: 'ghousiatraders/assets/baby_lotion.png') }}" alt="{{ $recProduct->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                        <h4 style="font-size: 0.84rem; font-weight: 700; color: #3A2518; margin: 0 0 6px 0; line-height: 1.25; height: 2.5em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $recProduct->name }}</h4>
                                    </div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px;">
                                        <span style="font-size: 0.88rem; font-weight: 800; color: #5C3E21;">PKR {{ number_format((float) ($recProduct->weekly_price ?? 0)) }}</span>
                                        <a href="{{ route('polani.product', $recProduct->slug) }}" style="width: 32px; height: 32px; border-radius: 8px; background: #FFFFFF; border: 1px solid #E0D5C7; color: #5C3E21; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: background 0.2s;" title="View Product">
                                            <i data-lucide="shopping-cart" style="width: 16px; height: 16px;"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>


            <!-- ================= RIGHT COLUMN ================= -->
            <div class="gt-order-right-column" style="display: flex; flex-direction: column; gap: 20px;">

                <!-- A. Order Summary Card -->
                <div class="gt-confirm-card" style="background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
                    <h3 style="font-size: 1.2rem; font-weight: 700; color: #3A2518; margin: 0 0 18px 0;">Order Summary</h3>

                    <!-- Order Items List -->
                    <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px; max-height: 320px; overflow-y: auto; padding-right: 4px;">
                        @forelse($orderedItems as $item)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-bottom: 12px; border-bottom: 1px solid #F3EDE6;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 48px; height: 48px; border-radius: 8px; border: 1px solid #EFEAE3; padding: 4px; background: #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                    <div>
                                        <h4 style="font-size: 0.85rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0; line-height: 1.2; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $item['name'] }}</h4>
                                        <span style="font-size: 0.75rem; color: #7A6E65;">Qty: {{ $item['quantity'] }}</span>
                                    </div>
                                </div>
                                <span style="font-size: 0.88rem; font-weight: 700; color: #3A2518;">PKR {{ number_format((float) $item['line_total']) }}</span>
                            </div>
                        @empty
                            <div style="font-size: 0.85rem; color: #7A6E65; text-align: center; padding: 12px;">Order items details</div>
                        @endforelse
                    </div>

                    <!-- Price Calculations -->
                    <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.88rem; color: #555;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #66594E;">Subtotal ({{ $itemCount }} {{ Str::plural('item', $itemCount) }})</span>
                            <span style="font-weight: 700; color: #3A2518;">PKR {{ number_format($subtotalVal) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #66594E;">Shipping</span>
                            <span style="font-weight: 700; color: #2E7D32;">FREE</span>
                        </div>

                        @if($discountVal > 0)
                            <div style="display: flex; justify-content: space-between; color: #117A65;">
                                <span>Discount {{ $couponCodeStr ? '(' . $couponCodeStr . ')' : '' }}</span>
                                <span style="font-weight: 700;">- PKR {{ number_format($discountVal) }}</span>
                            </div>
                        @endif

                        <div style="margin: 8px 0; height: 1px; background: #EFEAE3;"></div>

                        <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; color: #3A2518;">
                            <span>Total</span>
                            <span style="color: #5C3E21;">PKR {{ number_format($finalVal) }}</span>
                        </div>
                    </div>
                </div>

                <!-- B. Action Buttons -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- 1. Track Order Button (Primary Filled) -->
                    <a href="{{ route('polani.track-order', array_filter(['order_number' => $orderNo, 'email' => $custEmail, 'phone' => $custPhone])) }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; background: #5C3E21; color: #FFFFFF; font-weight: 700; font-size: 0.95rem; border-radius: 12px; text-decoration: none; transition: background 0.2s; box-shadow: 0 4px 12px rgba(92, 62, 33, 0.15);">
                        <i data-lucide="map-pin" style="width: 18px; height: 18px;"></i>
                        Track Order
                    </a>

                    <!-- 2. Dual Stacked Buttons: Download Invoice & Print Receipt -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        @if(Route::has('user.order.details'))
                            <a href="{{ route('user.order.details', $order->id) }}" style="display: flex; align-items: center; justify-content: center; gap: 6px; padding: 12px; background: #FFFFFF; border: 1.5px solid #D5C8B8; color: #5C3E21; font-weight: 700; font-size: 0.85rem; border-radius: 10px; text-decoration: none; transition: all 0.2s;">
                                <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                                Download Invoice
                            </a>
                        @else
                            <button type="button" onclick="window.print()" style="display: flex; align-items: center; justify-content: center; gap: 6px; padding: 12px; background: #FFFFFF; border: 1.5px solid #D5C8B8; color: #5C3E21; font-weight: 700; font-size: 0.85rem; border-radius: 10px; cursor: pointer; transition: all 0.2s;">
                                <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                                Download Invoice
                            </button>
                        @endif

                        <button type="button" onclick="window.print()" style="display: flex; align-items: center; justify-content: center; gap: 6px; padding: 12px; background: #FFFFFF; border: 1.5px solid #D5C8B8; color: #5C3E21; font-weight: 700; font-size: 0.85rem; border-radius: 10px; cursor: pointer; transition: all 0.2s;">
                            <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
                            Print Receipt
                        </button>
                    </div>

                    <!-- 3. Continue Shopping Button -->
                    <a href="{{ route('polani.collection') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; background: #FFFFFF; border: 1.5px solid #D5C8B8; color: #5C3E21; font-weight: 700; font-size: 0.9rem; border-radius: 10px; text-decoration: none; transition: all 0.2s;">
                        <i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
                        Continue Shopping
                    </a>
                </div>

                <!-- C. Confirmation / Help Note Card -->
                <div class="gt-confirm-card" style="background: #FAF6F0; border: 1.5px solid #EADECF; border-radius: 16px; padding: 18px 20px; display: flex; align-items: flex-start; gap: 14px;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #F3ECE2; border: 1px solid #E5D7C5; color: #5C3E21; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="mail" style="width: 22px; height: 22px;"></i>
                    </div>
                    <div>
                        <p style="font-size: 0.85rem; color: #3A2518; margin: 0 0 4px 0; line-height: 1.4;">
                            We’ve sent an order confirmation to<br>
                            <strong style="color: #3A2518;">{{ $custEmail }}</strong>
                        </p>
                        <p style="font-size: 0.76rem; color: #7A6E65; margin: 0; line-height: 1.3;">
                            Can’t find it? Check your spam folder or contact our support team.
                        </p>
                    </div>
                </div>

            </div>

        </div>

        <!-- 3. NEWSLETTER AREA (ABOVE FOOTER) -->
        <div class="gt-confirm-newsletter-bar" style="margin-top: 40px; background: #FFFFFF; border: 1px solid #EFEAE3; border-radius: 16px; padding: 24px 32px; display: flex; align-items: center; justify-content: space-between; gap: 24px; box-shadow: 0 4px 16px rgba(92, 62, 33, 0.03);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #FAF5EE; border: 1px solid #EFE4D6; color: #5C3E21; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="mail-check" style="width: 24px; height: 24px;"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #3A2518; margin: 0 0 2px 0;">Stay Updated with Ghousia Traders</h3>
                    <p style="font-size: 0.82rem; color: #7A6E65; margin: 0;">Subscribe to our newsletter for exclusive offers, new arrivals, and parenting tips.</p>
                </div>
            </div>

            <form action="{{ route('home') }}" method="GET" style="display: flex; gap: 10px; flex: 1; max-width: 460px;">
                <input type="email" placeholder="Enter your email address" required style="flex: 1; padding: 12px 16px; border: 1px solid #D5C8B8; border-radius: 10px; outline: none; font-size: 0.88rem; background: #FFFDF9;">
                <button type="submit" style="padding: 12px 24px; background: #5C3E21; color: #FFFFFF; font-weight: 700; font-size: 0.88rem; border: none; border-radius: 10px; cursor: pointer; white-space: nowrap; transition: background 0.2s;">
                    Subscribe
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    /* Ghousia Traders 3-Row Order Information Layout */
    .gt-order-info-cards-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 16px !important;
        align-items: stretch !important;
    }

    .gt-order-info-card {
        background: #FAF7F2 !important;
        border: 1px solid #EAE1D3 !important;
        border-radius: 14px !important;
        padding: 18px 20px !important;
        box-sizing: border-box !important;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease, background 0.22s ease !important;
    }

    .gt-order-info-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(92, 62, 33, 0.08) !important;
        border-color: #D6C4AE !important;
        background: #FFFFFF !important;
    }

    .gt-order-info-card-full {
        grid-column: 1 / -1 !important;
    }

    .gt-card-top-row {
        display: flex !important;
        align-items: flex-start !important;
        gap: 14px !important;
    }

    .gt-card-icon-circle {
        width: 42px !important;
        height: 42px !important;
        border-radius: 50% !important;
        background: #F3ECE1 !important;
        border: 1px solid #E5D9C7 !important;
        color: #5C3E21 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    .gt-card-label {
        font-size: 0.72rem !important;
        font-weight: 700 !important;
        color: #8C7C6D !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        margin-bottom: 4px !important;
        line-height: 1.2 !important;
    }

    .gt-card-value {
        font-size: 0.92rem !important;
        font-weight: 800 !important;
        color: #3A2518 !important;
        line-height: 1.35 !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
    }

    /* Responsive rules for Ghousia Traders Order Confirmation Page */
    @media (max-width: 991px) {
        .gt-order-main-grid {
            grid-template-columns: 1fr !important;
        }
        .gt-confirm-hero-card {
            flex-direction: column !important;
            padding: 24px !important;
        }
        .gt-hero-image-wrapper {
            width: 100% !important;
            height: 180px !important;
        }
        .gt-recommendations-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        .gt-confirm-newsletter-bar {
            flex-direction: column !important;
            align-items: flex-start !important;
            padding: 20px !important;
        }
        .gt-confirm-newsletter-bar form {
            width: 100% !important;
            max-width: 100% !important;
        }
    }

    @media (max-width: 576px) {
        .gt-order-info-cards-grid {
            grid-template-columns: 1fr !important;
        }
        .gt-order-info-card-full {
            grid-column: auto !important;
        }
        .gt-recommendations-grid {
            grid-template-columns: 1fr !important;
        }
        .gt-support-blocks-grid {
            grid-template-columns: 1fr !important;
        }
        .gt-stepper-nodes {
            flex-wrap: wrap !important;
            gap: 16px !important;
            justify-content: center !important;
        }
        .gt-stepper-line {
            display: none !important;
        }
    }

    @media print {
        header, footer, .gt-confirm-newsletter-bar, .gt-order-right-column div:nth-child(2), .gt-hero-image-wrapper {
            display: none !important;
        }
        .gt-order-confirmation-wrapper {
            background: #fff !important;
            padding: 0 !important;
        }
        .gt-order-main-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection
