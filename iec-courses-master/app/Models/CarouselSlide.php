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
                'title' => 'Baby Care Essentials',
                'subtitle' => 'Soft, safe and everyday essentials for your baby\'s comfort, care and happy little moments.',
                'cta_text' => 'Explore Baby Care',
                'cta_url' => route('polani.babycare'),
                'image_name' => 'baby-care-banner',
            ],
            'collection' => [
                'title' => 'Explore Our Shop',
                'subtitle' => 'Discover all premium baby care products and exciting ride-on toys in one place.',
                'cta_text' => 'Shop Now',
                'cta_url' => route('polani.collection'),
                'image_name' => 'shop_hero',
            ],
            'women' => [
                'title' => 'Baby Care Items',
                'subtitle' => 'Premium quality baby products for gentle care.',
                'cta_text' => 'Shop Baby Care',
                'cta_url' => route('polani.babycare'),
                'image_name' => 'baby-care-banner',
            ],
            'attars' => [
                'title' => 'Battery-Operated Ride-On Bikes',
                'subtitle' => 'Exciting rechargeable bikes for kids adventure and fun.',
                'cta_text' => 'Shop B/O Bikes',
                'cta_url' => route('polani.bikes'),
                'image_name' => 'shop_hero',
            ],
            'oud' => [
                'title' => 'Battery-Operated Ride-On Cars',
                'subtitle' => 'Premium luxury electric cars for double the joy.',
                'cta_text' => 'Shop B/O Cars',
                'cta_url' => route('polani.cars'),
                'image_name' => 'shop_hero',
            ],
            'scented-candles' => [
                'title' => 'Premium Toys',
                'subtitle' => 'Little essentials, big joy.',
                'cta_text' => 'Shop Now',
                'cta_url' => route('polani.collection'),
                'image_name' => 'shop_hero',
            ],
            'product' => [
                'title' => 'Product Detail',
                'subtitle' => 'Premium ride-on toys and baby care essentials.',
                'cta_text' => 'Explore Catalog',
                'cta_url' => route('polani.collection'),
                'image_name' => 'shop_hero',
            ],
            'cart' => [
                'title' => 'Your Cart',
                'subtitle' => 'Review your selected items before placing order.',
                'cta_text' => 'Continue Shopping',
                'cta_url' => route('polani.collection'),
                'image_name' => 'wishlist_hero',
            ],
            'checkout' => [
                'title' => 'Checkout',
                'subtitle' => 'Complete your purchase securely.',
                'cta_text' => 'Review Cart',
                'cta_url' => route('shopping-cart'),
                'image_name' => 'wishlist_hero',
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
                'subtitle' => 'Enter your order number to see progress.',
                'cta_text' => 'Track Order',
                'cta_url' => route('polani.track-order'),
                'image_name' => 'wishlist_hero',
            ],
            'order-status' => [
                'title' => 'Order Confirmed',
                'subtitle' => 'Your Ghousia Traders order has been placed successfully.',
                'cta_text' => 'Track Order',
                'cta_url' => route('polani.track-order'),
                'image_name' => 'wishlist_hero',
            ],
            'about' => [
                'title' => 'About Ghousia Traders',
                'subtitle' => 'Built around quality products, customer trust, and little smiles.',
                'cta_text' => 'Shop Now',
                'cta_url' => route('polani.collection'),
                'image_name' => 'shop_hero',
            ],
        ];

        if (!isset($map[$pageKey])) {
            return null;
        }

        return new self(array_merge([
            'id' => 0,
            'page_key' => $pageKey,
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

        // Custom theme fallback images for Ghousia Traders
        $themeFallbacks = [
            'baby-care-banner' => 'ghousiatraders/assets/baby-care-banner.jpg',
            'shop_hero'        => 'ghousiatraders/assets/shop_hero.png',
            'wishlist_hero'    => 'ghousiatraders/assets/wishlist_hero.png',
            'contact-hero'     => 'ghousiatraders/assets/contact-hero.png',
        ];

        if (isset($themeFallbacks[$this->image_name])) {
            return asset($themeFallbacks[$this->image_name]);
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
