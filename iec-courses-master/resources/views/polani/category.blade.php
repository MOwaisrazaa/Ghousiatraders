@extends('polani.layout')

@section('title', $pageTitle)
@section('meta_description', $pageDescription)
@section('body_class', 'page-collection')

@section('content')
  <x-polani.page-banner
    :page-key="$pageKey ?? 'collection'"
    eyebrow="OUR COLLECTION"
    :title="$collectionLabel"
    :subtitle="$pageDescription"
    :fallback-image="$heroImage"
    image-position="center center"
  />

  <section class="section section--ivory">
    <div class="container">
      <div class="collection-head">
        <div class="breadcrumbs">
          <a href="{{ route('home') }}">Home</a> <span aria-hidden="true">›</span> <span>{{ $collectionLabel }}</span>
        </div>
        <div class="collection-head__right">
          <div class="muted">Showing {{ $products->count() }} products</div>
          <a class="btn btn--ghost btn--dark" href="{{ route('polani.collection') }}">View All</a>
        </div>
      </div>

      <div class="collection">
        <aside class="filters" aria-label="Category summary">
          <div class="filters__title">Browse</div>
          <div class="filter">
            <div class="filter__label">Quick Links</div>
            <a class="footer__link" href="{{ route('polani.women') }}">Women</a>
            <a class="footer__link" href="{{ route('polani.attars') }}">Attars</a>
            <a class="footer__link" href="{{ route('polani.oud') }}">Oud</a>
            <a class="footer__link" href="{{ route('polani.scented-candles') }}">Scented Candles</a>
            <a class="footer__link" href="{{ route('polani.track-order') }}">Track Order</a>
          </div>
        </aside>

        <div class="collection__grid">
          <div class="product-grid" data-product-grid="collection">
            @forelse($products as $product)
              @include('polani.partials.product-card', ['product' => $product])
            @empty
              <div class="alert alert--success" style="grid-column: 1 / -1;">
                We are preparing this collection. Please check back soon.
              </div>
            @endforelse
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
