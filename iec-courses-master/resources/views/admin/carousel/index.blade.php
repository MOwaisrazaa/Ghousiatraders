@extends('admin.layout')

@section('header', 'Banner Management')

@section('actions')
    {{-- Removed create/filter buttons since banners are now managed per-page directly --}}
@endsection

@section('content')
<style>
    .pf-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .pf-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(212, 166, 88, 0.15);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .pf-card:hover {
        transform: translateY(-5px);
        border-color: rgba(212, 166, 88, 0.4);
        box-shadow: 0 15px 40px rgba(212, 166, 88, 0.15);
    }

    .pf-card__media {
        position: relative;
        height: 180px;
        background: #111;
        overflow: hidden;
        border-bottom: 1px solid rgba(212, 166, 88, 0.1);
    }
    .pf-card__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
        transition: transform 0.3s ease;
    }
    .pf-card:hover .pf-card__img {
        transform: scale(1.05);
    }

    .pf-card__overlay-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 2;
    }

    .pf-badge-active {
        background: rgba(79, 200, 100, 0.15);
        color: #5fcf6e;
        border: 1px solid rgba(79, 200, 100, 0.3);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pf-badge-inactive {
        background: rgba(255, 255, 255, 0.06);
        color: rgba(248, 231, 208, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .pf-card__body {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .pf-card__title {
        font-family: var(--serif);
        font-size: 1.35rem;
        font-weight: 600;
        color: #d4a658;
        margin: 0;
    }

    .pf-card__desc-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 0.88rem;
        color: rgba(248, 231, 208, 0.75);
    }
    .pf-card__desc-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .pf-card__desc-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(212, 166, 88, 0.55);
        font-weight: 700;
    }
    .pf-card__desc-val {
        line-height: 1.4;
        word-break: break-word;
    }

    .pf-card__footer {
        padding: 20px 24px 24px;
        background: rgba(0, 0, 0, 0.15);
        border-top: 1px solid rgba(255,255,255,0.03);
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: flex-end;
    }

    .pf-btn {
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        text-decoration: none !important;
        padding: 10px 18px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    .pf-btn-primary {
        background: linear-gradient(135deg, #d4a658, #9d6f20);
        color: #111 !important;
    }
    .pf-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(212, 166, 88, 0.3);
        color: #000 !important;
    }
    .pf-btn-secondary {
        background: rgba(212, 166, 88, 0.1);
        color: #d4a658 !important;
        border: 1px solid rgba(212, 166, 88, 0.25);
    }
    .pf-btn-secondary:hover {
        background: rgba(212, 166, 88, 0.2);
        border-color: #d4a658;
        color: #f4d7ab !important;
    }
    .pf-btn-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #f07080 !important;
        border: 1px solid rgba(220, 53, 69, 0.25);
    }
    .pf-btn-danger:hover {
        background: rgba(220, 53, 69, 0.2);
        border-color: #dc3545;
        color: #ff8090 !important;
    }
</style>

<div class="pf-grid">
    @foreach (\App\Models\CarouselSlide::pageOptions() as $key => $label)
        @php
            $slide = $slides->get($key)?->first();
            $isCustom = ($slide !== null);

            // Determine preview image and fields
            if ($isCustom) {
                $eyebrow = $slide->eyebrow;
                $previewImage = $slide->getImagePath('400w');
                $title = $slide->title;
                $subtitle = $slide->subtitle;
                $ctaText = $slide->cta_text;
                $ctaUrl = $slide->cta_url;
                $secondaryCtaText = $slide->secondary_cta_text;
                $secondaryCtaUrl = $slide->secondary_cta_url;
            } else {
                // System default mappings
                if ($key === 'home') {
                    $eyebrow = 'POLANI FRAGRANCE';
                    $previewImage = asset('polani/assets/hero-noir-elixir-1024.jpg');
                    $title = 'More Than A Fragrance, It\'s A Statement.';
                    $subtitle = 'Handcrafted extrait de parfum made for timeless elegance.';
                    $ctaText = 'Discover Collection';
                    $ctaUrl = route('polani.collection');
                    $secondaryCtaText = 'Shop Now';
                    $secondaryCtaUrl = route('polani.collection') . '#bestsellers';
                } else {
                    $fallback = \App\Models\CarouselSlide::getFallbackSlides($key)?->first();
                    if ($fallback) {
                        $eyebrow = $fallback->eyebrow ?: '';
                        $previewImage = $fallback->getImagePath('400w');
                        $title = $fallback->title;
                        $subtitle = $fallback->subtitle;
                        $ctaText = $fallback->cta_text;
                        $ctaUrl = $fallback->cta_url;
                        $secondaryCtaText = $fallback->secondary_cta_text;
                        $secondaryCtaUrl = $fallback->secondary_cta_url;
                    } else {
                        if (in_array($key, ['checkout', 'cart', 'track-order', 'order-status', 'faq', 'returns', 'shipping', 'terms', 'privacy'], true)) {
                            $previewImage = asset('polani/assets/cart_banner.jpeg');
                        } elseif ($key === 'about') {
                            $previewImage = asset('polani/assets/story-packaging.svg');
                        } else {
                            $previewImage = asset('polani/assets/home_banner_1.jpeg');
                        }
                        $eyebrow = '';
                        $title = $label;
                        $subtitle = 'Luxury fragrance experience crafted for unforgettable presence.';
                        $ctaText = null;
                        $ctaUrl = null;
                        $secondaryCtaText = null;
                        $secondaryCtaUrl = null;
                    }
                }
            }
        @endphp

        <div class="pf-card">
            {{-- Image Preview --}}
            <div class="pf-card__media">
                <img src="{{ $previewImage }}" alt="{{ $label }}" class="pf-card__img" loading="lazy">
                <div class="pf-card__overlay-badge">
                    @if ($isCustom && $slide->is_active)
                        <span class="pf-badge-active">
                            <i class="fas fa-check-circle"></i> Custom Active
                        </span>
                    @elseif ($isCustom)
                        <span class="pf-badge-inactive">
                            <i class="fas fa-eye-slash"></i> Custom Inactive
                        </span>
                    @else
                        <span class="pf-badge-inactive">
                            <i class="fas fa-cog"></i> System Default
                        </span>
                    @endif
                </div>
            </div>

            {{-- Body Details --}}
            <div class="pf-card__body">
                <h2 class="pf-card__title">{{ $label }}</h2>
                
                <div class="pf-card__desc-list">
                    @if ($eyebrow)
                        <div class="pf-card__desc-item">
                            <span class="pf-card__desc-label">Banner Eyebrow</span>
                            <span class="pf-card__desc-val">{{ $eyebrow }}</span>
                        </div>
                    @endif
                    <div class="pf-card__desc-item">
                        <span class="pf-card__desc-label">Banner Title</span>
                        <span class="pf-card__desc-val">{{ $title ?: '—' }}</span>
                    </div>
                    <div class="pf-card__desc-item">
                        <span class="pf-card__desc-label">Banner Text</span>
                        <span class="pf-card__desc-val" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ $subtitle ?: '—' }}</span>
                    </div>
                    @if (in_array($key, ['home', 'cart', 'checkout', 'contact', 'about', 'order-status', 'track-order'], true) || $ctaText || $secondaryCtaText)
                        <div class="pf-card__desc-item">
                            <span class="pf-card__desc-label">Buttons</span>
                            <span class="pf-card__desc-val d-flex flex-column gap-1">
                                @if ($ctaText)
                                    <div>
                                        <small style="opacity:0.65;text-transform:uppercase;font-size:0.68rem;letter-spacing:0.04em;">Button 1:</small> 
                                        <span style="color:#d4a658;font-weight:600;">{{ $ctaText }}</span> <small style="opacity:0.6;">({{ Str::limit($ctaUrl, 25) }})</small>
                                    </div>
                                @endif
                                @if ($secondaryCtaText)
                                    <div>
                                        <small style="opacity:0.65;text-transform:uppercase;font-size:0.68rem;letter-spacing:0.04em;">Button 2:</small> 
                                        <span style="color:#d4a658;font-weight:600;">{{ $secondaryCtaText }}</span> <small style="opacity:0.6;">({{ Str::limit($secondaryCtaUrl, 25) }})</small>
                                    </div>
                                @endif
                                @if (!$ctaText && !$secondaryCtaText)
                                    <span style="opacity:0.5;font-style:italic;">None</span>
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="pf-card__footer">
                @if ($isCustom)
                    <form method="POST" action="{{ route('admin.banners.destroy', $slide->id) }}" style="margin:0;"
                        onsubmit="return confirm('Revert to default system layout? This will delete your custom banner.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="pf-btn pf-btn-danger" title="Delete & Revert">
                            <i class="fas fa-undo"></i> Revert
                        </button>
                    </form>
                    <a href="{{ route('admin.banners.edit', $slide->id) }}" class="pf-btn pf-btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @else
                    <a href="{{ route('admin.banners.create', ['page_key' => $key]) }}" class="pf-btn pf-btn-secondary">
                        <i class="fas fa-plus"></i> Set Custom Banner
                    </a>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
