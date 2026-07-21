@extends('polani.layout')

@section('title', 'Polani Fragrance — Luxury Fragrance Boutique')
@section('meta_description', "More than a fragrance — it's a statement. Handcrafted extrait de parfum made for timeless elegance.")

@section('content')
  @php
    $featured = $signatureProduct ?? $products->first();
    $bestsellers = $products->take(5);
    $heroBanner = \App\Models\CarouselSlide::getPrimarySlide('home');
  @endphp

  <section class="hero hero--noir" aria-label="Featured banner">
    @if($heroBanner && $heroBanner->image_name)
      <img
        class="hero__bg-img"
        src="{{ $heroBanner->getImagePath('1200w') }}"
        width="1502"
        height="704"
        alt="{{ $heroBanner->title ?? 'Banner' }}"
        loading="eager"
        fetchpriority="high"
        decoding="async"
      />
    @else
      <img
        class="hero__bg-img"
        src="{{ asset('polani/assets/hero-noir-elixir-1024.jpg') }}"
        srcset="{{ asset('polani/assets/hero-noir-elixir-1024.jpg') }} 1024w, {{ asset('polani/assets/hero-noir-elixir.jpg') }} 1502w"
        sizes="100vw"
        width="1502"
        height="704"
        alt=""
        loading="eager"
        fetchpriority="high"
        decoding="async"
      />
    @endif
    <div class="hero__bg" aria-hidden="true"></div>
    <div class="container hero__inner">
      <div class="hero__content">
        @if($heroBanner)
          @if($heroBanner->eyebrow)
            @php
              $words = explode(' ', trim($heroBanner->eyebrow));
              $word1 = $words[0] ?? '';
              $word2 = $words[1] ?? '';
            @endphp
            @if($word1 && $word2 && count($words) === 2)
              <div class="hero__mark" aria-hidden="true">
                <div class="hero__mark-word">{{ $word1 }}</div>
                <div class="hero__mark-sub">{{ $word2 }}</div>
              </div>
            @else
              <div class="hero__mark" aria-hidden="true" style="margin-bottom:15px;">
                <div class="hero__mark-sub" style="font-size: 0.85rem; letter-spacing: 0.3em; margin: 0; text-transform: uppercase; color: #d4a658;">{{ $heroBanner->eyebrow }}</div>
              </div>
            @endif
          @else
            <div class="hero__mark" aria-hidden="true">
              <div class="hero__mark-word">POLANI</div>
              <div class="hero__mark-sub">FRAGRANCE</div>
            </div>
          @endif

          <h1 class="hero__title">{!! nl2br(e(html_entity_decode($heroBanner->title, ENT_QUOTES, 'UTF-8'))) !!}</h1>

          @if($heroBanner->subtitle)
            <div class="hero__divider" aria-hidden="true"></div>
            <p class="hero__subtitle">{{ $heroBanner->subtitle }}</p>
          @endif

          <div class="hero__actions">
            @if($heroBanner->cta_text && $heroBanner->cta_url)
              <a class="btn btn--primary" href="{{ $heroBanner->cta_url }}">{{ $heroBanner->cta_text }}</a>
            @endif
            @if($heroBanner->secondary_cta_text && $heroBanner->secondary_cta_url)
              <a class="btn btn--ghost" href="{{ $heroBanner->secondary_cta_url }}">{{ $heroBanner->secondary_cta_text }}</a>
            @endif
          </div>
        @else
          <div class="hero__mark" aria-hidden="true">
            <div class="hero__mark-word">POLANI</div>
            <div class="hero__mark-sub">FRAGRANCE</div>
          </div>
          <h1 class="hero__title">More Than A Fragrance,<br />It's A Statement.</h1>
          <div class="hero__divider" aria-hidden="true"></div>
          <p class="hero__subtitle">Handcrafted extrait de parfum made for timeless elegance.</p>
          <div class="hero__actions">
            <a class="btn btn--primary" href="{{ route('polani.collection') }}">Discover Collection</a>
            <a class="btn btn--ghost" href="{{ route('polani.collection') }}#bestsellers">Shop Now</a>
          </div>
        @endif
      </div>
    </div>
  </section>

  <section class="section section--ivory" id="collections">
    <div class="container">
      <div class="section-head">
        <h2 class="section-title">Explore Our Collections</h2>
        <div class="section-rule" aria-hidden="true"></div>
      </div>

      <div class="category-grid">
        <a class="category-card" href="{{ route('polani.collection') }}#men">
          <img src="{{ asset('polani/assets/cat-men.png') }}" alt="men" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="men"></span>
            <div class="category-card__label">MEN</div>
          </div>
        </a>
        <a class="category-card" href="{{ route('polani.women') }}">
          <img src="{{ asset('polani/assets/cat-women.jpeg') }}" alt="women" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="women"></span>
            <div class="category-card__label">WOMEN</div>
          </div>
        </a>
        <a class="category-card" href="{{ route('polani.attars') }}">
          <img src="{{ asset('polani/assets/cat-attars.jpeg') }}" alt="" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="attar"></span>
            <div class="category-card__label">ATTARS</div>
          </div>
        </a>
        <a class="category-card" href="#signature">
          <img src="{{ asset('polani/assets/cat-signature.jpeg') }}" alt="" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="star"></span>
            <div class="category-card__label">SIGNATURE</div>
          </div>
        </a>
        <a class="category-card" href="{{ route('polani.oud') }}">
          <img src="{{ asset('polani/assets/cat-oud.jpeg') }}" alt="" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="oud"></span>
            <div class="category-card__label">OUD</div>
          </div>
        </a>
        <a class="category-card" href="{{ route('polani.scented-candles') }}">
          <img src="{{ asset('polani/assets/cat-candles.jpg') }}" alt="Scented Candles" style="object-fit:cover;" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="candle"></span>
            <div class="category-card__label">SCENTED CANDLES</div>
          </div>
        </a>
        <a class="category-card" href="{{ route('polani.contact') }}">
          <img src="{{ asset('polani/assets/cat-contact.jpg') }}" alt="Contact Us" style="object-fit:cover;" />
          <div class="category-card__meta">
            <span class="category-card__icon" aria-hidden="true" data-icon="mail"></span>
            <div class="category-card__label">CONTACT US</div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <section class="section" id="signature">
    <div class="container split">
      <div class="split__media">
        <img
          class="frame-img"
          src="{{ asset('polani/assets/product-qasr-al-oud-408.jpg') }}"
          srcset="{{ asset('polani/assets/product-qasr-al-oud-408.jpg') }} 408w, {{ asset('polani/assets/product-qasr-al-oud-816.jpg') }} 816w, {{ asset('polani/assets/product-qasr-al-oud.jpg') }} 960w"
          width="408"
          height="544"
          alt="Qasr Al Oud bottle"
          loading="lazy"
          decoding="async"
        />
      </div>
      <div class="split__content">
        <div class="eyebrow">OUR SIGNATURE</div>
        <h2 class="split__title">{{ $featured['name'] ?? 'Qasr Al Oud' }}<br />A Signature Oud Experience</h2>
        <p class="split__text">
          A rich blend of rare ingredients crafted for unforgettable presence.
        </p>
        <div class="split__actions">
          <a class="btn btn--primary" href="{{ route('polani.product', ['slug' => $featured['slug'] ?? 'qasr-al-oud']) }}">Explore {{ $featured['name'] ?? 'Qasr Al Oud' }}</a>
        </div>

        <div class="notes">
          <div class="notes__col">
            <div class="notes__label">TOP NOTES</div>
            <div class="notes__value">Saffron, Bergamot</div>
          </div>
          <div class="notes__col">
            <div class="notes__label">HEART NOTES</div>
            <div class="notes__value">Oud, Rose</div>
          </div>
          <div class="notes__col">
            <div class="notes__label">BASE NOTES</div>
            <div class="notes__value">Amber, Musk, Patchouli</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section section--dark">
    <div class="container">
      <div class="section-head section-head--center">
        <div class="eyebrow">WHY CHOOSE POLANI</div>
        <h2 class="section-title section-title--small">Luxury, made effortless.</h2>
      </div>

      <div class="features">
        <div class="feature">
          <div class="feature__icon" aria-hidden="true" data-icon="quality"></div>
          <div class="feature__title">Premium Quality</div>
          <div class="feature__text">Finest ingredients sourced globally.</div>
        </div>
        <div class="feature">
          <div class="feature__icon" aria-hidden="true" data-icon="hand"></div>
          <div class="feature__title">Crafted With Care</div>
          <div class="feature__text">Handcrafted to perfection.</div>
        </div>
        <div class="feature">
          <div class="feature__icon" aria-hidden="true" data-icon="clock"></div>
          <div class="feature__title">Long Lasting</div>
          <div class="feature__text">Scents that stay with you.</div>
        </div>
        <div class="feature">
          <div class="feature__icon" aria-hidden="true" data-icon="gift"></div>
          <div class="feature__title">Luxury Packaging</div>
          <div class="feature__text">Beautifully wrapped for you.</div>
        </div>
        <div class="feature">
          <div class="feature__icon" aria-hidden="true" data-icon="truck"></div>
          <div class="feature__title">Free Shipping</div>
          <div class="feature__text">On orders above Rs 27,500.</div>
        </div>
      </div>
    </div>
  </section>

  {{-- ── Our Story Banner Section ── --}}
  <section class="story-banner-section" id="story">
    <img 
      class="story-banner-bg"
      src="{{ asset('polani/assets/story-banner.jpg') }}" 
      alt="How Polani Fragrance Was Started" 
      loading="lazy"
      decoding="async"
    />
    <div class="story-banner-overlay" aria-hidden="true"></div>
    <div class="container">
      <div class="story-banner-content">
        <h2 class="story-banner-title">
          How Polani Fragrance<br />Was Started
        </h2>
        <p class="story-banner-text">
          A journey born from a love for cinematic elegance and rare scents. Handcrafted with passion, bottled with elegance... from a small workshop on M.A Jinnah Road, Karachi. Explore our history.
        </p>
        <div>
          <a class="story-banner-btn" href="{{ route('polani.about') }}">
            Read Our Story
          </a>
        </div>
      </div>
    </div>
  </section>

  <style>
    .story-banner-section {
      position: relative;
      overflow: hidden;
      height: 550px;
      display: flex;
      align-items: center;
      background: #000;
    }
    .story-banner-bg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center right;
      z-index: 1;
      transition: transform 0.6s ease;
    }
    .story-banner-section:hover .story-banner-bg {
      transform: scale(1.02);
    }
    .story-banner-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, rgba(10, 10, 10, 0.95) 0%, rgba(10, 10, 10, 0.75) 45%, rgba(10, 10, 10, 0.2) 100%);
      z-index: 2;
    }
    .story-banner-content {
      position: relative;
      z-index: 3;
      max-width: 580px;
      padding: 20px 0;
    }
    .story-banner-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(28px, 4.5vw, 44px);
      line-height: 1.2;
      color: #f8e7d0;
      margin: 0 0 20px;
      font-weight: 600;
      letter-spacing: 0.03em;
      text-transform: uppercase;
    }
    .story-banner-text {
      font-size: 1.02rem;
      line-height: 1.8;
      color: rgba(248, 231, 208, 0.8);
      margin: 0 0 32px;
      font-weight: 300;
    }
    .story-banner-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #ffffff !important;
      color: #000000 !important;
      border: 1px solid #ffffff !important;
      padding: 14px 32px;
      font-weight: 600;
      border-radius: 8px;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      font-size: 0.85rem;
      transition: all 0.3s ease !important;
    }
    .story-banner-btn:hover {
      background: #d4a658 !important;
      border-color: #d4a658 !important;
      color: #111111 !important;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(212, 166, 88, 0.2);
    }

    @media (max-width: 768px) {
      .story-banner-section {
        height: 480px;
      }
      .story-banner-overlay {
        background: linear-gradient(180deg, rgba(10, 10, 10, 0.85) 0%, rgba(10, 10, 10, 0.95) 100%);
      }
      .story-banner-content {
        max-width: 100%;
        text-align: center;
        padding: 0 10px;
      }
      .story-banner-btn {
        width: 100%;
      }
    }

    @media (max-width: 600px) {
      .story-banner-section {
        height: 320px;
      }
      .story-banner-title {
        font-size: 22px;
      }
      .story-banner-text {
        font-size: 11px;
        line-height: 1.6;
        margin-bottom: 20px;
      }
      .story-banner-btn {
        padding: 10px 20px;
        font-size: 10px;
        border-radius: 6px;
      }
    }
  </style>

  <section class="section" id="bestsellers">
    <div class="container">
      <div class="section-head">
        <h2 class="section-title">Best Sellers</h2>
        <div class="section-rule" aria-hidden="true"></div>
      </div>

      <div class="product-grid">
        @foreach($bestsellers as $product)
          @include('polani.partials.product-card', ['product' => $product])
        @endforeach
      </div>
    </div>
  </section>

  @if(isset($homepageSections) && $homepageSections->isNotEmpty())
    @foreach($homepageSections as $section)
      @if(count($section['products']) > 0)
        <section class="section {{ ($section['bg_theme'] ?? 'dark') === 'ivory' ? 'section--ivory' : 'section--dark' }}" id="{{ $section['slug'] }}" style="{{ ($section['bg_theme'] ?? 'dark') === 'dark' ? 'padding-top: 0;' : '' }}">
          <div class="container">
            <div class="section-head">
              <h2 class="section-title">{{ $section['title'] }}</h2>
              <div class="section-rule" aria-hidden="true"></div>
            </div>

            <div class="product-grid">
              @foreach($section['products'] as $product)
                @include('polani.partials.product-card', ['product' => $product])
              @endforeach
            </div>
          </div>
        </section>
      @endif
    @endforeach
  @endif

  {{-- ── Our Blogs Section ── --}}
  @if(isset($blogs) && $blogs->isNotEmpty())
    <section class="section section--ivory" id="blogs-section" style="border-top:1px solid rgba(212,166,88,0.12); border-bottom:1px solid rgba(212,166,88,0.12); padding: 80px 0;">
      <div class="container">
        <div class="section-head" style="margin-bottom: 50px;">
          <div class="eyebrow" style="text-align: center; color: #d4a658; letter-spacing: 0.2em; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 10px;">LATEST INSIGHTS</div>
          <h2 class="section-title" style="text-align: center; font-family: 'Playfair Display', serif; font-size: 2.8rem; color: #111; margin: 0;">Our Blogs</h2>
          <div class="section-rule" aria-hidden="true" style="width: 80px; height: 1px; background: #d4a658; margin: 24px auto 0;"></div>
        </div>

        <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
          @foreach($blogs as $blog)
            <article class="blog-card" style="background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
              @php
                $blogImg = $blog->image_path ? asset($blog->image_path) : asset('polani/assets/story-packaging.svg');
              @endphp
              <div class="blog-card__image" style="position: relative; height: 220px; overflow: hidden; background: #000;">
                <!-- Blurred Background -->
                <div style="position: absolute; inset: 0; background-image: url('{{ $blogImg }}'); background-size: cover; background-position: center; filter: blur(15px) brightness(0.65); transform: scale(1.15); z-index: 1;"></div>
                <!-- Main Image -->
                <img src="{{ $blogImg }}" 
                     alt="{{ $blog->title }}" 
                     style="position: relative; width: 100%; height: 100%; object-fit: contain; z-index: 2; transition: transform 0.5s ease;" />
                <div class="blog-card__date" style="position: absolute; bottom: 16px; left: 16px; background: rgba(10, 10, 10, 0.85); color: #d4a658; font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 20px; letter-spacing: 0.05em; border: 1px solid rgba(212,166,88,0.2); z-index: 3;">
                  {{ $blog->created_at->format('M d, Y') }}
                </div>
              </div>
              
              <div class="blog-card__content" style="padding: 24px; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                <div>
                  <h3 class="blog-card__title" style="font-family: 'Playfair Display', serif; font-size: 1.35rem; color: #111; margin: 0 0 12px; line-height: 1.4; font-weight: 700;">
                    <a href="{{ route('polani.blog.detail', $blog->slug) }}" style="text-decoration: none; color: inherit; transition: color 0.2s;">
                      {{ $blog->title }}
                    </a>
                  </h3>
                  <p class="blog-card__excerpt" style="font-size: 0.9rem; line-height: 1.6; color: #555; margin: 0 0 20px;">
                    {{ Str::limit(strip_tags(htmlspecialchars_decode($blog->content)), 110) }}
                  </p>
                </div>
                
                <a href="{{ route('polani.blog.detail', $blog->slug) }}" class="blog-card__link" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #d4a658; font-weight: 700; font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em; transition: color 0.2s;">
                  Read Article <span style="font-size: 1.1rem; line-height: 1;">&rarr;</span>
                </a>
              </div>
            </article>
          @endforeach
        </div>

        {{-- Read More button aligning to bottom right --}}
        <div style="display: flex; justify-content: flex-end; margin-top: 40px;">
          <a href="{{ route('polani.blogs') }}" class="btn btn--ghost btn--dark" style="padding: 12px 32px; font-size: 0.88rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border-radius: 999px;">
            Read More Blogs &rarr;
          </a>
        </div>
      </div>
    </section>

    {{-- Blog card hover styles injected dynamically --}}
    <style>
      .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        border-color: rgba(212,166,88,0.25) !important;
      }
      .blog-card:hover img {
        transform: scale(1.06);
      }
      .blog-card__title a:hover {
        color: #d4a658 !important;
      }
      .blog-card__link:hover {
        color: #9d6f20 !important;
      }
    </style>
  @endif

  <section class="trust section section--ivory">
    <div class="container trust__grid">
      <div class="trust__item">
        <span class="trust__icon" aria-hidden="true" data-icon="lock"></span>
        <div class="trust__title">Secure Payment</div>
        <div class="trust__text">100% secure checkout</div>
      </div>
      <div class="trust__item">
        <span class="trust__icon" aria-hidden="true" data-icon="box"></span>
        <div class="trust__title">Easy Returns</div>
        <div class="trust__text">Hassle free returns</div>
      </div>
      <div class="trust__item">
        <span class="trust__icon" aria-hidden="true" data-icon="badge"></span>
        <div class="trust__title">100% Authentic</div>
        <div class="trust__text">Original and authentic products</div>
      </div>
      <div class="trust__item">
        <span class="trust__icon" aria-hidden="true" data-icon="headset"></span>
        <div class="trust__title">Dedicated Support</div>
        <div class="trust__text">We're here to help you always</div>
      </div>
    </div>
  </section>
@endsection
