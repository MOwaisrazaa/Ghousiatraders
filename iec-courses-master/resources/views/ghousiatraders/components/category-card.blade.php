@props(['name', 'slug', 'image', 'icon', 'route'])

<div class="category-card" id="{{ $slug }}">
    <div class="cat-image-side">
        <a href="{{ $route }}">
            <img src="{{ asset($image) }}" alt="{{ $name }}">
        </a>
    </div>
    <div class="cat-info-side">
        <div class="cat-icon-box">
            <i data-lucide="{{ $icon }}"></i>
        </div>
        <h3 class="cat-title">
            <a href="{{ $route }}" style="color: inherit; text-decoration: none;">
                {{ $name }}
            </a>
        </h3>
        <a href="{{ $route }}" class="btn btn-primary btn-sm cat-btn">
            Shop Now <i data-lucide="arrow-right"></i>
        </a>
    </div>
</div>
