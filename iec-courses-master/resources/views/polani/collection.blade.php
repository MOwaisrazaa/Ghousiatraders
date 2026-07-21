@extends('polani.layout')

@section('title', 'Men — Polani Fragrance')
@section('meta_description', 'Discover bold, refined and long-lasting fragrances crafted for the modern man.')
@section('body_class', 'page-collection')

@section('content')
  <x-polani.page-banner
    page-key="collection"
    eyebrow="OUR COLLECTION"
    title="Explore Our Collections"
    subtitle="Discover bold, refined and long-lasting fragrances crafted for every Polani mood."
    fallback-image="polani/assets/home_banner_1.jpeg"
    image-position="center center"
  />

  <section class="section section--ivory">
    <div class="container">
      <div class="collection-head">
        <div class="breadcrumbs">
          <a href="{{ route('home') }}">Home</a> <span aria-hidden="true">›</span> <span>Men</span>
        </div>
        <div class="collection-head__right">
          <button class="btn btn--ghost btn--dark filter-toggle-btn" type="button" data-filter-toggle style="display: none; padding: 8px 16px; border-radius: 12px; font-size: 11px; letter-spacing: 0.12em;">
            Filters <span style="font-size: 9px; margin-left: 4px;">▼</span>
          </button>
          <div class="muted" data-results-count>Showing 1–12 of {{ $products->total() }} products</div>
          <label class="select">
            <span class="sr-only">Sort</span>
            <select data-sort>
              <option value="best" selected>Best Selling</option>
              <option value="price-asc">Price: Low to High</option>
              <option value="price-desc">Price: High to Low</option>
              <option value="rating-desc">Rating</option>
            </select>
          </label>
        </div>
      </div>

      <div class="collection">
        <aside class="filters" aria-label="Filters">
          <div class="filters__title">Filter By</div>

          <div class="filter" id="men">
            <div class="filter__label">Categories</div>
            <label class="check"><input type="radio" name="cat" value="all" checked data-filter-cat /> All</label>
            <label class="check"><input type="radio" name="cat" value="men" data-filter-cat /> Men</label>
            <label class="check"><input type="radio" name="cat" value="women" data-filter-cat /> Women</label>
            <label class="check"><input type="radio" name="cat" value="attars" data-filter-cat /> Attars</label>
            <label class="check"><input type="radio" name="cat" value="oud" data-filter-cat /> Oud</label>
            <label class="check"><input type="radio" name="cat" value="candles" data-filter-cat /> Candles</label>
          </div>

          <div class="filter" id="women">
            <div class="filter__label">Price</div>
            <div class="range">
              <input type="range" min="10000" max="50000" value="50000" step="500" data-filter-price />
              <div class="range__meta">
                <span>Rs 10,000</span>
                <span class="muted">Up to <strong data-price-label>Rs 50,000</strong></span>
              </div>
            </div>
          </div>

          <div class="filter" id="attars">
            <div class="filter__label">Fragrance Family</div>
            <label class="check"><input type="checkbox" value="citrus" data-filter-family /> Citrus</label>
            <label class="check"><input type="checkbox" value="woody" data-filter-family /> Woody</label>
            <label class="check"><input type="checkbox" value="spicy" data-filter-family /> Spicy</label>
            <label class="check"><input type="checkbox" value="fresh" data-filter-family /> Fresh</label>
            <label class="check"><input type="checkbox" value="amber" data-filter-family /> Amber</label>
            <label class="check"><input type="checkbox" value="floral" data-filter-family /> Floral</label>
            <label class="check"><input type="checkbox" value="musky" data-filter-family /> Musky</label>
          </div>

          <div class="filter" id="oud">
            <div class="filter__label">Occasion</div>
            <label class="check"><input type="checkbox" value="daily" data-filter-occasion /> Daily Wear</label>
            <label class="check"><input type="checkbox" value="office" data-filter-occasion /> Office</label>
            <label class="check"><input type="checkbox" value="evening" data-filter-occasion /> Evening</label>
            <label class="check"><input type="checkbox" value="luxury" data-filter-occasion /> Luxury Events</label>
          </div>

          <button class="btn btn--ghost btn--dark w-100" type="button" data-reset-filters id="candles">
            Reset Filters
          </button>
        </aside>

        <div class="collection__grid">
          <div class="product-grid" data-product-grid="collection">
            @foreach($products as $product)
              @include('polani.partials.product-card', ['product' => $product])
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="newsletter section section--dark">
    <div class="container newsletter__inner">
      <div>
        <h2 class="newsletter__title">Stay In Touch</h2>
        <p class="newsletter__text">
          Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.
        </p>
      </div>
      <form class="newsletter__form" data-newsletter>
        <label class="sr-only" for="email">Email</label>
        <input id="email" name="email" type="email" placeholder="Enter your email" required />
        <button class="btn btn--primary" type="submit">Subscribe</button>
      </form>
    </div>
  </section>
@endsection
