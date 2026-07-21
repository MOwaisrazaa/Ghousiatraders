@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'class' => '',
    'loading' => 'lazy',
    'sizes' => '(max-width: 576px) 100vw, (max-width: 768px) 50vw, 300px'
])

@php
    // Check if image is from storage or asset
    $isStorageUrl = str_contains($src, '/storage/');
    
    // Get the base path and extension
    $pathInfo = pathinfo(parse_url($src, PHP_URL_PATH));
    $extension = strtolower($pathInfo['extension'] ?? 'jpg');
    $basePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'];
    
    // Check if WebP version exists (for storage images)
    $webpSrc = null;
    if ($isStorageUrl && $extension !== 'webp') {
        $webpPath = str_replace('/storage/', '', $basePath) . '.webp';
        if (Storage::disk('public')->exists($webpPath)) {
            $webpSrc = Storage::url($webpPath);
        }
    } elseif ($extension === 'webp') {
        $webpSrc = $src;
    }
    
    // Build srcset for responsive images if sizes exist
    $srcsetWebp = [];
    $srcsetOriginal = [];
    $responsiveSizes = [150, 300, 600, 900];
    
    foreach ($responsiveSizes as $size) {
        // Check for sized versions
        $sizedWebpPath = str_replace('/storage/', '', $basePath) . '-' . $size . '.webp';
        if (Storage::disk('public')->exists($sizedWebpPath)) {
            $srcsetWebp[] = Storage::url($sizedWebpPath) . ' ' . $size . 'w';
        }
    }
    
    $srcsetWebpStr = implode(', ', $srcsetWebp);
@endphp

<picture>
    {{-- WebP source if available --}}
    @if($webpSrc || !empty($srcsetWebpStr))
        <source 
            type="image/webp"
            @if(!empty($srcsetWebpStr))
                srcset="{{ $srcsetWebpStr }}"
                sizes="{{ $sizes }}"
            @else
                srcset="{{ $webpSrc }}"
            @endif
        >
    @endif
    
    {{-- Fallback to original format --}}
    <img 
        src="{{ $src }}"
        alt="{{ $alt }}"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        class="{{ $class }}"
        loading="{{ $loading }}"
        {{ $attributes }}
    >
</picture>

