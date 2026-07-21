@extends('polani.layout')

@section('title', 'Order Status — Polani Fragrance')
@section('body_class', 'page-order-status')

@section('content')
  @php
    $orderedItems = $orderedItems ?? [];
    $recommendations = $recommendations ?? collect();
    $billingAddress = $billingAddress ?? [];
    $statusTone = $statusTone ?? 'pending';
    $isSuccess = $statusTone === 'success';
    $headline = $isSuccess ? 'Thank You For Your Purchase' : 'Order Received';
    $intro = $isSuccess
      ? 'Your order ' . $orderNumber . ' has been placed successfully. A confirmation email has been sent to your inbox.'
      : 'Your order ' . $orderNumber . ' has been received successfully. A confirmation email will be sent shortly.';
  @endphp

  <section class="order-confirm">
    <div class="order-confirm__glow" aria-hidden="true"></div>

    <div class="container order-confirm__shell">
      <div class="order-confirm__nav">
        <a class="order-confirm__nav-brand" href="{{ route('home') }}" style="display: flex; align-items: center; gap: 8px; flex-direction: row; text-decoration: none;">
          <img src="{{ asset('polani/assets/logos/logo-white-trans.png?v=4') }}" alt="Polani Fragrance Logo" style="height: 32px; width: auto; object-fit: contain;">
          <div style="display: flex; flex-direction: column; line-height: 1; text-align: left;">
            <span style="font-family: var(--serif); font-size: 20px; line-height: 1; color: #d9b06a;">POLANI</span>
            <small style="font-size: 9px; line-height: 1; letter-spacing: 0.2em; color: rgba(217, 176, 106, 0.7);">FRAGRANCE</small>
          </div>
        </a>
        <nav class="order-confirm__nav-links" aria-label="Order page navigation">
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('polani.collection') }}">Shop</a>
          <a href="{{ route('polani.contact') }}">Contact</a>
        </nav>
      </div>

      <x-polani.page-banner
        page-key="order-status"
        :eyebrow="$statusLabel ?? 'Order Confirmed'"
        :title="$headline"
        :subtitle="$intro"
        :cta-text="$actionLabel ?? 'Continue Shopping'"
        :cta-url="$actionUrl ?? route('polani.collection')"
        :secondary-cta-text="'Track Order'"
        :secondary-cta-url="route('polani.track-order', array_filter([
          'order_number' => $orderNumber ?? null,
          'email' => $billingAddress['email'] ?? null,
          'phone' => $billingAddress['phone'] ?? null,
        ]))"
        fallback-image="polani/assets/hero-noir-elixir.jpg"
        image-position="center center"
      />

      <div class="order-confirm__topline" aria-hidden="true"></div>

      @if(isset($message) && $message && $statusTone === 'pending')
        <div class="payment-pending-instructions" style="background: rgba(217, 176, 106, 0.05); border: 1px solid rgba(217, 176, 106, 0.2); border-radius: 16px; padding: 24px; margin-bottom: 35px; border-left: 4px solid #d9b06a; color: #f8e7d0;">
          <h3 style="font-family: var(--serif); color: #d9b06a; font-size: 1.25rem; margin-top: 0; margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-info-circle"></i> Payment Instructions
          </h3>
          <p style="margin: 0; font-size: 0.98rem; line-height: 1.6; white-space: pre-wrap;">{{ $message }}</p>
          @if($order->payment_method !== 'cash' && $order->payment_method !== 'free')
            <div style="background: rgba(217, 176, 106, 0.08); padding: 12px 16px; border-radius: 8px; font-size: 0.9rem; color: #d9b06a; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-top: 12px;">
              <i class="fab fa-whatsapp"></i> Please transfer the payment and send the screenshot on WhatsApp at +92 324 9206345 to confirm your order.
            </div>
          @endif
        </div>
      @endif

      <div class="order-confirm__grid" id="order-summary">
        <div class="order-confirm__panel order-confirm__panel--summary">
          <div class="order-confirm__panel-title">Order Summary</div>
          <div class="order-confirm__summary-list">
            <div class="order-confirm__summary-row">
              <span>Order #</span>
              <strong>{{ $orderNumber ?? ('#PF-' . $order->id) }}</strong>
            </div>
            <div class="order-confirm__summary-row">
              <span>Order Date</span>
              <strong>{{ $orderDate ?? optional($order->created_at)->format('F j, Y') }}</strong>
            </div>
            <div class="order-confirm__summary-row">
              <span>Payment Method</span>
              <strong>{{ $paymentLabel ?? ($paymentMethod->name ?? 'Polani Payment') }}</strong>
            </div>
            <div class="order-confirm__summary-row">
              <span>Estimated Delivery</span>
              <strong>{{ $estimatedDelivery ?? '3 - 5 working days' }}</strong>
            </div>
          </div>
        </div>

        <div class="order-confirm__side-stack">
          <div class="order-confirm__panel order-confirm__panel--compact">
            <div class="order-confirm__panel-title">Order Total</div>
            <div class="order-confirm__total-list">
              <div class="order-confirm__total-row">
                <span>Subtotal</span>
                <strong>Rs {{ number_format((float) ($subtotal ?? $order->total ?? 0), 0) }}</strong>
              </div>
              <div class="order-confirm__total-row">
                <span>Shipping</span>
                <strong>{{ $shippingLabel ?? 'Free' }}</strong>
              </div>
              <div class="order-confirm__total-row order-confirm__total-row--grand">
                <span>Total</span>
                <strong>Rs {{ number_format((float) ($totalAmount ?? $order->final_total ?? $order->total ?? 0), 0) }}</strong>
              </div>
            </div>
          </div>

          <div class="order-confirm__panel order-confirm__panel--compact">
            <div class="order-confirm__panel-title">Shipping Address</div>
            <div class="order-confirm__address">
              <div class="order-confirm__address-name">{{ $billingAddress['name'] ?? 'Polani Customer' }}</div>
              @if(!empty($billingAddress['lines']))
                @foreach($billingAddress['lines'] as $line)
                  <div class="order-confirm__address-line">{{ $line }}</div>
                @endforeach
              @endif
              @if(!empty($billingAddress['phone']))
                <div class="order-confirm__address-line">{{ $billingAddress['phone'] }}</div>
              @endif
              @if(!empty($billingAddress['email']))
                <div class="order-confirm__address-line">{{ $billingAddress['email'] }}</div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="order-confirm__section-head">
        <h2>Products Ordered</h2>
      </div>

      <div class="order-confirm__products">
        @forelse($orderedItems as $item)
          <article class="ordered-product">
            <div class="ordered-product__image">
              <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy">
            </div>
            <div class="ordered-product__body">
              <div class="ordered-product__name">{{ $item['name'] }}</div>
              <div class="ordered-product__meta">
                <span>Qty: {{ $item['quantity'] }}</span>
                <span>Rs {{ number_format((float) $item['line_total'], 0) }}</span>
              </div>
            </div>
          </article>
        @empty
          <div class="order-confirm__empty">No product details were found for this order.</div>
        @endforelse
      </div>

      <div class="order-confirm__section-head order-confirm__section-head--spaced">
        <h2>You May Also Like</h2>
      </div>

      <div class="order-confirm__recommendations">
        @foreach($recommendations as $product)
          <article class="recommend-card">
            <a class="recommend-card__image" href="{{ route('polani.product', $product->slug) }}">
              <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
            </a>
            <div class="recommend-card__body">
              <div class="recommend-card__name">{{ $product->name }}</div>
              <div class="recommend-card__price">Rs {{ number_format((float) $product->weekly_price, 0) }}</div>
              <form method="POST" action="{{ route('polani.cart.add', $product->slug) }}">
                @csrf
                <button type="submit" class="btn btn--gold btn--small">Add to Cart</button>
              </form>
            </div>
          </article>
        @endforeach
      </div>

      <div class="order-confirm__closing">
        <div class="order-confirm__closing-title">THANK YOU FOR CHOOSING POLANI FRAGRANCE</div>
        <div class="order-confirm__closing-sub">Crafted with passion. Bottled with elegance.</div>
        <a class="btn btn--gold btn--large" href="{{ $actionUrl ?? route('polani.collection') }}">{{ $actionLabel ?? 'Continue Shopping' }}</a>
      </div>

    </div>
  </section>
@endsection
