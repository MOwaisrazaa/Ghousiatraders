<div class="checkout" data-checkout-page>
  

    @if(!auth()->check())
        <div class="prose" style="margin-bottom: 18px; padding: 18px; border: 1px solid rgba(0,0,0,.08); border-radius: 18px; background: rgba(255,255,255,.8);">
            <p style="margin: 0 0 10px;">
                To place an order, please sign in first — this applies to both COD and online payments.
            </p>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn--primary" href="{{ route('sign-in') }}">Sign In</a>
                <a class="btn btn--ghost btn--dark" href="{{ route('sign-up') }}">Sign Up</a>
            </div>
        </div>
    @endif

    <div class="checkout__grid">
        <form class="checkout__form" wire:submit.prevent="createOrder">
            <div class="grid-2">
                <label class="field">
                    <span class="field__label">First name</span>
                    <input name="first_name" wire:model.defer="firstName" required />
                    @error('firstName') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>

                <label class="field">
                    <span class="field__label">Last name</span>
                    <input name="last_name" wire:model.defer="lastName" required />
                    @error('lastName') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>
            </div>

            <div class="grid-2">
                <label class="field">
                    <span class="field__label">Email</span>
                    <input name="email" type="email" wire:model.defer="email" required />
                    @error('email') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>

                <label class="field">
                    <span class="field__label">Phone</span>
                    <input name="phone" wire:model.defer="phone" required />
                    @error('phone') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>
            </div>

            <label class="field">
                <span class="field__label">Address</span>
                <input name="address" wire:model.defer="address" required />
                @error('address') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </label>

            <div class="grid-2">
                <label class="field">
                    <span class="field__label">Country</span>
                    <input name="country" wire:model.defer="country" required />
                    @error('country') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>

                <label class="field">
                    <span class="field__label">State</span>
                    <input name="state" wire:model.defer="state" required />
                    @error('state') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>
            </div>

            <div class="grid-2">
                <label class="field">
                    <span class="field__label">City</span>
                    <input name="city" wire:model.defer="city" required />
                    @error('city') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>

                <label class="field">
                    <span class="field__label">Postal code</span>
                    <input name="postal_code" wire:model.defer="postalCode" required />
                    @error('postalCode') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
                </label>
            </div>

            <div class="field">
                <div class="field__label">Payment Method</div>
                @foreach($paymentMethods as $method)
                    <div style="margin-bottom: 12px;">
                        <label class="radio" style="display: block; margin-bottom: 4px;">
                            <input type="radio" wire:model.live="paymentMethod" value="{{ $method->key }}" />
                            {{ $method->name }}
                        </label>
                        @if($paymentMethod === $method->key && $method->instructions)
                            <div class="payment-instructions" style="margin-left: 24px; margin-top: 6px; padding: 12px; border: 1px solid rgba(212,166,88,0.2); border-radius: 8px; background: rgba(212,166,88,0.03); color: var(--color-ink); font-size: 0.85rem; line-height: 1.5; border-left: 3px solid #d4a658;">
                                <strong style="display: block; margin-bottom: 4px; font-weight: 600; color: #d4a658; font-size: 0.7rem; letter-spacing: 0.12em; text-transform: uppercase;">Payment Instructions</strong>
                                <div style="white-space: pre-line;">{{ $method->instructions }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
                @error('paymentMethod') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <div class="field__label">Coupon</div>
                <div class="grid-2" style="grid-template-columns: 1fr auto;">
                    <input type="text" wire:model.defer="couponCode" placeholder="Enter coupon code" />
                    <button class="btn btn--ink" type="button" wire:click="applyCoupon" wire:loading.attr="disabled">
                        <span wire:loading.remove>Apply</span>
                        <span wire:loading>Applying...</span>
                    </button>
                </div>
                @if($couponError)
                    <div class="muted" style="color:#b91c1c; margin-top:8px;">{{ $couponError }}</div>
                @endif
                @if($couponSuccess)
                    <div class="muted" style="color:#166534; margin-top:8px;">{{ $couponSuccess }}</div>
                    <button class="link" type="button" wire:click="removeCoupon" style="margin-top:6px;">Remove coupon</button>
                @endif
            </div>

            <button class="btn btn--primary w-100" type="submit" wire:loading.attr="disabled" @if(!auth()->check()) disabled @endif>
                <span wire:loading.remove>{{ auth()->check() ? 'Place Order' : 'Sign In to Place Order' }}</span>
                <span wire:loading>Processing...</span>
            </button>
        </form>

        <aside class="checkout__summary" aria-label="Summary">
            <div class="summary">
                <div class="summary__title">Order Summary</div>

                <div class="summary__row"><span>Items</span><span>{{ count($cartItems) }}</span></div>
                <div class="summary__row summary__row--total"><span>Total</span><span>Rs {{ number_format((float)$total, 2) }}</span></div>
                @if($discount > 0)
                    <div class="summary__row"><span class="muted">Discount</span><span class="muted">-Rs {{ number_format((float)$discount, 2) }}</span></div>
                    <div class="summary__row summary__row--total"><span>Final</span><span>Rs {{ number_format(max(0, (float)$total - (float)$discount), 2) }}</span></div>
                @endif

                <div class="mini-note muted" style="margin-top:10px;">
                    Shipping calculated at checkout.
                </div>
            </div>
        </aside>
    </div>
</div>
