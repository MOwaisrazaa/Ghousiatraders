@extends('polani.layout')

@section('title', 'Track Order — Polani Fragrance')
@section('meta_description', 'Track your Polani order using your order number and checkout contact details.')
@section('body_class', 'page-order-status')

@push('head')
<style>
  .track-page{
    background:#0a0a0a;
    color:#f8e7d0;
    padding-bottom:56px;
  }
  .track-wrap{
    padding:40px 0 20px;
  }
  .track-card{
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(212,166,88,0.16);
    border-radius:20px;
    box-shadow:0 24px 60px rgba(0,0,0,0.35);
    padding:32px;
  }
  .track-card__title{
    font-family:'Playfair Display', serif;
    font-size:26px;
    letter-spacing:.08em;
    text-transform:uppercase;
    text-align:center;
    margin:0 0 10px;
    color:#f8e7d0;
  }
  .track-card__subtitle{
    text-align:center;
    color:rgba(248,231,208,0.65);
    margin:0 0 24px;
    line-height:1.7;
    font-size:0.95rem;
  }
  .track-form{
    display:grid;
    grid-template-columns:minmax(0,1fr) auto;
    gap:16px;
    align-items:start;
  }
  .track-form__fields{
    display:grid;
    gap:12px;
  }
  .track-form__input{
    width:100%;
    border-radius:10px;
    border:1px solid rgba(212,166,88,0.22);
    background:rgba(255,255,255,0.05);
    color:#f8e7d0;
    padding:13px 16px;
    outline:none;
    font-size:14px;
    box-sizing:border-box;
    transition: all 0.2s;
  }
  .track-form__input::placeholder {
    color: rgba(248, 231, 208, 0.3);
  }
  .track-form__input:focus{
    border-color:#d4a658;
    box-shadow:0 0 0 3px rgba(212,166,88,0.12);
    background:rgba(255,255,255,0.07);
  }
  .track-form__actions{
    display:flex;
    align-items:stretch;
  }
  .track-form__actions .btn{
    min-width:160px;
    min-height:48px;
    border-radius:10px;
  }
  .track-note{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
    margin-top:14px;
    color:rgba(248,231,208,0.55);
    font-size:13px;
  }
  .track-note__icon{
    width:18px;
    height:18px;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:1px solid rgba(212,166,88,0.3);
    color:#d4a658;
    font-size:12px;
    line-height:1;
  }
  .track-status{
    padding:32px 0 10px;
  }
  .track-section-title{
    font-family:'Playfair Display', serif;
    font-size:26px;
    letter-spacing:.08em;
    text-transform:uppercase;
    text-align:center;
    margin:0 0 24px;
    color:#f8e7d0;
  }
  .track-steps{
    display:grid;
    grid-template-columns:repeat(4, minmax(0,1fr));
    gap:20px;
    align-items:start;
    background:rgba(255,255,255,0.02);
    border:1px solid rgba(212,166,88,0.12);
    border-radius:20px;
    padding:30px 24px;
  }
  .track-step{
    text-align:center;
    position:relative;
  }
  .track-step__icon{
    width:64px;
    height:64px;
    border-radius:50%;
    margin:0 auto 14px;
    border:2px solid rgba(212,166,88,0.3);
    background:#111;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    color:rgba(248, 231, 208, 0.45);
    box-shadow:inset 0 0 0 5px rgba(255,255,255,0.02);
    transition: all 0.3s;
  }
  .track-step.is-active .track-step__icon{
    background:linear-gradient(135deg, #d4a658, #9d6f20);
    color:#111;
    border-color:#d4a658;
    box-shadow:0 0 15px rgba(212,166,88,0.35);
  }
  .track-step__label{
    font-family:'Playfair Display', serif;
    font-size:15px;
    letter-spacing:.18em;
    text-transform:uppercase;
    color:#f8e7d0;
    font-weight:600;
  }
  .track-step__meta{
    margin-top:8px;
    font-size:13px;
    line-height:1.6;
    color:rgba(248,231,208,0.6);
  }
  .track-grid{
    display:grid;
    grid-template-columns:minmax(0,1.2fr) minmax(300px,.8fr);
    gap:20px;
    align-items:start;
    margin-top:24px;
  }
  .track-panel{
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(212,166,88,0.16);
    border-radius:18px;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
    padding:24px;
  }
  .track-panel__title{
    font-family:'Playfair Display', serif;
    font-size:20px;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:#d4a658;
    margin:0 0 16px;
    border-bottom:1px solid rgba(212,166,88,0.15);
    padding-bottom:10px;
  }
  .track-summary{
    display:grid;
    gap:10px;
  }
  .track-summary__row{
    display:flex;
    justify-content:space-between;
    gap:12px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    padding-bottom:10px;
    color:rgba(248,231,208,0.7);
    font-size:0.92rem;
  }
  .track-summary__row strong{
    color:#f8e7d0;
    text-align:right;
  }
  .track-address{
    display:grid;
    gap:8px;
    color:rgba(248,231,208,0.7);
    line-height:1.65;
    font-size:0.92rem;
  }
  .track-address__name{
    font-family:'Playfair Display', serif;
    font-size:18px;
    color:#f8e7d0;
    font-weight:600;
  }
  .track-list{
    display:grid;
    gap:14px;
  }
  .track-list__item{
    display:grid;
    grid-template-columns:88px minmax(0,1fr) auto;
    gap:16px;
    align-items:center;
    padding:16px;
    border-radius:14px;
    border:1px solid rgba(212,166,88,0.15);
    background:rgba(255,255,255,0.02);
    text-decoration:none;
    color:inherit;
    transition: all 0.2s;
  }
  .track-list__item:hover{
    border-color: #d4a658;
    background: rgba(212,166,88,0.05);
  }
  .track-list__item img{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid rgba(212,166,88,0.15);
  }
  .track-list__name{
    font-family:'Playfair Display', serif;
    font-size:18px;
    color:#f8e7d0;
    margin-bottom:3px;
  }
  .track-list__meta{
    color:rgba(248,231,208,0.6);
    font-size:13px;
    line-height:1.6;
  }
  .track-help{
    margin-top:24px;
    padding:28px;
    background:rgba(255,255,255,0.03);
    border:1px solid rgba(212,166,88,0.16);
    border-radius:20px;
    box-shadow:0 12px 30px rgba(0,0,0,.15);
  }
  .track-help__title{
    font-family:'Playfair Display', serif;
    font-size:24px;
    text-align:center;
    text-transform:uppercase;
    letter-spacing:.08em;
    margin:0 0 12px;
    color:#f8e7d0;
  }
  .track-help__text{
    text-align:center;
    color:rgba(248,231,208,0.65);
    margin:0 0 24px;
    line-height:1.7;
    font-size:0.95rem;
  }
  .track-help__grid{
    display:grid;
    grid-template-columns:repeat(3, minmax(0,1fr));
    gap:16px;
  }
  .track-help__item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:16px;
    border-radius:12px;
    border:1px solid rgba(212,166,88,0.15);
    background:rgba(255,255,255,0.02);
  }
  .track-help__icon{
    width:42px;
    height:42px;
    border-radius:50%;
    flex:0 0 auto;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(212,166,88,0.12);
    color:#d4a658;
  }
  .track-help__label{
    font-family:'Playfair Display', serif;
    font-size:16px;
    color:#f8e7d0;
    margin-bottom:2px;
    font-weight:600;
  }
  .track-help__meta{
    color:rgba(248,231,208,0.6);
    font-size:13px;
    line-height:1.5;
  }
  .track-note--error{
    justify-content:flex-start;
    margin-top:14px;
    color:#f07080;
    font-weight:600;
  }
  .track-mini{
    display:grid;
    gap:12px;
    padding: 20px;
    border: 1px solid rgba(212,166,88,0.15);
    background: rgba(255,255,255,0.02);
    border-radius: 14px;
  }
  .track-mini__title{
    font-family:'Playfair Display', serif;
    text-transform:uppercase;
    letter-spacing:.08em;
    font-size:18px;
    color:#d4a658;
    margin:0;
    font-weight:600;
  }
  .track-mini__text{
    color:rgba(248,231,208,0.7);
    line-height:1.7;
    margin:0;
    font-size:0.92rem;
  }
  @media (max-width: 1100px){
    .track-hero__inner,
    .track-grid{
      grid-template-columns:1fr;
    }
    .track-hero__art{
      justify-self:center;
      max-width:320px;
    }
    .track-steps{
      grid-template-columns:repeat(2, minmax(0,1fr));
    }
    .track-help__grid{
      grid-template-columns:1fr;
    }
    .track-form{
      grid-template-columns:1fr;
    }
  }
  @media (max-width: 640px){
    .track-hero{
      min-height:auto;
    }
    .track-hero__inner{
      padding:30px 0 24px;
    }
    .track-hero__title{
      font-size:34px;
    }
    .track-steps{
      grid-template-columns:1fr;
    }
    .track-list__item{
      grid-template-columns:72px 1fr;
    }
    .track-list__price{
      grid-column:2 / -1;
    }
    .track-help,
    .track-panel,
    .track-card{
      padding:18px;
    }
  }
