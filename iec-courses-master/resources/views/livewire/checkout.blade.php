<div class="checkout-body-wrapper" data-checkout-page>
    <section class="checkout-body-section" style="background-color: var(--bg-light); padding: 40px 0 60px;">
        <div class="section-container">
            @if(session()->has('error'))
                <div style="background-color: #FDEDEC; border: 1px solid #F5B7B1; color: #78281F; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="createOrder">
                <div class="checkout-layout-grid">
                    
                    <!-- Left Columns: Form Fields -->
                    <div class="checkout-forms-column">
                        
                        <!-- 1. Contact Information -->
                        <div class="checkout-card">
                            <div class="card-section-header">
                                <i data-lucide="user" class="header-icon"></i>
                                <h3>Contact Information</h3>
                            </div>
                            <div class="form-grid grid-3col">
                                <div class="form-group">
                                    <label for="fullName">Full Name <span class="required">*</span></label>
                                    <input type="text" id="fullName" wire:model.defer="fullName" placeholder="Enter your full name" required>
                                    @error('fullName') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="emailAddress">Email Address <span class="required">*</span></label>
                                    <input type="email" id="emailAddress" wire:model.defer="email" placeholder="Enter your email address" required>
                                    @error('email') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number <span class="required">*</span></label>
                                    <input type="tel" id="phoneNumber" wire:model.defer="phone" placeholder="03XX XXXXXXX" required>
                                    @error('phone') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 2. Shipping Address -->
                        <div class="checkout-card">
                            <div class="card-section-header">
                                <i data-lucide="map-pin" class="header-icon"></i>
                                <h3>Shipping Address</h3>
                            </div>
                            <div class="form-grid grid-2col">
                                <div class="form-group">
                                    <label for="address1">Address Line 1 <span class="required">*</span></label>
                                    <input type="text" id="address1" wire:model.defer="address" placeholder="House number, street name" required>
                                    @error('address') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address2">Address Line 2 (Optional)</label>
                                    <input type="text" id="address2" wire:model.defer="address2" placeholder="Apartment, suite, unit, etc.">
                                </div>
                            </div>
                            <div class="form-grid grid-3col" style="margin-top: 16px;">
                                <div class="form-group">
                                    <label for="city">City <span class="required">*</span></label>
                                    <input type="text" id="city" wire:model.defer="city" placeholder="Enter your city" required>
                                    @error('city') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="area">Area <span class="required">*</span></label>
                                    <input type="text" id="area" wire:model.defer="area" placeholder="Enter your area" required>
                                    @error('area') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="postalCode">Postal Code <span class="required">*</span></label>
                                    <input type="text" id="postalCode" wire:model.defer="postalCode" placeholder="Enter postal code" required>
                                    @error('postalCode') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="form-grid grid-2col" style="margin-top: 16px;">
                                <div class="form-group">
                                    <label for="country">Country / Region <span class="required">*</span></label>
                                    <select id="country" wire:model.live="country" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-sm); outline: none;" required>
                                        <option value="PK">Pakistan</option>
                                        <option value="AE">United Arab Emirates</option>
                                        <option value="SA">Saudi Arabia</option>
                                    </select>
                                    @error('country') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="checkbox-group" style="margin-top: 16px;">
                                <label class="checkbox-label" style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--text-dark);">
                                    <input type="checkbox" id="useBilling" wire:model="useBilling">
                                    Use as billing address
                                </label>
                            </div>
                        </div>

                        <!-- 3. Delivery Method -->
                        <div class="checkout-card">
                            <div class="card-section-header">
                                <i data-lucide="truck" class="header-icon"></i>
                                <h3>Delivery Method</h3>
                            </div>
                            <div class="delivery-options-grid">
                                <!-- Option 1: Standard -->
                                <label class="delivery-option-card {{ $deliveryMethod === 'standard' ? 'active' : '' }}">
                                    <input type="radio" name="deliveryMethod" wire:model.live="deliveryMethod" value="standard">
                                    <span class="delivery-details">
                                        <span class="opt-title">Standard Delivery</span>
                                        <span class="opt-desc">Free on orders over PKR 5,000</span>
                                        <span class="opt-time">3-5 business days</span>
                                    </span>
                                    <span class="opt-price">FREE</span>
                                </label>

                                <!-- Option 2: Express -->
                                <label class="delivery-option-card {{ $deliveryMethod === 'express' ? 'active' : '' }}">
                                    <input type="radio" name="deliveryMethod" wire:model.live="deliveryMethod" value="express">
                                    <span class="delivery-details">
                                        <span class="opt-title">Express Delivery</span>
                                        <span class="opt-desc">Get your order faster</span>
                                        <span class="opt-time">1-2 business days</span>
                                    </span>
                                    <span class="opt-price">PKR 250</span>
                                </label>

                                <!-- Option 3: Pickup -->
                                <label class="delivery-option-card {{ $deliveryMethod === 'pickup' ? 'active' : '' }}">
                                    <input type="radio" name="deliveryMethod" wire:model.live="deliveryMethod" value="pickup">
                                    <span class="delivery-details">
                                        <span class="opt-title">Store Pickup</span>
                                        <span class="opt-desc">Pick up from our store</span>
                                        <span class="opt-time">Ready in 24 hours</span>
                                    </span>
                                    <span class="opt-price">FREE</span>
                                </label>
                            </div>
                        </div>

                        <!-- 4. Payment Method -->
                        <div class="checkout-card">
                            <div class="card-section-header">
                                <i data-lucide="credit-card" class="header-icon"></i>
                                <h3>Payment Method</h3>
                            </div>
                            <div class="payment-method-split">
                                @php
                                    $hasCod = collect($paymentMethods)->contains('key', 'cod');
                                    $hasCard = collect($paymentMethods)->contains('key', 'card');
                                    $hasBank = collect($paymentMethods)->contains('key', 'bank');
                                @endphp

                                <!-- Left split: radio methods -->
                                <div class="payment-radios-column">
                                    @if($hasCod)
                                        <label class="payment-radio-card {{ $paymentMethod === 'cod' ? 'active' : '' }}" id="codMethod">
                                            <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="cod">
                                            <div class="pay-logo-wrapper">
                                                <svg class="pay-logo cod-logo" viewBox="0 0 160 50" xmlns="http://www.w3.org/2000/svg" style="width:160px; height:38px;">
                                                    <rect width="160" height="50" rx="6" fill="#F4F6F6" stroke="#D5D8DC" stroke-width="1"/>
                                                    <path d="M25 15 H42 V32 H25 Z" fill="#5C3E21"/>
                                                    <path d="M42 20 H49 L54 26 V32 H42 Z" fill="#C29F74"/>
                                                    <circle cx="30" cy="35" r="4" fill="#333"/>
                                                    <circle cx="48" cy="35" r="4" fill="#333"/>
                                                    <rect x="65" y="12" width="75" height="26" rx="4" fill="#2ECC71"/>
                                                    <text x="102" y="30" font-family="'Plus Jakarta Sans', sans-serif" font-weight="800" font-size="15" fill="#FFF" text-anchor="middle">COD</text>
                                                </svg>
                                            </div>
                                        </label>
                                    @endif

                                    @if($hasCard)
                                        <label class="payment-radio-card {{ $paymentMethod === 'card' ? 'active' : '' }}" id="cardMethod">
                                            <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="card">
                                            <div class="pay-logo-wrapper">
                                                <div class="card-logos-flex" style="display:flex; gap: 8px;">
                                                    <svg class="pay-logo visa-logo" viewBox="0 0 75 50" xmlns="http://www.w3.org/2000/svg" style="width:70px; height:38px;">
                                                        <rect width="75" height="50" rx="6" fill="#FFFFFF" stroke="#D5D8DC" stroke-width="1"/>
                                                        <path d="M12 18 L18 34 H23 L29 18 H24 L21.5 29.5 L19 18 H12 Z" fill="#1A1F71"/>
                                                        <path d="M30 18 H34 V34 H30 Z" fill="#1A1F71"/>
                                                        <path d="M43.5 19.5 C42 18.5 40 18 38 18 C34 18 31.5 20 31.5 23.5 C31.5 28 37.5 28 37.5 30.5 C37.5 31.5 35.5 32 34 32 C32 32 30 31 29 30.5 L28 33.5 C29.5 34.5 32 35 34 35 C38.5 35 41.5 33 41.5 29.5 C41.5 25 35.5 24.5 35.5 22.5 C35.5 21.5 37 21 38.5 21 C40.5 21 42.5 21.5 43.5 22.5 L44.5 19.5 Z" fill="#1A1F71"/>
                                                        <path d="M52.5 18 H49 L42.5 34 H47.5 L48.5 31.5 H54.5 L55 34 H60 L56 18 H52.5 Z M50 28 L51.5 23 L53.5 28 H50 Z" fill="#1A1F71"/>
                                                        <path d="M12 18 L15 26 L16 18 Z" fill="#F7B600"/>
                                                    </svg>
                                                    <svg class="pay-logo mastercard-logo" viewBox="0 0 75 50" xmlns="http://www.w3.org/2000/svg" style="width:70px; height:38px;">
                                                        <rect width="75" height="50" rx="6" fill="#FFFFFF" stroke="#D5D8DC" stroke-width="1"/>
                                                        <circle cx="31" cy="25" r="14" fill="#EB001B" opacity="0.9"/>
                                                        <circle cx="44" cy="25" r="14" fill="#F79E1B" opacity="0.9"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </label>
                                    @endif

                                    @if($hasBank)
                                        <label class="payment-radio-card {{ $paymentMethod === 'bank' ? 'active' : '' }}" id="bankMethod">
                                            <input type="radio" name="paymentMethod" wire:model.live="paymentMethod" value="bank">
                                            <div class="pay-logo-wrapper">
                                                <svg class="pay-logo bank-logo" viewBox="0 0 160 50" xmlns="http://www.w3.org/2000/svg" style="width:160px; height:38px;">
                                                    <rect width="160" height="50" rx="6" fill="#F4F6F6" stroke="#D5D8DC" stroke-width="1"/>
                                                    <path d="M20 38 H50 V40 H20 Z M22 25 H26 V36 H22 Z M30 25 H34 V36 H30 Z M38 25 H42 V36 H38 Z M46 25 H50 V36 H46 Z M20 22 L35 12 L50 22 Z" fill="#8C1D40"/>
                                                     <text x="65" y="30" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700" font-size="11" fill="#8C1D40" letter-spacing="0.5px">BANK TRANSFER</text>
                                                </svg>
                                            </div>
                                        </label>
                                    @endif
                                </div>

                                <!-- Right split: card details or instructions -->
                                <div class="payment-details-column">
                                    @if($paymentMethod === 'card')
                                        <div id="cardDetailsColumn">
                                            <div class="form-group">
                                                <label for="cardName">Cardholder Name <span class="required">*</span></label>
                                                <input type="text" id="cardName" wire:model.defer="cardName" placeholder="Enter name on card" required>
                                            </div>
                                            <div class="form-group" style="margin-top: 12px;">
                                                <label for="cardNumber">Card Number <span class="required">*</span></label>
                                                <div class="card-input-wrapper" style="position: relative;">
                                                    <input type="text" id="cardNumber" wire:model.defer="cardNumber" placeholder="1234 5678 9012 3456" required>
                                                </div>
                                            </div>
                                            <div class="form-grid grid-2col" style="margin-top: 12px;">
                                                <div class="form-group">
                                                    <label for="expiryDate">Expiry Date <span class="required">*</span></label>
                                                    <input type="text" id="expiryDate" wire:model.defer="expiryDate" placeholder="MM / YY" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="cvv">CVV <span class="required">*</span></label>
                                                    <input type="text" id="cvv" wire:model.defer="cvv" placeholder="123" required>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $selectedMethodObj = collect($paymentMethods)->firstWhere('key', $paymentMethod);
                                        @endphp
                                        @if($selectedMethodObj && $selectedMethodObj->instructions)
                                            <div class="payment-instructions-wrapper" style="padding: 16px; background-color: #FDFEFE; border: 1px solid #D5D8DC; border-left: 4px solid #5C3E21; border-radius: var(--radius-sm);">
                                                <h4 style="font-size: 0.85rem; font-weight: 700; color: #5C3E21; margin-bottom: 6px;">Payment Instructions</h4>
                                                <p style="font-size: 0.8rem; color: var(--text-dark); line-height: 1.4; white-space: pre-line;">{{ $selectedMethodObj->instructions }}</p>
                                            </div>
                                        @else
                                            <div style="font-size: 0.85rem; color: var(--text-muted); text-align: center; padding: 20px;">
                                                Please select a payment method on the left to proceed.
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @error('paymentMethod') <div style="color: #E11D48; font-size: 0.75rem; margin-top: 8px;">{{ $message }}</div> @enderror
                        </div>

                        <!-- 5. Order Notes -->
                        <div class="checkout-card">
                            <div class="card-section-header">
                                <i data-lucide="file-text" class="header-icon"></i>
                                <h3>Order Notes <span style="font-weight: normal; font-size: 0.85rem; color: var(--text-muted);">(Optional)</span></h3>
                            </div>
                            <div class="form-group">
                                <textarea id="orderNotes" wire:model.defer="orderNotes" placeholder="Add any special instructions for delivery..." maxlength="200" style="width:100%; height:100px; padding:12px; border:1px solid var(--border-color); border-radius:var(--radius-sm); outline:none; resize:none; font-family:inherit;"></textarea>
                                <span class="char-count" style="display:block; text-align:right; font-size:0.75rem; color:var(--text-muted); margin-top:4px;">Max 200 characters</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Order Summary & Action Buttons -->
                    <div class="checkout-summary-column">
                        <div class="summary-card" style="position:sticky; top:100px; padding: 24px;">
                            <h2 class="summary-title" style="font-size:1.25rem; margin-bottom:18px;">Order Summary</h2>
                            
                            <!-- Checkout mini thumbnails list -->
                            <div class="checkout-summary-products-list" style="display:flex; flex-direction:column; gap:14px; margin-bottom: 20px;">
                                @foreach($cartItems as $item)
                                    <div class="checkout-mini-prod-row" style="display:flex; align-items:center; gap:12px; justify-content:space-between;">
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <div class="mini-prod-img" style="width:48px; height:48px; border:1px solid var(--border-color); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; padding:4px; background:#fff; flex-shrink:0;">
                                                @if(!empty($item['image_path']))
                                                    <img src="{{ asset($item['image_path']) }}" alt="{{ $item['name'] }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                                                @else
                                                    <img src="{{ asset('ghousiatraders/assets/baby_lotion.png') }}" alt="{{ $item['name'] }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                                                @endif
                                            </div>
                                            <div class="mini-prod-info">
                                                <h4 style="font-size:0.85rem; font-weight:700; color:var(--primary); line-height:1.2;">{{ $item['name'] }}</h4>
                                                <p style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">Qty: {{ $item['quantity'] }}</p>
                                            </div>
                                        </div>
                                        <span style="font-size:0.85rem; font-weight:700; color:var(--text-dark);">PKR {{ number_format($item['price'] * $item['quantity']) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="summary-divider" style="margin: 14px 0; height: 1px; background-color: var(--border-color);"></div>

                            <!-- Financial Rows -->
                            <div class="summary-row" style="margin-bottom:10px; font-size:0.9rem; display: flex; justify-content: space-between;">
                                <span class="summary-label">Subtotal ({{ collect($cartItems)->sum('quantity') }} items)</span>
                                <span class="summary-val">PKR {{ number_format($total) }}</span>
                            </div>
                            <div class="summary-row" style="margin-bottom:10px; font-size:0.9rem; display: flex; justify-content: space-between;">
                                <span class="summary-label">Shipping</span>
                                <span class="summary-val {{ $shippingCost == 0 ? 'shipping-free' : '' }}" style="font-weight: 700; color: {{ $shippingCost == 0 ? '#2ECC71' : 'var(--text-dark)' }};">
                                    {{ $shippingCost == 0 ? 'FREE' : 'PKR ' . number_format($shippingCost) }}
                                </span>
                            </div>
                            
                            @if($discount > 0)
                                <div class="summary-row" id="checkoutDiscountRow" style="margin-bottom:12px; font-size:0.9rem; color:#117A65; display: flex; justify-content: space-between;">
                                    <span class="summary-label">Discount {{ $appliedCoupon ? '(' . $appliedCoupon->code . ')' : '' }}</span>
                                    <span class="summary-val">- PKR {{ number_format($discount) }}</span>
                                </div>
                            @endif
                            
                            <div class="summary-divider" style="margin: 14px 0; height: 1px; background-color: var(--border-color);"></div>
                            <div class="summary-row total-row" style="margin-top:0; margin-bottom:20px; font-size:1.15rem; display: flex; justify-content: space-between; font-weight: 800;">
                                <span class="summary-label">Total</span>
                                <span class="summary-val total-price-val" id="checkoutTotalVal" style="color: var(--accent);">PKR {{ number_format(max(0, $total - $discount + $shippingCost)) }}</span>
                            </div>

                            <!-- Coupon code entry form -->
                            <div class="coupon-apply-row" style="display:flex; gap:10px; margin-bottom:14px;">
                                <input type="text" wire:model.defer="couponCode" placeholder="Enter coupon code" style="flex:1; padding:10px; border:1px solid var(--border-color); border-radius:var(--radius-sm); font-size:0.85rem; outline:none; text-transform:uppercase;">
                                <button type="button" wire:click="applyCoupon" class="btn btn-primary" style="padding:10px 18px; font-size:0.85rem; background-color:#5C3E21; color:#fff; border:none; border-radius:var(--radius-sm); cursor:pointer;">
                                    <span wire:loading.remove wire:target="applyCoupon">Apply</span>
                                    <span wire:loading wire:target="applyCoupon">...</span>
                                </button>
                            </div>
                            
                            @if($couponError)
                                <div style="color: #E11D48; font-size: 0.8rem; margin-bottom: 14px;">{{ $couponError }}</div>
                            @endif

                            @if($couponSuccess)
                                <!-- Coupon notification alert -->
                                <div class="coupon-applied-alert" id="couponAppliedAlert" style="display:flex; align-items:center; justify-content:space-between; background-color:#E8F8F5; border:1px solid #A3E4D7; border-radius:var(--radius-sm); padding:10px; margin-bottom:20px;">
                                    <span style="font-size:0.8rem; color:#117A65; font-weight:600;"><i data-lucide="check" style="width:14px; height:14px; vertical-align:middle; margin-right:4px;"></i>{{ $couponSuccess }}</span>
                                    <button type="button" wire:click="removeCoupon" style="background:none; border:none; color:#117A65; font-size:0.8rem; font-weight:700; cursor:pointer;">Remove</button>
                                </div>
                            @endif

                            <!-- Secure checkout banner -->
                            <div class="secure-checkout-banner" style="display:flex; align-items:center; gap:10px; background:#F8F9F9; border:1px solid var(--border-color); padding:12px; border-radius:var(--radius-sm); margin-bottom:20px;">
                                <i data-lucide="lock" style="width:20px; height:20px; color:#5C3E21; flex-shrink:0;"></i>
                                <div style="font-size:0.75rem; color:var(--text-muted); line-height:1.3; text-align: left;">
                                    <strong>Secure checkout</strong><br>Your payment information is encrypted and safe with us.
                                </div>
                            </div>

                            <!-- Submit Form Button -->
                            <button type="submit" class="btn btn-primary checkout-btn" style="width:100%; padding:14px; font-size:1rem; font-weight:700; background-color:#5C3E21; color:#fff; border:none; border-radius:var(--radius-sm); cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="createOrder">
                                    <i data-lucide="lock" style="width:16px; height:16px; display:inline-block; vertical-align:-2px;"></i> Place Order
                                </span>
                                <span wire:loading wire:target="createOrder">
                                    <i data-lucide="loader-2" class="animate-spin" style="width:16px; height:16px; display:inline-block; vertical-align:-2px; animation: spin 1s linear infinite;"></i> Processing...
                                </span>
                            </button>

                            <a href="{{ route('shopping-cart') }}" class="return-to-cart-link" style="display:block; text-align:center; margin-top:16px; font-size:0.85rem; font-weight:700; color:var(--primary); text-decoration:none;">
                                <i data-lucide="arrow-left" style="width:14px; height:14px; display:inline-block; vertical-align:-2px; margin-right:4px;"></i> Return to Cart
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>
</div>
