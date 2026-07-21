@props([
    'pageKey' => 'home',
    'eyebrow' => 'POLANI FRAGRANCE',
    'title' => '',
    'subtitle' => '',
    'ctaText' => null,
    'ctaUrl' => null,
    'secondaryCtaText' => null,
    'secondaryCtaUrl' => null,
    'fallbackImage' => null,
    'imagePosition' => 'center center',
    'alignment' => 'center',
    'textColor' => 'light',
])

@php
    $slide = \App\Models\CarouselSlide::getPrimarySlide($pageKey);
    $bannerEyebrow = $slide?->eyebrow ?: $eyebrow;
    $bannerTitle = $slide?->title ?: $title;
    $bannerSubtitle = $slide?->subtitle ?: $subtitle;
    $bannerCtaText = $slide?->cta_text ?: $ctaText;
    $bannerCtaUrl = $slide?->cta_url ?: $ctaUrl;
    $bannerSecondaryCtaText = $slide?->secondary_cta_text ?: $secondaryCtaText;
    $bannerSecondaryCtaUrl = $slide?->secondary_cta_url ?: $secondaryCtaUrl;

    $bannerImage = null;
    if ($slide) {
        $bannerImage = $slide->getImagePath('1200w');
    } elseif ($fallbackImage) {
        $bannerImage = preg_match('/^https?:\/\//', $fallbackImage)
            ? $fallbackImage
            : asset($fallbackImage);
    }
@endphp

<section class="polani-banner polani-banner--{{ $alignment }} polani-banner--{{ $textColor }}">
    @if($bannerImage)
        <img
            class="polani-banner__img"
            src="{{ $bannerImage }}"
            alt=""
            aria-hidden="true"
            style="object-position: {{ $imagePosition }};"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        />
    @endif
    <div class="polani-banner__overlay" aria-hidden="true"></div>
    <div class="container polani-banner__inner">
        <div class="polani-banner__content">
            <div class="polani-banner__eyebrow">{{ $bannerEyebrow }}</div>
            <h1 class="polani-banner__title">{!! nl2br(e(html_entity_decode($bannerTitle ?: $title, ENT_QUOTES, 'UTF-8'))) !!}</h1>
            @if($bannerSubtitle)
                <p class="polani-banner__text">{{ $bannerSubtitle }}</p>
            @endif
            @if($bannerCtaText && $bannerCtaUrl)
                <div class="polani-banner__actions">
                    <a class="btn btn--gold" href="{{ $bannerCtaUrl }}">{{ $bannerCtaText }}</a>
                    @if($bannerSecondaryCtaText && $bannerSecondaryCtaUrl)
                        <a class="btn btn--outline-gold" href="{{ $bannerSecondaryCtaUrl }}">{{ $bannerSecondaryCtaText }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
