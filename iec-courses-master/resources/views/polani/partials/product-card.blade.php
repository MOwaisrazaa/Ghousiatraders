<article
  class="card"
  data-card-link="{{ route('polani.product', ['slug' => $product['slug']]) }}"
  data-product-name="{{ strtolower($product['name']) }}"
  data-product-type="{{ strtolower($product['type']) }}"
  data-product-price="{{ (int) $product['price'] }}"
  data-product-rating="{{ $product['rating'] }}"
  data-product-category="{{ $product['category_slug'] ?? '' }}"
  data-product-family="{{ implode(' ', $product['family'] ?? []) }}"
  data-product-occasion="{{ implode(' ', $product['occasion'] ?? []) }}"
>
  <a class="card__media" href="{{ route('polani.product', ['slug' => $product['slug']]) }}">
    <img
      src="{{ $product['image'] }}"
      alt="{{ $product['name'] }}"
      width="400"
      height="240"
      loading="lazy"
      decoding="async"
    />
    <button class="wish" type="button" aria-label="Add to wishlist">
      <span class="icon" aria-hidden="true" data-icon="star"></span>
    </button>
  </a>
  <div class="card__body">
    <h3 class="card__name">
      <a href="{{ route('polani.product', ['slug' => $product['slug']]) }}">{{ $product['name'] }}</a>
    </h3>
    <div class="card__type">{{ $product['type'] }}</div>
    <div class="card__row">
      <div class="price">Rs {{ number_format($product['price']) }}</div>
      <div class="rating" data-product-rating-slug="{{ $product['slug'] }}">
        @php
          $ratingVal = (float) ($product['rating'] ?? 0);
          $reviewsCount = (int) ($product['reviews'] ?? 0);
          $fullStars = (int) round($ratingVal);
          $starsStr = str_repeat('★', $fullStars) . str_repeat('☆', 5 - $fullStars);
        @endphp
        <span class="stars" aria-hidden="true" data-stars-display style="color: #d4a658; font-size: 0.8rem; letter-spacing: 1px;">{{ $starsStr }}</span>
        <span class="muted" data-reviews-count-display style="font-size: 0.8rem; margin-left: 4px;">({{ $reviewsCount }})</span>
      </div>
    </div>
    <div class="card__actions">
      <button
        class="btn btn--ghost"
        type="button"
        data-add-to-cart
        data-add-url="{{ route('polani.cart.add', ['slug' => $product['slug']]) }}"
      >
        Add to Cart
      </button>
    </div>
  </div>
</article>
