@extends('polani.layout')

@section('title', $product['name'] . ' — Polani Fragrance')
@section('meta_description', $product['description'] ?? 'Luxury fragrance crafted for unforgettable presence.')
@section('body_class', 'page-product')

@section('content')
  <x-polani.page-banner
    page-key="product"
    eyebrow="SIGNATURE PRODUCT"
    :title="$product['name']"
    :subtitle="$product['type'] . ' — Luxury fragrance crafted for unforgettable presence.'"
    :fallback-image="$product['image']"
    image-position="center center"
  />

  <section class="section section--ivory">
    <div class="container product" data-product-page>
      <div class="product__gallery">
        <img class="product__img" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" data-product-image />
        <div class="thumbs" data-thumbs>
          <button class="thumb is-on" type="button" data-thumb="0">
            <img src="{{ $product['image'] }}" alt="" />
          </button>
        </div>
      </div>

      <div class="product__info">
        <div class="breadcrumbs">
          <a href="{{ route('home') }}">Home</a> <span aria-hidden="true">›</span>
          <a href="{{ route('polani.collection') }}">Shop</a> <span aria-hidden="true">›</span>
          <span data-product-name>{{ $product['name'] }}</span>
        </div>

        <h1 class="product__title" data-product-name>{{ $product['name'] }}</h1>
        <div class="product__type" data-product-type>{{ $product['type'] }}</div>

        {{-- Interactive rating stars summary under the title --}}
        <div class="product__rating-summary-top" id="product-rating-summary-top" style="display: none; align-items: center; gap: 8px; margin: 8px 0 16px; font-size: 0.9rem;">
          <span class="stars" id="top-stars" style="display: flex; gap: 2px;"></span>
          <span class="rating-val" id="top-rating-val" style="font-weight: 700; color: #855b14;"></span>
          <span class="rating-count" id="top-rating-count" style="color: #8a7558; text-decoration: underline; cursor: pointer;"></span>
        </div>

        <div class="product__row">
          <div class="price" data-product-price>Rs {{ number_format($product['price']) }}</div>
          {{-- No fake rating shown --}}
        </div>

        <p class="product__desc" data-product-desc>{{ $product['description'] }}</p>

        <div class="notes notes--compact" data-product-notes>
          <div class="notes__col">
            <div class="notes__label">TOP NOTES</div>
            <div class="notes__value">{{ implode(', ', $product['notes']['top']) }}</div>
          </div>
          <div class="notes__col">
            <div class="notes__label">HEART NOTES</div>
            <div class="notes__value">{{ implode(', ', $product['notes']['heart']) }}</div>
          </div>
          <div class="notes__col">
            <div class="notes__label">BASE NOTES</div>
            <div class="notes__value">{{ implode(', ', $product['notes']['base']) }}</div>
          </div>
        </div>

        <div class="product__actions">
          <button
            class="btn btn--primary"
            type="button"
            data-add-to-cart
            data-add-url="{{ route('polani.cart.add', ['slug' => $product['slug']]) }}"
          >
            Add to Cart
          </button>
          <a class="btn btn--ghost btn--dark" href="{{ route('shopping-cart') }}">Buy Now</a>
        </div>

        <div class="accordion" data-accordion>
          <button class="accordion__head" type="button" aria-expanded="true">
            Additional Information <span aria-hidden="true">+</span>
          </button>
          <div class="accordion__body">
            <div class="kv">
              <div class="kv__row"><span>Longevity</span><span data-product-longevity>{{ $product['longevity'] }}</span></div>
              <div class="kv__row"><span>Projection</span><span data-product-projection>{{ $product['projection'] }}</span></div>
              <div class="kv__row"><span>Best Season</span><span data-product-season>{{ $product['season'] }}</span></div>
              <div class="kv__row"><span>Occasion</span><span data-product-occasion>{{ implode(', ', $product['occasion']) }}</span></div>
            </div>
          </div>
        </div>

        <div class="mini-note muted">Guest checkout is available for manual payment methods.</div>
      </div>
    </div>
  </section>

  {{-- ══════════════════════════════════════════
       TABS: Description / Shipping / Reviews
  ══════════════════════════════════════════ --}}
  <section class="pdt-tabs-section">
    <div class="container">

      {{-- Tab Nav --}}
      <div class="pdt-tabs-nav" role="tablist">
        <button class="pdt-tab-btn is-active" role="tab" data-tab="description" aria-selected="true">
          Description
        </button>
        <button class="pdt-tab-btn" role="tab" data-tab="shipping" aria-selected="false">
          Shipping Info
        </button>
        <button class="pdt-tab-btn" role="tab" data-tab="reviews" aria-selected="false">
          Reviews <span class="pdt-tab-count" id="review-count-badge">0</span>
        </button>
      </div>

      {{-- Tab Panels --}}

      {{-- Description --}}
      <div class="pdt-tab-panel is-active" id="tab-description" role="tabpanel">
        <div class="pdt-desc-grid">
          <div class="pdt-desc-main">
            @php
              $videoId = null;
              $videoUrl = $product['intro_video_url'] ?? null;
              if ($videoUrl) {
                  if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                      $videoId = $match[1];
                  }
              }
            @endphp

            @if($videoId)
              <div class="pdt-video-section" style="margin-bottom: 40px;">
                <h3 class="pdt-section-title">Product Video</h3>
                <div class="pdt-video-wrapper" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(212,166,88,0.18); background: rgba(0,0,0,0.2); position: relative; padding-bottom: 56.25%; height: 0; box-shadow: 0 12px 30px rgba(0,0,0,0.25);">
                  <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                          style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" 
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                          allowfullscreen></iframe>
                </div>
              </div>
            @else
              <div class="pdt-video-section" style="margin-bottom: 40px;">
                <h3 class="pdt-section-title">Product Video</h3>
                <div class="pdt-no-video" style="border-radius: 16px; border: 1px dashed rgba(212,166,88,0.15); background: rgba(255,255,255,0.01); padding: 40px; text-align: center; color: rgba(248, 231, 208, 0.4);">
                  <span style="font-size: 2rem; display: block; margin-bottom: 8px;">🎥</span>
                  <span style="font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 600;">No Product Video Available</span>
                </div>
              </div>
            @endif

            <h3 class="pdt-section-title">About This Fragrance</h3>
            @if(!empty($product['long_description']))
              <div class="pdt-desc-text ql-editor-view">
                {!! htmlspecialchars_decode($product['long_description']) !!}
              </div>
            @else
              <p class="pdt-desc-text">{{ $product['description'] ?? 'A luxurious fragrance crafted with the finest ingredients, designed to leave a lasting impression.' }}</p>
            @endif

            <div class="pdt-notes-detail">
              <div class="pdt-note-block">
                <div class="pdt-note-label">
                  <span class="pdt-note-dot pdt-note-dot--top"></span>
                  Top Notes
                </div>
                <div class="pdt-note-val">{{ implode(' · ', $product['notes']['top']) }}</div>
              </div>
              <div class="pdt-note-block">
                <div class="pdt-note-label">
                  <span class="pdt-note-dot pdt-note-dot--heart"></span>
                  Heart Notes
                </div>
                <div class="pdt-note-val">{{ implode(' · ', $product['notes']['heart']) }}</div>
              </div>
              <div class="pdt-note-block">
                <div class="pdt-note-label">
                  <span class="pdt-note-dot pdt-note-dot--base"></span>
                  Base Notes
                </div>
                <div class="pdt-note-val">{{ implode(' · ', $product['notes']['base']) }}</div>
              </div>
            </div>
          </div>

          <div class="pdt-desc-side">
            <div class="pdt-info-card">
              <div class="pdt-info-card__title">Performance</div>
              <div class="pdt-info-row">
                <span>Longevity</span><strong>{{ $product['longevity'] }}</strong>
              </div>
              <div class="pdt-info-row">
                <span>Projection</span><strong>{{ $product['projection'] }}</strong>
              </div>
              <div class="pdt-info-row">
                <span>Best Season</span><strong>{{ $product['season'] }}</strong>
              </div>
              <div class="pdt-info-row">
                <span>Occasion</span><strong>{{ implode(', ', $product['occasion']) }}</strong>
              </div>
              <div class="pdt-info-row">
                <span>Fragrance Family</span><strong>{{ implode(', ', $product['family']) }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Shipping --}}
      <div class="pdt-tab-panel" id="tab-shipping" role="tabpanel" hidden>
        <div class="pdt-shipping-grid">
          <div class="pdt-ship-card">
            <div class="pdt-ship-icon">🚚</div>
            <div class="pdt-ship-title">Standard Delivery</div>
            <div class="pdt-ship-text">Orders are processed within 1–2 business days. Delivery within Pakistan takes 3–5 business days depending on your city.</div>
          </div>
          <div class="pdt-ship-card">
            <div class="pdt-ship-icon">💳</div>
            <div class="pdt-ship-title">Cash on Delivery</div>
            <div class="pdt-ship-text">We offer Cash on Delivery (COD) across Pakistan. Pay when your order arrives at your doorstep — safe and convenient.</div>
          </div>
          <div class="pdt-ship-card">
            <div class="pdt-ship-icon">📦</div>
            <div class="pdt-ship-title">Secure Packaging</div>
            <div class="pdt-ship-text">Every order is carefully packed in premium packaging to ensure your fragrance arrives in perfect condition.</div>
          </div>
          <div class="pdt-ship-card">
            <div class="pdt-ship-icon">🔄</div>
            <div class="pdt-ship-title">Easy Returns</div>
            <div class="pdt-ship-text">If you receive a damaged or incorrect item, contact us within 48 hours. We'll arrange a replacement or full refund.</div>
          </div>
        </div>

        <div class="pdt-ship-note">
          <strong>Free Shipping</strong> on orders above <strong>Rs 10,000</strong> anywhere in Pakistan.
        </div>
      </div>

      {{-- Reviews --}}
      <div class="pdt-tab-panel" id="tab-reviews" role="tabpanel" hidden>

        {{-- Rating Summary --}}
        <div class="pdt-reviews-top">
          <div class="pdt-rating-summary" id="rating-summary">
            <div class="pdt-rating-big" id="avg-rating-display">—</div>
            <div class="pdt-rating-stars" id="avg-stars-display">
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
            </div>
            <div class="pdt-rating-count" id="total-reviews-label">No reviews yet</div>
          </div>

          <div class="pdt-rating-bars" id="rating-bars">
            @foreach([5,4,3,2,1] as $star)
            <div class="pdt-bar-row" data-star="{{ $star }}">
              <span class="pdt-bar-label">{{ $star }} ★</span>
              <div class="pdt-bar-track"><div class="pdt-bar-fill" style="width:0%"></div></div>
              <span class="pdt-bar-count">0</span>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Review List --}}
        <div class="pdt-reviews-list" id="reviews-list">
          <div class="pdt-no-reviews" id="no-reviews-msg">
            <div class="pdt-no-reviews-icon">✦</div>
            <p>No reviews yet. Be the first to share your experience!</p>
          </div>
        </div>

        {{-- Write Review Form --}}
        <div class="pdt-write-review">
          <h4 class="pdt-section-title">Write a Review</h4>
          <form class="pdt-review-form" id="review-form">
            <div class="pdt-rev-row">
              <div class="pdt-rev-field">
                <label>Your Name</label>
                <input type="text" id="rev-name" placeholder="e.g., Ahmed Khan">
              </div>
              <div class="pdt-rev-field">
                <label>Rating</label>
                <div class="pdt-star-picker" id="star-picker" role="radiogroup" aria-label="Rating">
                  @foreach([1,2,3,4,5] as $s)
                  <button type="button" class="pdt-star-pick" data-val="{{ $s }}" aria-label="{{ $s }} star">★</button>
                  @endforeach
                </div>
                <input type="hidden" id="rev-rating" value="0">
              </div>
            </div>
            <div class="pdt-rev-field">
              <label>Your Review</label>
              <textarea id="rev-body" rows="4" placeholder="Tell us about your experience with this fragrance..." required></textarea>
            </div>
            <button type="submit" class="pdt-rev-submit">Post Review</button>
            <div class="pdt-rev-msg" id="rev-msg" style="display:none;"></div>
          </form>
        </div>

      </div>
    </div>
  </section>

