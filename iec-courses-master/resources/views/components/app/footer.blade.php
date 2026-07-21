@php
    $footer = \App\Models\FooterSetting::getSettings();
@endphp

<footer class="footer pt-5 pb-4 mt-5">
    <div class="row px-4">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="d-flex align-items-center mb-3">
                <img src="{{ asset('polani/assets/hero-noir-elixir.jpg') }}" alt="{{ $footer->brand_name }} Logo" width="45" height="45" class="me-2" style="object-fit:cover;border-radius:10px;">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #c6a46c;">{{ $footer->brand_name }}</h3>
                    <small class="text-muted">{{ $footer->brand_tagline }}</small>
                </div>
            </div>
            <p class="text-sm text-muted mb-4 pe-lg-5">
                {{ $footer->brand_description }}
            </p>
            <div class="d-flex gap-3">
                @if($footer->facebook_url)
                    <a href="{{ $footer->facebook_url }}" class="btn btn-icon-only btn-outline-primary rounded-circle" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if($footer->twitter_url)
                    <a href="{{ $footer->twitter_url }}" class="btn btn-icon-only btn-outline-primary rounded-circle" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                @endif
                @if($footer->linkedin_url)
                    <a href="{{ $footer->linkedin_url }}" class="btn btn-icon-only btn-outline-primary rounded-circle" target="_blank" rel="noopener noreferrer" aria-label="Follow us on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                @endif
                @if($footer->youtube_url)
                    <a href="{{ $footer->youtube_url }}" class="btn btn-icon-only btn-outline-primary rounded-circle" target="_blank" rel="noopener noreferrer" aria-label="Subscribe to our YouTube channel"><i class="fab fa-youtube"></i></a>
                @endif
            </div>
        </div>

        <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
            <h4 class="text-uppercase text-xs font-weight-bold mb-3" style="color: #c6a46c;">Shop</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="{{ route('polani.collection') }}" class="nav-link p-0 text-muted text-sm">All Products</a></li>
                <li class="nav-item mb-2"><a href="{{ route('polani.collection') }}#men" class="nav-link p-0 text-muted text-sm">Men</a></li>
                <li class="nav-item mb-2"><a href="{{ route('polani.women') }}" class="nav-link p-0 text-muted text-sm">Women</a></li>
                <li class="nav-item mb-2"><a href="{{ route('polani.scented-candles') }}" class="nav-link p-0 text-muted text-sm">Candles</a></li>
            </ul>
        </div>

        <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
            <h4 class="text-uppercase text-xs font-weight-bold mb-3" style="color: #c6a46c;">Support</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="{{ route('polani.contact') }}" class="nav-link p-0 text-muted text-sm">Contact Us</a></li>
                <li class="nav-item mb-2"><a href="{{ route('polani.track-order') }}" class="nav-link p-0 text-muted text-sm">Track Order</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted text-sm">FAQs</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted text-sm">Terms of Service</a></li>
            </ul>
        </div>

        <div class="col-lg-4 col-md-4">
            <h4 class="text-uppercase text-xs font-weight-bold mb-3" style="color: #c6a46c;">Contact Info</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2 d-flex">
                    <i class="fas fa-map-marker-alt mt-1 me-3 text-primary text-sm"></i>
                    <span class="text-sm text-muted">{{ $footer->address }}</span>
                </li>
                <li class="nav-item mb-2 d-flex">
                    <i class="fas fa-envelope mt-1 me-3 text-primary text-sm"></i>
                    <span class="text-sm text-muted">{{ $footer->email }}</span>
                </li>
                <li class="nav-item d-flex">
                    <i class="fas fa-phone mt-1 me-3 text-primary text-sm"></i>
                    <span class="text-sm text-muted">{{ $footer->phone }}</span>
                </li>
            </ul>
        </div>
    </div>

    <hr class="horizontal dark my-4 opacity-1">

    <div class="row px-4">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <p class="text-sm text-muted mb-0">
                &copy; {{ date('Y') }} <a href="{{ $footer->copyright_url }}" class="font-weight-bold" style="color: #1a4a8e;" target="_blank">{{ $footer->copyright_name }}</a>. All Rights Reserved.
            </p>
        </div>
        <div class="col-md-6 text-center text-md-end">
            <span class="text-sm text-muted">{{ $footer->footer_text }}</span>
        </div>
    </div>
</footer>

<style>
    .footer .nav-link:hover {
        color: #c6a46c !important;
        transform: translateX(5px);
        transition: all 0.3s ease;
    }
    .footer .btn-icon-only {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .footer .btn-icon-only:hover {
        background: #c6a46c;
        color: #fff;
        transform: translateY(-3px);
    }
</style>

