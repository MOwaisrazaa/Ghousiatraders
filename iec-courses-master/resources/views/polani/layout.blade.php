<!doctype html>
<html lang="en">
  @php
    $footer = \App\Models\FooterSetting::getSettings();
    $whatsappPhone = preg_replace('/\D+/', '', $footer->phone ?? '');
    $navPages = \App\Models\NavigationPage::where('is_active', true)->orderBy('order')->get();
  @endphp
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Polani Fragrance')</title>
    <meta name="description" content="@yield('meta_description', 'Luxury fragrance boutique.')" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      rel="preload"
      as="style"
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500;600&display=swap"
      onload="this.onload=null;this.rel='stylesheet'"
    />
    <noscript>
      <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500;600&display=swap"
      />
    </noscript>

    <link rel="stylesheet" href="{{ asset('polani/css/styles.css?v=1.0.4') }}" />
    <script defer src="{{ asset('polani/js/polani-theme.js') }}"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('polani/assets/logos/favicon-tab.png?v=3') }}" />

    @livewireStyles
    @stack('head')
  </head>
  <body class="@yield('body_class')">
    <a class="skip-link" href="#main">Skip to content</a>

    <div class="topbar">
      <div class="container topbar__inner">
        <div class="topbar__left">FREE SHIPPING ON ORDERS ABOVE RS 10,000</div>
        <div class="topbar__right">
          <a class="topbar__link" href="{{ route('polani.track-order') }}">TRACK ORDER</a>
          <span class="topbar__sep">|</span>
          <a class="topbar__link" href="{{ route('polani.contact') }}">HELP</a>
          <span class="topbar__sep">|</span>
          <button class="topbar__btn" type="button">
            PKR (Rs) <span aria-hidden="true">▾</span>
          </button>
        </div>
      </div>
    </div>

    <header class="header" data-header>
      <div class="container header__inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Polani Fragrance Home" style="display: flex; align-items: center; gap: 8px; flex-direction: row; text-decoration: none;">
          <img src="{{ asset('polani/assets/logos/logo-white-trans.png?v=4') }}" alt="Polani Fragrance Logo" style="height: 32px; width: auto; object-fit: contain;">
          <div style="display: flex; flex-direction: column; line-height: 1;">
            <span class="brand__wordmark">POLANI</span>
            <span class="brand__sub" style="margin-top: 2px;">FRAGRANCE</span>
          </div>
        </a>

        <button class="nav-toggle" type="button" aria-label="Open menu" data-nav-toggle>
          <span class="nav-toggle__bar"></span>
          <span class="nav-toggle__bar"></span>
          <span class="nav-toggle__bar"></span>
        </button>

        <nav class="nav" aria-label="Primary" data-nav>
          @foreach($navPages as $page)
            @php
              $isActive = false;
              if ($page->type === 'custom') {
                  $isActive = request()->routeIs('polani.custom-page') && request()->route('slug') === $page->slug;
              } else {
                  $cleanLink = trim($page->link, '/');
                  if ($cleanLink === '') {
                      $isActive = request()->is('/');
                  } else {
                      $isActive = request()->is($cleanLink) || request()->is($cleanLink . '/*');
                  }
              }
              
              $href = $page->type === 'custom' 
                  ? route('polani.custom-page', $page->slug) 
                  : (str_starts_with($page->link, 'http') || str_starts_with($page->link, '#') ? $page->link : url($page->link));
            @endphp
            <a class="nav__link {{ $isActive ? 'is-active' : '' }}" href="{{ $href }}">{{ strtoupper($page->name) }}</a>
          @endforeach
        </nav>

        <div class="header-actions">
          <button class="icon-btn" type="button" aria-label="Search" data-search-open>
            <span class="icon" aria-hidden="true" data-icon="search"></span>
          </button>
          @auth
            <form method="POST" action="{{ route('logout') }}" class="header-actions__form">
              @csrf
              <button class="header-action-link" type="submit" aria-label="Logout">
                <span class="icon" aria-hidden="true" data-icon="logout"></span>
                <span class="header-action-link__text">LOGOUT</span>
              </button>
            </form>
          @else
            <a class="header-action-link" href="{{ route('sign-in') }}" aria-label="Sign In">
              <span class="icon" aria-hidden="true" data-icon="user"></span>
              <span class="header-action-link__text">SIGN IN</span>
            </a>
          @endauth
          <a class="icon-btn" href="{{ route('shopping-cart') }}" aria-label="Cart">
            <span class="icon" aria-hidden="true" data-icon="cart"></span>
            <span class="badge" data-cart-badge>{{ (int)($cartCount ?? 0) }}</span>
          </a>
        </div>
      </div>

      <!-- Persistent Search Bar for Mobile -->
      <div class="header__search-mobile">
        <form action="{{ route('polani.collection') }}" method="GET" class="search-bar-form">
          <input type="search" name="q" placeholder="Search..." aria-label="Search" class="search-bar-input" value="{{ request('q') }}">
          <button type="submit" class="search-bar-btn" aria-label="Submit Search">
            <span class="icon" aria-hidden="true" data-icon="search"></span>
          </button>
        </form>
      </div>
    </header>

    <main id="main">
      @if (session('error'))
        <div class="container" style="padding-top: 16px;">
          <div class="alert alert--danger">{{ session('error') }}</div>
        </div>
      @endif
      @if (session('success'))
        <div class="container" style="padding-top: 16px;">
          <div class="alert alert--success">{{ session('success') }}</div>
        </div>
      @endif

      @yield('content')
    </main>

    <footer class="footer">
      <div class="container footer__grid">
        <div class="footer__brand">
          <div class="footer__logo" style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
            <img src="{{ asset('polani/assets/logos/logo-white-trans.png?v=4') }}" alt="Polani Fragrance Logo" style="height: 48px; width: auto; object-fit: contain;">
            <div style="display: flex; flex-direction: column; line-height: 1;">
              <span style="font-family: var(--serif); font-weight: 600; letter-spacing: 0.18em; font-size: 20px; color: #fff;">{{ $footer->brand_name }}</span>
              <span style="font-size: 11px; letter-spacing: 0.36em; margin-top: 4px; color: rgba(255,255,255,.7);">{{ $footer->brand_tagline }}</span>
            </div>
          </div>
          <p class="footer__desc">{{ $footer->brand_description }}</p>
          <div class="social">
            <a class="social__link" href="{{ $footer->instagram_url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" data-icon="ig"></a>
            <a class="social__link" href="{{ $footer->tiktok_url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok" data-icon="tt"></a>
            <a class="social__link" href="{{ $footer->facebook_url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" data-icon="fb"></a>
            <a class="social__link" href="{{ $footer->youtube_url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube" data-icon="yt"></a>
            <a class="social__link" href="{{ $footer->linkedin_url ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" data-icon="li"></a>
          </div>
        </div>

        <div class="footer__col">
          <div class="footer__title">Shop</div>
          <a class="footer__link" href="{{ route('polani.collection') }}">MEN</a>
          <a class="footer__link" href="{{ route('polani.women') }}">WOMEN</a>
          <a class="footer__link" href="{{ route('polani.attars') }}">ATTARS</a>
          <a class="footer__link" href="{{ route('polani.oud') }}">OUD</a>
          <a class="footer__link" href="{{ route('home') }}#signature">SIGNATURE</a>
          <a class="footer__link" href="{{ route('polani.scented-candles') }}">SCENTED CANDLES</a>
        </div>

        <div class="footer__col">
          <div class="footer__title">Customer Care</div>
          <a class="footer__link" href="{{ route('polani.track-order') }}">TRACK ORDER</a>
          <a class="footer__link" href="{{ route('polani.shipping') }}">SHIPPING &amp; DELIVERY</a>
          <a class="footer__link" href="{{ route('polani.returns') }}">RETURNS &amp; REFUNDS</a>
          <a class="footer__link" href="{{ route('polani.faq') }}">FAQ</a>
          <a class="footer__link" href="{{ route('polani.terms') }}">TERMS &amp; CONDITIONS</a>
          <a class="footer__link" href="{{ route('polani.privacy') }}">PRIVACY POLICY</a>
        </div>

        <div class="footer__col">
          <div class="footer__title">Contact Us</div>
          <div class="footer__info">Email: {{ $footer->email }}</div>
          <div class="footer__info">Phone: {{ $footer->phone }}</div>
          <div class="footer__info">Mon–Sat: 9AM – 9PM</div>
          <div class="footer__info">{{ $footer->address }}</div>
          <a class="footer__link footer__link--cta" href="{{ route('polani.contact') }}">GET IN TOUCH</a>
        </div>
      </div>

      <div class="container footer__bottom">
        <div class="footer__copy footer__copy--live">&copy; <span data-year></span> <a href="{{ $footer->copyright_url }}" target="_blank" rel="noopener noreferrer">{{ $footer->copyright_name }}</a>. {{ $footer->footer_text }} | Powered by <a href="https://snipezon.com" target="_blank" rel="noopener noreferrer">snipezon.com</a></div>
        <div class="footer__payments" aria-label="Payments">
          <span class="pay pay--logo" data-icon="visa" aria-hidden="true"></span>
          <span class="pay pay--logo" data-icon="mc" aria-hidden="true"></span>
          <span class="pay pay--logo" data-icon="amex" aria-hidden="true"></span>
          <span class="pay pay--logo" data-icon="paypal" aria-hidden="true"></span>
          <span class="pay pay--logo" data-icon="applepay" aria-hidden="true"></span>
        </div>
      </div>
    </footer>

    <a
      class="whatsapp-fab"
      href="https://wa.me/{{ $whatsappPhone }}?text={{ rawurlencode('Assalam o Alaikum, I want to order from Polani Fragrance.') }}"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Chat with us on WhatsApp"
      title="Chat with us on WhatsApp"
    >
      <span class="whatsapp-fab__icon" aria-hidden="true" data-icon="wa"></span>
      <span class="whatsapp-fab__label">WhatsApp</span>
    </a>

    <div class="drawer" role="dialog" aria-modal="true" aria-label="Search" hidden data-search>
      <div class="drawer__overlay" data-search-close></div>
      <div class="drawer__panel">
        <div class="drawer__head">
          <div class="drawer__title">Search</div>
          <button class="icon-btn" type="button" aria-label="Close" data-search-close>
            <span class="icon" aria-hidden="true" data-icon="x"></span>
          </button>
        </div>
        <form class="search" data-search-form>
          <label class="sr-only" for="q">Search</label>
          <input id="q" name="q" class="search__input" type="search" placeholder="Search products…" />
          <button class="btn btn--primary" type="submit">Search</button>
        </form>
        <div class="search__hint">Tip: try “Elixir”, “Oud”, “Candle”.</div>
      </div>
    </div>



    @livewireScripts
    @stack('scripts')
  </body>
</html>