<style>
  /* ── Tabs Section ─────────────────────────────── */
  .pdt-tabs-section {
    background: #0a0a0a;
    padding: 56px 0 72px;
    border-top: 1px solid rgba(212,166,88,0.1);
  }
  .pdt-tabs-nav {
    display: flex;
    gap: 0;
    border-bottom: 1px solid rgba(212,166,88,0.15);
    margin-bottom: 40px;
    flex-wrap: wrap;
  }
  .pdt-tab-btn {
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: rgba(248,231,208,0.45);
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 14px 28px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: -1px;
  }
  .pdt-tab-btn:hover { color: rgba(248,231,208,0.8); }
  .pdt-tab-btn.is-active {
    color: #d4a658;
    border-bottom-color: #d4a658;
  }
  .pdt-tab-count {
    background: rgba(212,166,88,0.18);
    color: #d4a658;
    font-size: 0.7rem;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 20px;
    min-width: 20px;
    text-align: center;
  }
  .pdt-tab-panel { display: none; }
  .pdt-tab-panel.is-active { display: block; }

  .pdt-section-title {
    font-size: 1.1rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #f8e7d0;
    margin: 0 0 22px;
  }

  /* ── Description Tab ── */
  .pdt-desc-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 40px;
    align-items: start;
  }
  @media(max-width:860px) { .pdt-desc-grid { grid-template-columns: 1fr; } }

  .pdt-desc-text {
    font-size: 0.97rem;
    line-height: 1.8;
    color: rgba(248,231,208,0.7);
    margin-bottom: 28px;
  }
  .ql-editor-view ul, .ql-editor-view ol {
    margin: 14px 0;
    padding-left: 24px;
  }
  .ql-editor-view ul {
    list-style-type: disc;
  }
  .ql-editor-view ol {
    list-style-type: decimal;
  }
  .ql-editor-view li {
    margin-bottom: 8px;
    font-size: 0.97rem;
    line-height: 1.8;
    color: rgba(248,231,208,0.7);
  }
  .ql-editor-view p {
    margin-bottom: 14px;
  }
  .ql-editor-view p:last-child {
    margin-bottom: 0;
  }
  .ql-editor-view strong {
    color: #f8e7d0;
    font-weight: 700;
  }
  .pdt-notes-detail { display: flex; flex-direction: column; gap: 16px; }
  .pdt-note-block {
    padding: 14px 18px;
    background: rgba(212,166,88,0.05);
    border: 1px solid rgba(212,166,88,0.12);
    border-radius: 14px;
  }
  .pdt-note-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #d4a658;
    margin-bottom: 8px;
  }
  .pdt-note-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
  }
  .pdt-note-dot--top { background: #f5a623; }
  .pdt-note-dot--heart { background: #d4a658; }
  .pdt-note-dot--base { background: #8a6525; }
  .pdt-note-val { font-size: 0.92rem; color: rgba(248,231,208,0.75); line-height: 1.6; }

  .pdt-info-card {
    background: rgba(212,166,88,0.06);
    border: 1px solid rgba(212,166,88,0.18);
    border-radius: 18px;
    padding: 24px;
  }
  .pdt-info-card__title {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #d4a658;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(212,166,88,0.15);
  }
  .pdt-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    font-size: 0.87rem;
  }
  .pdt-info-row:last-child { border-bottom: none; }
  .pdt-info-row span { color: rgba(248,231,208,0.5); }
  .pdt-info-row strong { color: #f8e7d0; font-weight: 600; text-align: right; max-width: 60%; }

  /* ── Shipping Tab ── */
  .pdt-shipping-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
  }
  @media(max-width:860px) { .pdt-shipping-grid { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:480px) { .pdt-shipping-grid { grid-template-columns: 1fr; } }

  .pdt-ship-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(212,166,88,0.14);
    border-radius: 18px;
    padding: 26px 22px;
    text-align: center;
    transition: border-color 0.2s, transform 0.2s;
  }
  .pdt-ship-card:hover { border-color: rgba(212,166,88,0.35); transform: translateY(-3px); }
  .pdt-ship-icon { font-size: 2.2rem; margin-bottom: 14px; }
  .pdt-ship-title { font-size: 0.88rem; font-weight: 700; color: #d4a658; margin-bottom: 10px; letter-spacing: 0.05em; text-transform: uppercase; }
  .pdt-ship-text { font-size: 0.86rem; line-height: 1.65; color: rgba(248,231,208,0.6); }
  .pdt-ship-note {
    background: rgba(212,166,88,0.07);
    border: 1px solid rgba(212,166,88,0.2);
    border-radius: 14px;
    padding: 16px 22px;
    font-size: 0.9rem;
    color: rgba(248,231,208,0.7);
    text-align: center;
  }
  .pdt-ship-note strong { color: #d4a658; }

  /* ── Reviews Tab ── */
  .pdt-reviews-top {
    display: flex;
    gap: 40px;
    align-items: flex-start;
    margin-bottom: 36px;
    padding-bottom: 36px;
    border-bottom: 1px solid rgba(212,166,88,0.12);
  }
  @media(max-width:640px) { .pdt-reviews-top { flex-direction: column; gap: 24px; } }

  .pdt-rating-summary { text-align: center; min-width: 130px; }
  .pdt-rating-big { font-size: 3.5rem; font-weight: 900; color: #f8e7d0; line-height: 1; }
  .pdt-rating-stars { display: flex; justify-content: center; gap: 3px; margin: 8px 0 6px; }
  .pdt-star { font-size: 1.4rem; color: rgba(212,166,88,0.45); }
  .pdt-star--full { color: #d4a658; }
  .pdt-star--half { color: #d4a658; opacity: 0.6; }
  .pdt-rating-count { font-size: 0.78rem; color: rgba(248,231,208,0.7); }

  .pdt-rating-bars { flex: 1; display: flex; flex-direction: column; gap: 10px; }
  .pdt-bar-row { display: flex; align-items: center; gap: 12px; font-size: 0.82rem; }
  .pdt-bar-label { color: rgba(248,231,208,0.85); white-space: nowrap; min-width: 36px; text-align: right; }
  .pdt-bar-track { flex: 1; height: 7px; background: rgba(255,255,255,0.07); border-radius: 10px; overflow: hidden; }
  .pdt-bar-fill { height: 100%; background: linear-gradient(90deg, #d4a658, #f5a623); border-radius: 10px; transition: width 0.5s ease; }
  .pdt-bar-count { color: rgba(248,231,208,0.65); font-size: 0.78rem; min-width: 20px; }

  .pdt-no-reviews { text-align: center; padding: 40px 20px; color: rgba(248,231,208,0.75); }
  .pdt-no-reviews-icon { font-size: 2rem; color: rgba(212,166,88,0.35); margin-bottom: 12px; }
  .pdt-no-reviews p { font-size: 0.95rem; }

  /* Review cards */
  .pdt-review-card {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(212,166,88,0.22);
    border-radius: 16px;
    padding: 22px 24px;
    margin-bottom: 16px;
  }
  .pdt-review-card__header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; gap: 10px; flex-wrap: wrap; }
  .pdt-review-card__name { font-weight: 700; color: #ffffff; font-size: 0.95rem; }
  .pdt-review-card__meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .pdt-review-card__stars { color: #d4a658; font-size: 0.9rem; }
  .pdt-review-card__date { font-size: 0.75rem; color: rgba(248,231,208,0.55); }
  .pdt-review-card__body { font-size: 0.94rem; line-height: 1.7; color: rgba(248,231,208,0.9); }

  /* Write review form */
  .pdt-write-review {
    background: rgba(212,166,88,0.04);
    border: 1px solid rgba(212,166,88,0.15);
    border-radius: 20px;
    padding: 32px 28px;
    margin-top: 36px;
  }
  .pdt-rev-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
  @media(max-width:540px) { .pdt-rev-row { grid-template-columns: 1fr; } }
  .pdt-rev-field { display: flex; flex-direction: column; gap: 8px; margin-bottom: 18px; }
  .pdt-rev-field label { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: #d4a658; }
  .pdt-rev-field input,
  .pdt-rev-field textarea {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(212,166,88,0.22);
    border-radius: 12px;
    color: #f8e7d0;
    padding: 11px 16px;
    font-size: 0.92rem;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
    resize: vertical;
  }
  .pdt-rev-field input::placeholder,
  .pdt-rev-field textarea::placeholder { color: rgba(248,231,208,0.3); }
  .pdt-rev-field input:focus,
  .pdt-rev-field textarea:focus { border-color: #d4a658; }

  .pdt-star-picker { display: flex; gap: 6px; }
  .pdt-star-pick {
    background: none;
    border: none;
    font-size: 1.8rem;
    color: rgba(212,166,88,0.25);
    cursor: pointer;
    transition: color 0.15s, transform 0.15s;
    padding: 0;
    line-height: 1;
  }
  .pdt-star-pick:hover,
  .pdt-star-pick.is-selected { color: #d4a658; }
  .pdt-star-pick:hover { transform: scale(1.15); }

  .pdt-rev-submit {
    background: linear-gradient(135deg, #d4a658, #9d6f20);
    color: #111;
    font-weight: 700;
    font-size: 0.88rem;
    letter-spacing: 0.06em;
    border: none;
    border-radius: 12px;
    padding: 12px 28px;
    cursor: pointer;
    transition: all 0.2s;
    text-transform: uppercase;
  }
  .pdt-rev-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(212,166,88,0.3); }
  .pdt-rev-msg { margin-top: 12px; font-size: 0.88rem; padding: 10px 16px; border-radius: 10px; }
  .pdt-rev-msg--success { background: rgba(79,200,100,0.1); color: #5fcf6e; border: 1px solid rgba(79,200,100,0.2); }
  .pdt-rev-msg--error { background: rgba(220,53,69,0.1); color: #f07080; border: 1px solid rgba(220,53,69,0.2); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ── Tab switching ── */
  const tabBtns = document.querySelectorAll('.pdt-tab-btn');
  const tabPanels = document.querySelectorAll('.pdt-tab-panel');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tab;
      tabBtns.forEach(b => { b.classList.remove('is-active'); b.setAttribute('aria-selected','false'); });
      tabPanels.forEach(p => { p.classList.remove('is-active'); p.hidden = true; });
      btn.classList.add('is-active');
      btn.setAttribute('aria-selected','true');
      const panel = document.getElementById('tab-' + target);
      panel.classList.add('is-active');
      panel.hidden = false;
    });
  });

  /* ── Review API integration ── */
  const PRODUCT_DB_ID = '{{ $product["db_id"] }}';

  function fetchReviews() {
    fetch(`/products/${PRODUCT_DB_ID}/ratings`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          renderReviews(data.ratings, data.average_rating, data.rating_count);
        }
      })
      .catch(err => console.error('Error fetching reviews:', err));
  }

  function renderStars(avg) {
    const full  = Math.floor(avg);
    const hasHalf = (avg - full) >= 0.5;
    return [1,2,3,4,5].map(i => {
      if (i <= full) return '<span class="pdt-star pdt-star--full">★</span>';
      if (i === full + 1 && hasHalf) return '<span class="pdt-star pdt-star--half">★</span>';
      return '<span class="pdt-star pdt-star--empty">★</span>';
    }).join('');
  }

  function updateTopRatingSummary(reviews, averageRating, ratingCount) {
    const topSummary = document.getElementById('product-rating-summary-top');
    const topStars = document.getElementById('top-stars');
    const topVal = document.getElementById('top-rating-val');
    const topCount = document.getElementById('top-rating-count');

    if (!topSummary) return;

    if (reviews.length === 0) {
      topStars.innerHTML = [1,2,3,4,5].map(() => '<span class="pdt-star pdt-star--empty" style="color: rgba(157, 111, 32, 0.22);">★</span>').join('');
      topVal.textContent = '0.0';
      topCount.textContent = '(0 reviews)';
      topSummary.style.display = 'flex';
      return;
    }

    const avg = parseFloat(averageRating || 0);
    
    // Render stars
    const full = Math.floor(avg);
    const hasHalf = (avg - full) >= 0.5;
    topStars.innerHTML = [1,2,3,4,5].map(i => {
      if (i <= full) return '<span class="pdt-star pdt-star--full" style="color: #9d6f20;">★</span>';
      if (i === full + 1 && hasHalf) return '<span class="pdt-star pdt-star--half" style="color: #9d6f20; opacity: 0.7;">★</span>';
      return '<span class="pdt-star pdt-star--empty" style="color: rgba(157, 111, 32, 0.22);">★</span>';
    }).join('');

    topVal.textContent = avg.toFixed(1);
    topCount.textContent = `(${ratingCount} review${ratingCount !== 1 ? 's' : ''})`;
    topSummary.style.display = 'flex';
  }

  function renderReviews(reviews, averageRating, ratingCount) {
    updateTopRatingSummary(reviews, averageRating, ratingCount);

    const list  = document.getElementById('reviews-list');
    const badge = document.getElementById('review-count-badge');
    const avgEl = document.getElementById('avg-rating-display');
    const starsEl = document.getElementById('avg-stars-display');
    const totalEl = document.getElementById('total-reviews-label');

    badge.textContent = reviews.length;

    if (reviews.length === 0) {
      list.innerHTML = `
        <div class="pdt-no-reviews" id="no-reviews-msg">
          <div class="pdt-no-reviews-icon">✦</div>
          <p>No reviews yet. Be the first to share your experience!</p>
        </div>
      `;
      avgEl.textContent = '—';
      starsEl.innerHTML = [1,2,3,4,5].map(() => '<span class="pdt-star pdt-star--empty">★</span>').join('');
      totalEl.textContent = 'No reviews yet';
      
      // Reset bars
      [5,4,3,2,1].forEach(s => {
        const row = document.querySelector(`.pdt-bar-row[data-star="${s}"]`);
        if (row) {
          row.querySelector('.pdt-bar-fill').style.width = '0%';
          row.querySelector('.pdt-bar-count').textContent = '0';
        }
      });
      return;
    }

    avgEl.textContent = averageRating ? parseFloat(averageRating).toFixed(1) : '—';
    starsEl.innerHTML = renderStars(averageRating);
    totalEl.textContent = ratingCount + ' review' + (ratingCount !== 1 ? 's' : '');

    // Rating bars
    const counts = {5:0,4:0,3:0,2:0,1:0};
    reviews.forEach(r => {
      if (counts[r.rating] !== undefined) {
        counts[r.rating]++;
      }
    });

    [5,4,3,2,1].forEach(s => {
      const row = document.querySelector(`.pdt-bar-row[data-star="${s}"]`);
      if (!row) return;
      const pct = reviews.length ? Math.round((counts[s] / reviews.length) * 100) : 0;
      row.querySelector('.pdt-bar-fill').style.width = pct + '%';
      row.querySelector('.pdt-bar-count').textContent = counts[s];
    });

    // Render cards
    const cards = reviews.map(r => {
      const reviewerName = r.reviewer_name;
      const hasName = !!reviewerName;
      const nameHtml = hasName ? `<div class="pdt-review-card__name">${reviewerName}</div>` : '';
      const emailStyle = hasName 
        ? 'font-size: 0.78rem; color: #d4a658; font-weight: 400; margin-top: 3px;'
        : 'font-size: 0.95rem; color: #d4a658; font-weight: 700; margin-top: 0;';
      const userEmail = r.user && r.user.email ? `<div style="${emailStyle}">${r.user.email}</div>` : '';
      const date = new Date(r.created_at).toLocaleDateString('en-PK', { year:'numeric', month:'short', day:'numeric' });
      const comment = r.comment || '';
      return `
        <div class="pdt-review-card">
          <div class="pdt-review-card__header">
            <div>
              ${nameHtml}
              ${userEmail}
            </div>
            <div class="pdt-review-card__meta">
              <span class="pdt-review-card__stars">${'★'.repeat(r.rating)}${'☆'.repeat(5-r.rating)}</span>
              <span class="pdt-review-card__date">${date}</span>
            </div>
          </div>
          <div class="pdt-review-card__body">${comment}</div>
        </div>
      `;
    }).join('');

    list.innerHTML = cards;
  }

  /* ── Star picker ── */
  let selectedRating = 0;
  const starPicks = document.querySelectorAll('.pdt-star-pick');
  const ratingInput = document.getElementById('rev-rating');

  starPicks.forEach((btn, idx) => {
    btn.addEventListener('mouseenter', () => {
      starPicks.forEach((b, i) => b.classList.toggle('is-selected', i <= idx));
    });
    btn.addEventListener('mouseleave', () => {
      starPicks.forEach((b, i) => b.classList.toggle('is-selected', i < selectedRating));
    });
    btn.addEventListener('click', () => {
      selectedRating = idx + 1;
      ratingInput.value = selectedRating;
      starPicks.forEach((b, i) => b.classList.toggle('is-selected', i < selectedRating));
    });
  });

  /* ── Submit review ── */
  document.getElementById('review-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const name   = document.getElementById('rev-name').value.trim();
    const rating = parseInt(ratingInput.value);
    const comment = document.getElementById('rev-body').value.trim();
    const msg    = document.getElementById('rev-msg');

    if (!comment) {
      msg.className = 'pdt-rev-msg pdt-rev-msg--error';
      msg.textContent = 'Please fill in your review.';
      msg.style.display = 'block';
      return;
    }
    if (rating < 1) {
      msg.className = 'pdt-rev-msg pdt-rev-msg--error';
      msg.textContent = 'Please select a star rating.';
      msg.style.display = 'block';
      return;
    }

    const csrfEl = document.querySelector('meta[name="csrf-token"]');
    const token = csrfEl ? csrfEl.getAttribute('content') : '';

    fetch(`/products/${PRODUCT_DB_ID}/rate`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        name: name,
        rating: rating,
        comment: comment
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        msg.className = 'pdt-rev-msg pdt-rev-msg--success';
        msg.textContent = 'Thank you! Your review has been posted.';
        msg.style.display = 'block';
        
        this.reset();
        selectedRating = 0;
        ratingInput.value = 0;
        starPicks.forEach(b => b.classList.remove('is-selected'));
        
        fetchReviews();
      } else {
        msg.className = 'pdt-rev-msg pdt-rev-msg--error';
        msg.textContent = data.message || 'Something went wrong. Please try again.';
        msg.style.display = 'block';
      }
      setTimeout(() => msg.style.display = 'none', 4000);
    })
    .catch(err => {
      console.error('Error posting review:', err);
      msg.className = 'pdt-rev-msg pdt-rev-msg--error';
      msg.textContent = 'Error sending review. Please try again later.';
      msg.style.display = 'block';
      setTimeout(() => msg.style.display = 'none', 4000);
    });
  });

  // Top rating click to scroll to reviews tab
  const topCount = document.getElementById('top-rating-count');
  if (topCount) {
    topCount.addEventListener('click', () => {
      const reviewsSection = document.querySelector('.pdt-tabs-section');
      if (reviewsSection) {
        reviewsSection.scrollIntoView({ behavior: 'smooth' });
        const reviewsTabBtn = document.querySelector('.pdt-tab-btn[data-tab="reviews"]');
        if (reviewsTabBtn) {
          reviewsTabBtn.click();
        }
      }
    });
  }

  fetchReviews();
});
</script>

@endsection