</style>
@endpush

@section('content')
  @php
    $timeline = [
      ['label' => 'Placed', 'desc' => 'Order received and waiting for confirmation.', 'icon' => '1'],
      ['label' => 'Confirmed', 'desc' => 'Payment confirmed and order is being prepared.', 'icon' => '2'],
      ['label' => 'Shipped', 'desc' => 'Your order is packed and scheduled for dispatch.', 'icon' => '3'],
      ['label' => 'Delivered', 'desc' => 'Order successfully delivered.', 'icon' => '4'],
    ];
    $isAuthenticated = $isAuthenticated ?? auth()->check();
    $orders = $orders ?? collect();
  @endphp

  <section class="track-page">
    <x-polani.page-banner
      page-key="track-order"
      eyebrow="ORDER STATUS"
      title="Order Tracking"
      subtitle="Track your order status and stay updated every step of the way."
      fallback-image="polani/assets/home_banner_1.jpeg"
      image-position="center center"
    />

    <div class="container track-wrap">
      <div class="track-card">
        <div class="track-card__title">Track Your Order</div>
        <p class="track-card__subtitle">
          {{ $isAuthenticated ? 'Your orders are listed below. Choose any order to see its latest tracking status.' : 'Enter your Order ID / Tracking Number to check the status of your order.' }}
        </p>

        @if($isAuthenticated)
          @if($orders->isNotEmpty())
            <div class="track-list">
              @foreach($orders as $trackedOrder)
                <a class="track-list__item" href="{{ route('polani.track-order', ['order_number' => $trackedOrder['orderNumber']]) }}">
                  <img src="{{ $trackedOrder['items'][0]['image'] ?? asset('polani/assets/product-noir-elixir.jpg') }}" alt="{{ $trackedOrder['orderNumber'] }}">
                  <div class="track-list__body">
                    <div class="track-list__name">{{ $trackedOrder['orderNumber'] }}</div>
                    <div class="track-list__meta">
                      Status: {{ $trackedOrder['statusLabel'] }}<br>
                      {{ $trackedOrder['statusStage'] }}<br>
                      Delivery: {{ $trackedOrder['deliveryWindow'] }}
                    </div>
                  </div>
                  <div class="track-list__price">
                    <div class="status-pill @if($trackedOrder['status'] === 'completed') status-pill--success @elseif(in_array($trackedOrder['status'], ['failed', 'rejected'])) status-pill--danger @else status-pill--pending @endif">
                      {{ $trackedOrder['statusLabel'] }}
                    </div>
                  </div>
                </a>
              @endforeach
            </div>
          @else
            <div class="track-mini" style="text-align:center; padding: 20px 0;">
              <p class="track-mini__title">No Orders Yet</p>
              <p class="track-mini__text">You haven’t placed any orders yet. Start shopping to see tracking here.</p>
              <div style="margin-top: 14px;">
                <a class="btn btn--primary" href="{{ route('polani.collection') }}">Shop Now</a>
              </div>
            </div>
          @endif
        @else
          <form class="track-form" method="GET" action="{{ route('polani.track-order') }}">
            <div class="track-form__fields">
              <input class="track-form__input" type="text" name="order_number" placeholder="Enter your Order ID / Tracking Number" value="{{ $query['order_number'] ?? '' }}" required />
              <input class="track-form__input" type="email" name="email" placeholder="Email used at checkout" value="{{ $query['email'] ?? '' }}" />
              <input class="track-form__input" type="text" name="phone" placeholder="Phone number used at checkout" value="{{ $query['phone'] ?? '' }}" />
            </div>
            <div class="track-form__actions">
              <button class="btn btn--primary" type="submit">Track Order</button>
            </div>
          </form>

          <div class="track-note">
            <span class="track-note__icon">i</span>
            <span>You can find your Order ID in the order confirmation email we sent you.</span>
          </div>

          @if($error)
            <div class="notice track-note--error">{{ $error }}</div>
          @endif
        @endif
      </div>

      <div class="track-status">
        <div class="track-section-title">Order Status</div>
        <div class="track-steps">
          @foreach($timeline as $index => $step)
            <div class="track-step @if($order && $order['statusStep'] >= $index + 1) is-active @elseif(!$order && $index === 0) is-active @endif">
              <div class="track-step__icon">{{ $step['icon'] }}</div>
              <div class="track-step__label">{{ $step['label'] }}</div>
              <div class="track-step__meta">{{ $step['desc'] }}</div>
            </div>
          @endforeach
        </div>

        @if($order)
          @if($order['status'] === 'pending' && in_array(strtolower($order['paymentMethod']), ['jazzcash', 'easypaisa', 'bank transfer']))
            @php
              $pmKey = str_replace(' ', '', strtolower($order['paymentMethod']));
              // Normalize Bank Transfer to matches key in DB
              if ($pmKey === 'banktransfer') {
                  $pmKey = 'banktransfer';
              }
              $paymentMethodObj = \App\Models\PaymentMethod::where('key', $pmKey)->first();
              $instructions = $paymentMethodObj ? $paymentMethodObj->instructions : '';
            @endphp
            @if($instructions)
              <div class="track-card" style="margin-top:24px; border-left: 4px solid #d4a658; background: rgba(212,166,88,0.03); border-color: rgba(212,166,88,0.25);">
                <div class="track-card__title" style="margin-bottom:12px; color: #d4a658; font-family: var(--serif); text-transform: uppercase; letter-spacing: 0.05em; font-size: 1.1rem; text-align: left;">
                  <i class="fas fa-info-circle"></i> Payment Instructions
                </div>
                <p style="color:#f8e7d0; font-size: 0.95rem; line-height: 1.6; margin: 0 0 12px; white-space: pre-wrap; text-align: left;">{{ $instructions }}</p>
                <div style="background: rgba(212,166,88,0.08); padding: 12px 16px; border-radius: 8px; font-size: 0.9rem; color: #d4a658; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-align: left; width: 100%;">
                  <i class="fab fa-whatsapp"></i> Please transfer the payment and send the screenshot on WhatsApp at +92 324 9206345 to confirm your order.
                </div>
              </div>
            @endif
          @endif

          <div class="track-card" style="margin-top:24px;">
            <div class="track-card__title" style="margin-bottom:18px;">Order Details</div>
            <div class="track-grid" style="margin-top:0;">
              <div class="track-panel">
                <div class="track-panel__title">Order Details</div>
                <div class="track-summary">
                  <div class="track-summary__row"><span>Order ID</span><strong>{{ $order['orderNumber'] }}</strong></div>
                  <div class="track-summary__row"><span>Order Date</span><strong>{{ $order['orderDate'] }}</strong></div>
                  <div class="track-summary__row"><span>Payment Method</span><strong>{{ $order['paymentMethod'] }}</strong></div>
                  <div class="track-summary__row"><span>Status</span><strong>{{ $order['statusLabel'] }}</strong></div>
                  <div class="track-summary__row"><span>Estimated Delivery</span><strong>{{ $order['deliveryWindow'] }}</strong></div>
                  <div class="track-summary__row"><span>Total Amount</span><strong>Rs {{ number_format((float) $order['total']) }}</strong></div>
                </div>
              </div>

              <div class="track-panel">
                <div class="track-panel__title">Shipping Address</div>
                <div class="track-address">
                  <div class="track-address__name">{{ $order['email'] ?? 'Email not available' }}</div>
                  <div>{{ $order['phone'] ?? 'Phone not available' }}</div>
                  <div>{{ $order['address'] ?: 'Address not available' }}</div>
                </div>
              </div>
            </div>
          </div>

          @if(!empty($order['items']))
            <div class="track-card" style="margin-top:24px;">
              <div class="track-card__title" style="margin-bottom:24px; text-align:left; border-bottom:1px solid rgba(212,166,88,0.15); padding-bottom:12px;">Items in this Order</div>
              <div class="track-list" style="display:flex; flex-direction:column; gap:24px;">
                @foreach($order['items'] as $item)
                  <div class="track-item-wrapper" style="border: 1px solid rgba(212,166,88,0.15); border-radius: 16px; background: rgba(255,255,255,0.02); padding: 20px; display: flex; flex-direction: column; gap: 20px;">
                    <div class="track-list__item" style="border:none; background:transparent; padding:0; display:grid; grid-template-columns:88px minmax(0,1fr) auto; gap:16px; align-items:center;">
                      <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" style="width:80px; height:80px; object-fit:cover; border-radius:12px; border: 1px solid rgba(212,166,88,0.15);">
                      <div class="track-list__body">
                        <div class="track-list__name" style="font-family:'Playfair Display', serif; font-size:1.15rem; color:#f8e7d0; margin-bottom:4px;">{{ $item['name'] }}</div>
                        <div class="track-list__meta" style="color:rgba(248,231,208,0.6); font-size:0.88rem;">Quantity: {{ $item['quantity'] }} &bull; Price: Rs {{ number_format((float) $item['price']) }}</div>
                      </div>
                      <div class="track-list__price" style="font-family:'Playfair Display', serif; font-size:1.2rem; color:#d4a658; font-weight:600;">
                        Rs {{ number_format((float) $item['total']) }}
                      </div>
                    </div>
                    
                    {{-- Mini item-specific tracking status bar --}}
                    <div class="item-tracking" style="padding-top:10px; border-top:1px dashed rgba(212,166,88,0.12);">
                      <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; color:#d4a658; margin-bottom:12px; font-weight:700;">Item Delivery Progress</div>
                      <div class="item-track-bar" style="display:flex; justify-content:space-between; position:relative; padding:0 10px;">
                        {{-- Background Line --}}
                        <div style="position:absolute; top:8px; left:15px; right:15px; height:2px; background:rgba(255,255,255,0.1); z-index:1;"></div>
                        {{-- Active Progress Line --}}
                        @php
                          $progressPercent = match((int)$order['statusStep']) {
                            1 => 0,
                            2 => 33,
                            3 => 66,
                            4 => 100,
                            default => 0
                          };
                        @endphp
                        <div style="position:absolute; top:8px; left:15px; width:calc({{ $progressPercent }}% - 30px); height:2px; background:#d4a658; z-index:2; transition:width 0.4s ease;"></div>
                        
                        {{-- Steps --}}
                        @foreach($timeline as $idx => $step)
                          @php
                            $isStepActive = ($order['statusStep'] >= $idx + 1);
                          @endphp
                          <div style="display:flex; flex-direction:column; align-items:center; text-align:center; position:relative; z-index:3; width:60px;">
                            <div style="width:18px; height:18px; border-radius:50%; background:{{ $isStepActive ? '#d4a658' : '#222' }}; border:2px solid {{ $isStepActive ? '#d4a658' : 'rgba(212,166,88,0.3)' }}; box-shadow:{{ $isStepActive ? '0 0 8px rgba(212,166,88,0.5)' : 'none' }}; transition:all 0.3s; display:flex; align-items:center; justify-content:center;">
                              @if($isStepActive)
                                <span style="width:6px; height:6px; border-radius:50%; background:#111;"></span>
                              @endif
                            </div>
                            <span style="font-size:0.75rem; color:{{ $isStepActive ? '#f8e7d0' : 'rgba(248,231,208,0.4)' }}; font-weight:{{ $isStepActive ? '700' : '400' }}; margin-top:6px; transition:color 0.3s;">{{ $step['label'] }}</span>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        @else
          <div class="track-grid">
            <div class="track-panel">
              <div class="track-panel__title">Order Status</div>
              <div class="track-mini">
                <p class="track-mini__title">Placed</p>
                <p class="track-mini__text">Your order is received by Polani.</p>
                <p class="track-mini__title">Processing</p>
                <p class="track-mini__text">We prepare and pack your fragrance order.</p>
                <p class="track-mini__title">On the Way</p>
                <p class="track-mini__text">Your parcel is handed over to the courier.</p>
              </div>
            </div>
            <div class="track-panel">
              <div class="track-panel__title">Order Details</div>
              <p class="track-mini__text">Enter your details above to see the exact tracking stage, your delivery window, and order details.</p>
            </div>
          </div>
        @endif

        <div class="track-help">
          <div class="track-help__title">Need Help?</div>
          <p class="track-help__text">If you have any questions about your order, our support team is here to help you.</p>
          <div class="track-help__grid">
            <div class="track-help__item">
              <div class="track-help__icon" aria-hidden="true" data-icon="headset"></div>
              <div>
                <div class="track-help__label">Contact Us</div>
                <div class="track-help__meta">We’re here to assist you</div>
              </div>
            </div>
            <div class="track-help__item">
              <div class="track-help__icon" aria-hidden="true" data-icon="mail"></div>
              <div>
                <div class="track-help__label">Email Us</div>
                <div class="track-help__meta">polanifragnance@gmail.com</div>
              </div>
            </div>
            <div class="track-help__item">
              <div class="track-help__icon" aria-hidden="true" data-icon="clock"></div>
              <div>
                <div class="track-help__label">Business Hours</div>
                <div class="track-help__meta">Mon - Sat: 10:00 AM - 8:00 PM</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
