<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'eyebrow',
        'title',
        'subtitle',
        'cta_text',
        'cta_url',
        'secondary_cta_text',
        'secondary_cta_url',
        'image_name',
        'order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const PAGE_KEYS = [
        'home',
        'collection',
        'women',
        'attars',
        'oud',
        'scented-candles',
        'product',
        'cart',
        'checkout',
        'contact',
        'track-order',
        'order-status',
        'about',
    ];

    /**
     * Get the user who created this slide.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this slide.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get active carousel slides ordered by order column.
     * Returns fallback data if no active slides found.
     */
    public static function getActiveSlides(?string $pageKey = null)
    {
        $query = self::where('is_active', true)
            ->when($pageKey, fn ($builder) => $builder->where('page_key', $pageKey))
            ->orderBy('order')
            ->get();

        return $slides->isEmpty() ? self::getFallbackSlides($pageKey) : $slides;
    }

    public static function getPrimarySlide(?string $pageKey = null): ?self
    {
        return self::where('is_active', true)
            ->when($pageKey, fn ($builder) => $builder->where('page_key', $pageKey))
            ->orderBy('order')
            ->first();
    }

    public static function pageOptions(): array
    {
        return [
            'home' => 'Home',
            'collection' => 'Collection',
            'women' => 'Women',
            'attars' => 'Attars',
            'oud' => 'Oud',
            'scented-candles' => 'Scented Candles',
            'product' => 'Product Page',
            'cart' => 'Cart',
            'checkout' => 'Checkout',
            'contact' => 'Contact',
            'track-order' => 'Track Order',
            'order-status' => 'Order Status',
            'about' => 'About',
        ];
    }

    /**
     * Get fallback hardcoded slides when database is empty or no active slides.
     * Uses existing hero-dashboard images for compatibility.
     */
    public static function getFallbackSlides(?string $pageKey = null)
    {
        if ($pageKey && in_array($pageKey, ['home', 'collection', 'women', 'attars', 'oud', 'scented-candles', 'contact', 'track-order', 'order-status', 'product', 'cart', 'checkout', 'about'], true)) {
            $fallback = self::fallbackByPage($pageKey);
            if ($fallback) {
                return collect([$fallback]);
            }
        }

        return collect([
            new self([
                'id' => 1,
                'page_key' => 'home',
                'title' => 'Master Islamic Finance',
                'subtitle' => 'Gain deep expertise in Sharia-compliant banking, ethical investing, and the global Islamic economy with our world-class curriculum.',
                'cta_text' => 'Explore FinTech',
                'cta_url' => route('courses'),
                'image_name' => 'hero-dashboard-1',
                'order' => 1,
                'is_active' => true,
            ]),
            new self([
                'id' => 2,
                'title' => 'Advance Your Career',
                'subtitle' => 'Unlock professional opportunities in the rapidly evolving field of Islamic finance with expert-led industry certifications.',
                'cta_text' => 'Discover Courses',
                'cta_url' => route('courses'),
                'image_name' => 'hero-dashboard-2',
                'order' => 2,
                'is_active' => true,
            ]),
            new self([
                'id' => 3,
                'title' => 'Global Certification',
                'subtitle' => 'Earn recognized certificates and join an elite worldwide network of dedicated Islamic finance professionals.',
                'cta_text' => 'Start Learning',
                'cta_url' => route('courses'),
                'image_name' => 'hero-dashboard-3',
                'order' => 3,
                'is_active' => true,
            ]),
            new self([
                'id' => 4,
                'title' => 'Join Our Community',
                'subtitle' => 'Connect with industry mentors and like-minded peers in our vibrant global learning ecosystem.',
                'cta_text' => 'Join Now',
                'cta_url' => route('sign-up'),
                'image_name' => 'hero-dashboard-4',
                'order' => 4,
                'is_active' => true,
            ]),
        ]);
    }

    protected static function fallbackByPage(string $pageKey): ?self
    {
        $map = [
            'home' => [
                'title' => 'More Than A Fragrance, It\'s A Statement.',
                'subtitle' => 'Handcrafted extrait de parfum made for timeless elegance.',
                'cta_text' => 'Discover Collection',
                'cta_url' => route('polani.collection'),
                'image_name' => 'hero-noir-elixir',
            ],
            'collection' => [
                'title' => 'Explore Our Collections',
                'subtitle' => 'Discover all Polani fragrance families in one elegant place.',
                'cta_text' => 'Shop Now',
                'cta_url' => route('polani.collection'),
                'image_name' => 'home_banner_1',
            ],
            'women' => [
                'title' => 'Women',
                'subtitle' => 'Elegant, refined and statement-making fragrances designed for her.',
                'cta_text' => 'Shop Women',
                'cta_url' => route('polani.women'),
                'image_name' => 'cat-women',
            ],
            'attars' => [
                'title' => 'Attars',
                'subtitle' => 'Rich oil-based attars with long-lasting depth and warmth.',
                'cta_text' => 'Shop Attars',
                'cta_url' => route('polani.attars'),
                'image_name' => 'cat-attars',
            ],
            'oud' => [
                'title' => 'Oud',
                'subtitle' => 'Luxurious oud blends with a bold, captivating trail.',
                'cta_text' => 'Shop Oud',
                'cta_url' => route('polani.oud'),
                'image_name' => 'cat-oud',
            ],
            'scented-candles' => [
                'title' => 'Scented Candles',
                'subtitle' => 'Warm, ambient candles crafted for elegant spaces.',
                'cta_text' => 'Shop Candles',
                'cta_url' => route('polani.scented-candles'),
                'image_name' => 'cart_banner',
            ],
            'product' => [
                'title' => 'Signature Product',
                'subtitle' => 'Discover a curated Polani fragrance experience.',
                'cta_text' => 'Shop Collection',
                'cta_url' => route('polani.collection'),
                'image_name' => 'product-noir-elixir',
            ],
            'cart' => [
                'title' => 'Your Cart',
                'subtitle' => 'Review your selected Polani fragrances before checkout.',
                'cta_text' => 'Continue Shopping',
                'cta_url' => route('polani.collection'),
                'image_name' => 'cart_banner',
            ],
            'checkout' => [
                'title' => 'Checkout',
                'subtitle' => 'Complete your purchase securely and smoothly.',
                'cta_text' => 'Review Cart',
                'cta_url' => route('shopping-cart'),
                'image_name' => 'cart_banner',
            ],
            'contact' => [
                'title' => 'We\'re Here To Help You',
                'subtitle' => 'Have a question, suggestion, or need assistance? We would love to hear from you.',
                'cta_text' => 'Get In Touch',
                'cta_url' => route('polani.contact'),
                'image_name' => 'contact-hero',
            ],
            'track-order' => [
                'title' => 'Track Your Order',
                'subtitle' => 'Enter your order number and checkout details to see progress.',
                'cta_text' => 'Track Order',
                'cta_url' => route('polani.track-order'),
                'image_name' => 'cart_banner',
            ],
            'order-status' => [
                'title' => 'Order Confirmed',
                'subtitle' => 'Your Polani order has been placed successfully.',
                'cta_text' => 'Track Order',
                'cta_url' => route('polani.track-order'),
                'image_name' => 'hero-noir-elixir',
            ],
            'about' => [
                'title' => 'About Polani',
                'subtitle' => 'Built around timeless blends, premium ingredients, and modern luxury.',
                'cta_text' => 'Shop Now',
                'cta_url' => route('polani.collection'),
                'image_name' => 'story-packaging',
            ],
        ];

        if (!isset($map[$pageKey])) {
            return null;
        }

        return new self(array_merge([
            'id' => 0,
            'page_key' => $pageKey,
            'order' => 1,
            'is_active' => true,
        ], $map[$pageKey]));
    }

    /**
     * Get image URL for specific size and format.
     *
     * @param string $size Size suffix (e.g., '400w', '800w', '1200w')
     * @param string $format Format (e.g., 'webp', 'png')
     * @return string Image URL
     */
    public function getImagePath($size = '1200w', $format = 'webp')
    {
        // Newly uploaded carousel slides are in carousel/ subdirectory
        // Seeded/fallback slides use hero-dashboard images in main img directory
        if (str_starts_with($this->image_name, 'carousel-slide-')) {
            return asset("assets/img/carousel/{$this->image_name}-{$size}.{$format}");
        }

        return asset("assets/img/{$this->image_name}-{$size}.{$format}");
    }

    /**
     * Get base image path for file operations.
     *
     * @return string Full path to image directory
     */
    public function getBaseImagePath()
    {
        // Newly uploaded carousel slides are in carousel/ subdirectory
        if (str_starts_with($this->image_name, 'carousel-slide-')) {
            return public_path("assets/img/carousel/{$this->image_name}");
        }

        return public_path("assets/img/{$this->image_name}");
    }

    /**
     * Get all image files for this slide.
     *
     * @return array Array of file paths
     */
    public function getImageFiles()
    {
        $basePath = $this->getBaseImagePath();
        $patterns = [
            "{$basePath}*.webp",
            "{$basePath}*.png",
        ];

        $files = [];
        foreach ($patterns as $pattern) {
            $files = array_merge($files, glob($pattern));
        }

        return $files;
    }
};
