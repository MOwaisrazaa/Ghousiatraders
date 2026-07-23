<div class="cart-body-section" data-cart-page style="background-color: var(--bg-light); padding: 60px 0;">
    <div class="section-container">
        @if($cartitems->count() > 0)
            <div class="cart-layout-grid">
                <!-- Left: Items list -->
                <div class="cart-main-content">
                    <div class="cart-table-header">
                        <span class="th-product">Product</span>
                        <span class="th-price">Price</span>
                        <span class="th-quantity">Quantity</span>
                        <span class="th-subtotal">Subtotal</span>
                    </div>

                    <div class="cart-items-list" id="cartItemsList">
                        @foreach($cartitems as $cartitem)
                            @php
                                $isObject = is_object($cartitem);
                                $itemId = $isObject ? ($cartitem->id ?? null) : ($cartitem['id'] ?? null);
                                $courseObj = $isObject ? ($cartitem->course ?? null) : ($cartitem['course'] ?? null);
                                $courseObj = is_array($courseObj) ? (object) $courseObj : $courseObj;
                                $lectureObj = $isObject ? ($cartitem->lecture ?? null) : ($cartitem['lecture'] ?? null);
                                $lectureObj = is_array($lectureObj) ? (object) $lectureObj : $lectureObj;

                                $name = $courseObj ? ($courseObj->name ?? 'Item') : ($lectureObj ? ($lectureObj->name ?? 'Item') : 'Item');
                                $img = null;
                                if ($courseObj && !empty($courseObj->image_path)) {
                                    $img = asset($courseObj->image_path);
                                }
                                if (!$img && $lectureObj && !empty($lectureObj->image_path)) {
                                    $img = asset($lectureObj->image_path);
                                }
                                if (!$img) {
                                    $img = asset('ghousiatraders/assets/baby_products.png');
                                }
                                $price = (float) ($isObject ? ($cartitem->price ?? 0) : ($cartitem['price'] ?? 0));
                                $quantity = (int) ($isObject ? ($cartitem->quantity ?? 1) : ($cartitem['quantity'] ?? 1));
                            @endphp

                            <div class="cart-item-row" data-id="{{ $itemId }}" data-price="{{ $price }}">
                                <div class="td-product">
                                    <div class="cart-item-img">
                                        <img src="{{ $img }}" alt="{{ $name }}">
                                    </div>
                                    <div class="cart-item-detail">
                                        <h3>{{ $name }}</h3>
                                        <p>{{ $courseObj ? 'Ride-On Toy / Baby Care' : 'Item' }}</p>
                                        <span class="stock-badge">In Stock</span>
                                    </div>
                                </div>
                                <div class="td-price">
                                    <span class="price-label">Price:</span>
                                    <span class="val-price">PKR {{ number_format($price) }}</span>
                                </div>
                                <div class="td-quantity">
                                    <div class="quantity-control">
                                        <button class="qty-btn qty-minus" type="button" aria-label="Decrease quantity" wire:click="decrementQuantity({{ $itemId }})" wire:loading.attr="disabled">—</button>
                                        <input type="number" value="{{ $quantity }}" min="1" class="qty-input" readonly>
                                        <button class="qty-btn qty-plus" type="button" aria-label="Increase quantity" wire:click="incrementQuantity({{ $itemId }})" wire:loading.attr="disabled">+</button>
                                    </div>
                                </div>
                                <div class="td-subtotal">
                                    <span class="subtotal-label">Subtotal:</span>
                                    <span class="val-subtotal">PKR {{ number_format($price * $quantity) }}</span>
                                    <button class="remove-item-btn" type="button" aria-label="Remove item" wire:click="removeFromCart({{ $itemId }})">
                                        <i data-lucide="x"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions below list -->
                    <div class="cart-action-buttons" style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <a href="{{ route('polani.collection') }}" class="btn btn-outline" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
                            <span>Continue Shopping</span>
                        </a>
                        <button class="btn btn-outline-danger" id="clearCartBtn" type="button" wire:click="clearCart" style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; text-decoration: none;">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                            <span>Clear Cart</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div class="cart-summary-sidebar">
                    <div class="summary-card">
                        <h2 class="summary-title">Order Summary</h2>
                        <div class="summary-row">
                            <span class="summary-label" id="summaryItemsCount">Subtotal ({{ $cartitems->count() }} items)</span>
                            <span class="summary-val" id="summarySubtotal">PKR {{ number_format((float)$totalAmount) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-val shipping-free">PKR 0</span>
                        </div>
                        <div class="shipping-qualify-msg">
                            You qualify for free shipping!
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total-row">
                            <span class="summary-label">Total</span>
                            <span class="summary-val total-price-val" id="summaryTotal">PKR {{ number_format((float)$totalAmount) }}</span>
                        </div>
                        
                        <button class="btn btn-primary checkout-btn" type="button" wire:click="checkout" wire:loading.attr="disabled" style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 8px;">
                            <i data-lucide="lock" style="width: 16px; height: 16px;"></i>
                            <span wire:loading.remove>Proceed to Checkout</span>
                            <span wire:loading>Processing…</span>
                        </button>
                        
                        <div class="accepted-payment-methods">
                            <span class="payment-title">We Accept</span>
                            <div class="payment-methods-grid">
                                <svg class="pay-logo-img" viewBox="0 0 75 50" xmlns="http://www.w3.org/2000/svg" style="width:50px; height:30px;"><rect width="75" height="50" rx="4" fill="#FFF" stroke="#D5D8DC" stroke-width="1"/><path d="M12 18 L18 34 H23 L29 18 H24 L21.5 29.5 L19 18 H12 Z" fill="#1A1F71"/><path d="M30 18 H34 V34 H30 Z" fill="#1A1F71"/><path d="M43.5 19.5 C42 18.5 40 18 38 18 C34 18 31.5 20 31.5 23.5 C31.5 28 37.5 28 37.5 30.5 C37.5 31.5 35.5 32 34 32 C32 32 30 31 29 30.5 L28 33.5 C29.5 34.5 32 35 34 35 C38.5 35 41.5 33 41.5 29.5 C41.5 25 35.5 24.5 35.5 22.5 C35.5 21.5 37 21 38.5 21 C40.5 21 42.5 21.5 43.5 22.5 L44.5 19.5 Z" fill="#1A1F71"/><path d="M52.5 18 H49 L42.5 34 H47.5 L48.5 31.5 H54.5 L55 34 H60 L56 18 H52.5 Z M50 28 L51.5 23 L53.5 28 H50 Z" fill="#1A1F71"/><path d="M12 18 L15 26 L16 18 Z" fill="#F7B600"/></svg>
                                <svg class="pay-logo-img" viewBox="0 0 75 50" xmlns="http://www.w3.org/2000/svg" style="width:50px; height:30px;"><rect width="75" height="50" rx="4" fill="#FFF" stroke="#D5D8DC" stroke-width="1"/><circle cx="31" cy="25" r="14" fill="#EB001B" opacity="0.9"/><circle cx="44" cy="25" r="14" fill="#F79E1B" opacity="0.9"/></svg>
                                <img class="pay-logo-img" src="{{ asset('ghousiatraders/assets/meezan-logo.png') }}" alt="Meezan Bank" style="width: 50px; height: 30px; object-fit: contain;">
                                <img class="pay-logo-img" src="{{ asset('ghousiatraders/assets/easypaisa-logo.png') }}" alt="Easypaisa" style="width: 50px; height: 30px; object-fit: contain;">
                                <img class="pay-logo-img" src="{{ asset('ghousiatraders/assets/jazzcash-logo.png') }}" alt="JazzCash" style="width: 50px; height: 30px; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="wishlist-empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; text-align: center; background: #fff; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); width: 100%;">
                <div class="empty-icon-wrapper" style="width: 80px; height: 80px; background-color: #FAF5F5; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i data-lucide="shopping-cart" style="width: 36px; height: 36px;"></i>
                </div>
                <h2 style="font-family: var(--font-serif); font-size: 1.8rem; color: var(--text-dark); margin-bottom: 10px;">Your cart is empty</h2>
                <p style="color: var(--text-muted); margin-bottom: 25px; max-width: 400px;">Explore our catalog of premium baby care and exciting ride-on toys to start shopping!</p>
                <a href="{{ route('polani.collection') }}" class="btn btn-primary" style="text-decoration: none;">Go to Shop</a>
            </div>
        @endif
    </div>
</div>
