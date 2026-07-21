@props([
    'imageName' => 'hero-dashboard-1',
    'alt' => 'Hero image',
    'lazy' => false
])

@php
    // Determine correct path based on image type
    // Newly uploaded carousel slides use carousel/ subdirectory
    $basePath = str_starts_with($imageName, 'carousel-slide-')
        ? 'assets/img/carousel/'
        : 'assets/img/';
@endphp

<picture class="hero-picture">
    <source
        srcset="
            {{ asset($basePath . $imageName . '-400w.webp') }} 400w,
            {{ asset($basePath . $imageName . '-800w.webp') }} 800w,
            {{ asset($basePath . $imageName . '-1200w.webp') }} 1200w"
        sizes="(max-width: 600px) 400px, (max-width: 1024px) 800px, 1200px"
        type="image/webp">
    <img
        src="{{ asset($basePath . $imageName . '-1200w.webp') }}"
        alt="{{ $alt }}"
        @if($lazy)
            loading="lazy"
            decoding="async"
        @else
            fetchpriority="high"
            decoding="sync"
        @endif
        class="hero-slide-img"
        width="1200"
        height="900">
</picture>
