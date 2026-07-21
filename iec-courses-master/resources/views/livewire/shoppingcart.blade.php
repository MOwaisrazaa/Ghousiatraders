<div class="cart" data-cart-page>
  <section class="section section--ivory">
    <div class="container cart__grid cart__grid--table">
      <section class="cart-table" aria-label="Cart items">
        <div class="cart-table__head" aria-hidden="true">
          <div class="cart-table__h cart-table__h--product">Product</div>
          <div class="cart-table__h">Price</div>
          <div class="cart-table__h">Quantity</div>
          <div class="cart-table__h">Subtotal</div>
        </div>

        <div class="cart-table__body">
          @if($cartitems->count() > 0)
            @foreach($cartitems as $cartitem)
              @php
                $name = $cartitem->course ? $cartitem->course->name : ($cartitem->lecture ? $cartitem->lecture->name : 'Item');
                $img = null;
                if ($cartitem->course && $cartitem->course->image_path) {
                    $img = asset($cartitem->course->image_path);
                }
                if (!$img && $cartitem->lecture && $cartitem->lecture->image_path) {
                    $img = asset($cartitem->lecture->image_path);
                }
                if (!$img) {
                    $img = asset('polani/assets/product-noir.svg');
                }
                $price = (float) ($cartitem->price ?? 0);
                $quantity = (int) ($cartitem->quantity ?? 1);
              @endphp

              <div class="cart-row">
                <button
                  class="cart-row__remove"
                  type="button"
                  aria-label="Remove"
                  wire:click="removeFromCart({{ $cartitem->id }})"
                >
                  ×
                </button>

                <div class="cart-row__product">
                  <img class="cart-row__img" src="{{ $img }}" alt="{{ $name }}" loading="lazy" />
                  <div class="cart-row__info">
                    <div class="cart-row__name">{{ $name }}</div>
                    <div class="cart-row__meta">{{ $cartitem->course ? 'Extrait de Parfum' : 'Item' }}</div>
                    <div class="cart-row__meta">Size: {{ $cartitem->course && str_contains(strtolower($cartitem->course->name), 'candle') ? '200g' : '100ml' }}</div>
                  </div>
                </div>

                <div class="cart-row__price">Rs {{ number_format($price, 2) }}</div>

                <div class="qty qty--boxed" aria-label="Quantity">
                  <button type="button" aria-label="Decrease" wire:click="decrementQuantity({{ $cartitem->id }})" wire:loading.attr="disabled">−</button>
                  <input type="number" min="1" max="99" value="{{ $quantity }}" readonly />
                  <button type="button" aria-label="Increase" wire:click="incrementQuantity({{ $cartitem->id }})" wire:loading.attr="disabled">+</button>
                </div>

                <div class="cart-row__subtotal">Rs {{ number_format($price * $quantity, 2) }}</div>
              </div>
            @endforeach
          @else
            <div class="cart-empty">
              <div>
                <p class="cart-empty__title">Your cart is empty.</p>
                <p class="muted">Add a Polani fragrance to continue to checkout.</p>
              </div>
              <a class="btn btn--ink" href="{{ route('polani.collection') }}">Continue shopping</a>
            </div>
          @endif
        </div>

        @if($cartitems->count() > 0)
          <div class="cart-actions">
            <a class="cart-actions__link" href="{{ route('polani.collection') }}">← Continue shopping</a>
            <button class="btn btn--ghost btn--dark cart-actions__btn" type="button" wire:click="loadCartItems" wire:loading.attr="disabled">
              <span wire:loading.remove>Update cart ↻</span>
              <span wire:loading>Updating…</span>
            </button>
          </div>
        @endif
      </section>

      <aside class="cart__summary" aria-label="Order Summary">
        <div class="summary summary--cart">
          <div class="summary__title">Order Summary</div>
          <div class="summary__row">
            <span>Subtotal (<span>{{ $cartitems->count() }}</span> items)</span>
            <span>Rs {{ number_format((float)$totalAmount, 2) }}</span>
          </div>
          <div class="summary__row"><span>Shipping</span><span class="muted">Calculated at checkout</span></div>
          <div class="summary__row summary__row--total summary__row--cart-total">
            <span>Total</span><span class="summary__total">Rs {{ number_format((float)$totalAmount, 2) }}</span>
          </div>

          <button class="btn btn--ink w-100" type="button" wire:click="checkout" wire:loading.attr="disabled">
            <span wire:loading.remove>Proceed to checkout</span>
            <span wire:loading>Processing…</span>
          </button>
          <a class="btn btn--ghost btn--dark w-100" href="{{ route('checkout') }}">Buy it now</a>

          <div class="secure">
            <div class="secure__title">
              <span class="icon" aria-hidden="true" data-icon="lock-dark"></span>
              Secure checkout
            </div>
            <div class="secure__sub muted">We accept</div>
            <div class="secure__payments" aria-label="Payments">
              <span class="pay pay--logo" data-icon="visa" aria-hidden="true"></span>
              <span class="pay pay--logo" data-icon="mc" aria-hidden="true"></span>
              <span class="pay pay--logo" data-icon="amex" aria-hidden="true"></span>
              <span class="pay pay--logo" data-icon="paypal" aria-hidden="true"></span>
              <span class="pay pay--logo" data-icon="applepay" aria-hidden="true"></span>
            </div>
          </div>

          <form class="coupon coupon--row" data-coupon>
            <div class="coupon__label muted">Have a coupon code?</div>
            <button class="coupon__btn" type="submit">APPLY COUPON →</button>
          </form>
        </div>
      </aside>
    </div>
  </section>

  <section class="section section--ivory section--tight">
    <div class="container trust">
      <div class="trust__grid">
        <div class="trust__item">
          <span class="icon trust__icon" aria-hidden="true" data-icon="gift"></span>
          <div>
            <div class="trust__title">Luxury Packaging</div>
            <div class="trust__text">Beautifully wrapped for you</div>
          </div>
        </div>
        <div class="trust__item">
          <span class="icon trust__icon" aria-hidden="true" data-icon="truck"></span>
          <div>
            <div class="trust__title">Free Shipping</div>
            <div class="trust__text">On orders above Rs 27,500</div>
          </div>
        </div>
        <div class="trust__item">
          <span class="icon trust__icon" aria-hidden="true" data-icon="badge"></span>
          <div>
            <div class="trust__title">100% Authentic</div>
            <div class="trust__text">Original &amp; authentic products</div>
          </div>
        </div>
        <div class="trust__item">
          <span class="icon trust__icon" aria-hidden="true" data-icon="headset"></span>
          <div>
            <div class="trust__title">Dedicated Support</div>
            <div class="trust__text">We're here to help you always</div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

