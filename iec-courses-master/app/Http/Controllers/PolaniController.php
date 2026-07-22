<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Blog;
use App\Models\Order;
use App\Models\Shoppingcart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PolaniController extends Controller
{
    private array $notesMap = [
        'exclusive-reserve' => [
            'top' => ['Grapefruit', 'Aromatic Notes'],
            'heart' => ['Refined Spices', 'Soft Florals'],
            'base' => ['Warm Woods', 'Amber'],
        ],
        'oud-candle' => [
            'top' => ['Oud', 'Warm Spice'],
            'heart' => ['Smoke', 'Resin'],
            'base' => ['Amber', 'Woods'],
        ],
        'noir-candle' => [
            'top' => ['Rose'],
            'heart' => ['Jasmine'],
            'base' => ['Amber'],
        ],
        'qasr-al-oud' => [
            'top' => ['Saffron', 'Bergamot'],
            'heart' => ['Oud', 'Rose'],
            'base' => ['Amber', 'Musk', 'Patchouli'],
        ],
        'amber-woods-attar' => [
            'top' => ['Amber'],
            'heart' => ['Woods'],
            'base' => ['Musk', 'Resin'],
        ],
        'musk-silk-attar' => [
            'top' => ['Musk'],
            'heart' => ['Soft Powder'],
            'base' => ['Creamy Woods'],
        ],
        'citrus-grove' => [
            'top' => ['Bergamot', 'Orange'],
            'heart' => ['Green Notes'],
            'base' => ['Clean Woods'],
        ],
        'velvet-rose' => [
            'top' => ['Rose'],
            'heart' => ['Floral Accord'],
            'base' => ['Musk', 'Vanilla'],
        ],
    ];

    private array $ratingMap = [
        'exclusive-reserve' => ['rating' => 4.9, 'reviews' => 124],
        'oud-candle' => ['rating' => 4.6, 'reviews' => 54],
        'noir-candle' => ['rating' => 4.7, 'reviews' => 41],
        'qasr-al-oud' => ['rating' => 4.9, 'reviews' => 124],
        'amber-woods-attar' => ['rating' => 4.8, 'reviews' => 66],
        'musk-silk-attar' => ['rating' => 4.8, 'reviews' => 58],
        'citrus-grove' => ['rating' => 4.7, 'reviews' => 63],
        'velvet-rose' => ['rating' => 4.8, 'reviews' => 76],
        'noir-elixir' => ['rating' => 4.7, 'reviews' => 98],
        'oud-royale' => ['rating' => 4.9, 'reviews' => 112],
        'bleu-mist' => ['rating' => 4.6, 'reviews' => 47],
        'amber-muse' => ['rating' => 4.7, 'reviews' => 62],
    ];

    private function productType(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => 'Scented Candle',
            'Attars' => 'Attar',
            default => 'Extrait de Parfum',
        };
    }

    private function productViewModel(Course $course): array
    {
        $slug = $course->slug;
        $category = $course->category?->name;
        $imagePath = $course->image_path ?: 'polani/assets/product-noir.svg';
        $categoryFilter = match ($category) {
            'Scented Candles' => 'candles',
            'Attars' => 'attars',
            'Oud' => 'oud',
            'Women' => 'women',
            default => 'men',
        };
        $rating = $course->average_rating;
        $reviews = $course->rating_count;
        $notes = $this->notesMap[$slug] ?? [
            'top' => ['Citrus'],
            'heart' => ['Amber'],
            'base' => ['Musk', 'Woods'],
        ];

        return [
            'db_id' => $course->id,
            'id' => $slug,
            'slug' => $slug,
            'name' => $course->name,
            'type' => $this->productType($category),
            'price' => (float) ($course->weekly_price ?? 0),
            'rating' => $rating,
            'reviews' => $reviews,
            'category_slug' => $categoryFilter,
            'category_name' => $category,
            'family' => $this->familyForCategory($category),
            'occasion' => $this->occasionForCategory($category),
            'image' => asset($imagePath),
            'notes' => $notes,
            'longevity' => $this->longevityForCategory($category),
            'projection' => $this->projectionForCategory($category),
            'season' => $this->seasonForCategory($category),
            'description' => $course->description,
            'long_description' => $course->long_description,
            'intro_video_url' => $course->intro_video_url,
        ];
    }

    private function familyForCategory(?string $category): array
    {
        return match ($category) {
            'Attars' => ['amber', 'musky'],
            'Scented Candles' => ['amber', 'floral'],
            'Oud' => ['woody', 'amber'],
            'Women' => ['floral', 'amber'],
            default => ['woody', 'spicy'],
        };
    }

    private function occasionForCategory(?string $category): array
    {
        return match ($category) {
            'Scented Candles' => ['daily', 'luxury'],
            'Attars' => ['daily', 'evening'],
            'Women' => ['daily', 'evening'],
            'Oud' => ['evening', 'luxury'],
            default => ['office', 'evening'],
        };
    }

    private function longevityForCategory(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => '40 hours',
            'Attars' => '10–12 hours',
            'Oud' => '10–12 hours',
            default => '8–10 hours',
        };
    }

    private function projectionForCategory(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => 'Room-filling',
            'Oud' => 'Strong',
            'Attars' => 'Moderate',
            default => 'Moderate',
        };
    }

    private function seasonForCategory(?string $category): string
    {
        return match ($category) {
            'Scented Candles' => 'All seasons',
            'Attars' => 'All seasons',
            'Women' => 'All seasons',
            default => 'Fall / Winter',
        };
    }

    private function sessionCart(): array
    {
        return session()->get('polani_cart', []);
    }

    private function saveSessionCart(array $items): void
    {
        session()->put('polani_cart', array_values($items));
    }

    private function cartCount(): int
    {
        if (auth()->check()) {
            return Shoppingcart::where('user_id', auth()->id())->count();
        }

        return count($this->sessionCart());
    }

    private function categoryPage(string $categoryName, string $title, string $description, string $heroImage, string $heroPosition = 'center', string $pageKey = 'collection')
    {
        $products = Course::query()
            ->with('category')
            ->whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->latest('id')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course))
            ->values();

        return view('ghousiatraders.category', [
            'pageTitle' => $title,
            'pageDescription' => $description,
            'heroImage' => $heroImage,
            'heroPosition' => $heroPosition,
            'collectionLabel' => $categoryName,
            'pageKey' => $pageKey,
            'products' => $products,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function home()
    {
        $products = Course::query()
            ->with('category')
            ->whereHas('category', function ($query) {
                $query->whereIn('slug', ['baby-care', 'bo-bikes', 'bo-cars']);
            })
            ->latest('id')
            ->take(8)
            ->get();

        $babyCareProducts = Course::query()
            ->with('category')
            ->whereHas('category', function ($query) {
                $query->where('slug', 'baby-care');
            })
            ->latest('id')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course));

        $bikesProducts = Course::query()
            ->with('category')
            ->whereHas('category', function ($query) {
                $query->where('slug', 'bo-bikes');
            })
            ->latest('id')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course));

        $carsProducts = Course::query()
            ->with('category')
            ->whereHas('category', function ($query) {
                $query->where('slug', 'bo-cars');
            })
            ->latest('id')
            ->get()
            ->map(fn (Course $course) => $this->productViewModel($course));

        $blogs = Blog::latest('id')->take(3)->get();

        $homepageSections = \App\Models\HomepageSection::query()
            ->with(['products' => function($query) {
                $query->with('category')->orderByDesc('homepage_section_product.created_at');
            }])
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($section) {
                return [
                    'title' => $section->title,
                    'slug' => $section->slug,
                    'bg_theme' => $section->bg_theme,
                    'products' => $section->products->map(fn (Course $course) => $this->productViewModel($course))
                ];
            });

        return view('ghousiatraders.home', [
            'products' => $products->map(fn (Course $course) => $this->productViewModel($course)),
            'babyCareProducts' => $babyCareProducts,
            'bikesProducts' => $bikesProducts,
            'carsProducts' => $carsProducts,
            'blogs' => $blogs,
            'cartCount' => $this->cartCount(),
            'homepageSections' => $homepageSections,
        ]);
    }

    public function collection()
    {
        $query = trim((string) request('q', ''));

        $products = Course::query()
            ->with('category')
            ->whereHas('category', function ($q) {
                $q->whereIn('slug', ['baby-care', 'bo-bikes', 'bo-cars']);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', '%' . $query . '%')
                        ->orWhere('slug', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                });
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $products->getCollection()->transform(fn (Course $course) => $this->productViewModel($course));

        return view('ghousiatraders.shop', [
            'products' => $products,
            'cartCount' => $this->cartCount(),
            'searchQuery' => $query,
        ]);
    }

    public function showCustomPage(string $slug)
    {
        $page = \App\Models\NavigationPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $page->products()
            ->latest('id')
            ->get()
            ->map(fn (\App\Models\Course $course) => $this->productViewModel($course))
            ->values();

        return view('polani.page', [
            'page' => $page,
            'products' => $products,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function women()
    {
        return $this->categoryPage(
            'Women',
            'Women — Polani Fragrance',
            'Elegant, refined and statement-making fragrances designed for her.',
            'polani/assets/cat-women.jpeg',
            'center',
            'women'
        );
    }

    public function attars()
    {
        return $this->categoryPage(
            'Attars',
            'Attars — Polani Fragrance',
            'Rich oil-based attars with long-lasting depth and warmth.',
            'polani/assets/cat-attars.jpeg',
            'center',
            'attars'
        );
    }

    public function oud()
    {
        return $this->categoryPage(
            'Oud',
            'Oud — Polani Fragrance',
            'Luxurious oud blends with a bold, captivating trail.',
            'polani/assets/cat-oud.jpeg',
            'center',
            'oud'
        );
    }

    public function scentedCandles()
    {
        return $this->categoryPage(
            'Scented Candles',
            'Scented Candles — Polani Fragrance',
            'Warm, ambient candles crafted for elegant spaces.',
            'polani/assets/cat-candles.svg',
            'center',
            'scented-candles'
        );
    }

    public function babycare()
    {
        return $this->categoryPage(
            'Baby Care',
            'Baby Care Items — Ghousia Traders',
            'Deeply hydrating baby lotions, pure water wipes, soft spout sippy cups and complete feeding sets designed for gentle daily care.',
            'ghousiatraders/assets/baby-care-banner.jpg',
            'center',
            'baby-care'
        );
    }

    public function bikes()
    {
        return $this->categoryPage(
            'B/O Bikes',
            'B/O Bikes — Ghousia Traders',
            'Exciting battery-operated sports superbikes, touring trail motorbikes and retro Vespa ride-on scooters for child adventure and fun.',
            'ghousiatraders/assets/shop_hero.png',
            'center',
            'bo-bikes'
        );
    }

    public function cars()
    {
        return $this->categoryPage(
            'B/O Cars',
            'B/O Cars — Ghousia Traders',
            'Premium battery-operated AMG Mercedes ride-on cars, heavy duty 4WD Jeep Wranglers, Land Cruisers and luxury Range Rover electric cars.',
            'ghousiatraders/assets/shop_hero.png',
            'center',
            'bo-cars'
        );
    }

    public function wishlist()
    {
        return view('ghousiatraders.wishlist', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $error = null;
        $orderNumberInput = trim((string) $request->query('order_number', ''));
        $emailInput = trim((string) $request->query('email', ''));
        $phoneInput = trim((string) $request->query('phone', ''));
        $userOrders = collect();
        $selectedOrderId = null;

        if (auth()->check()) {
            $userOrders = Order::where('user_id', auth()->id())
                ->latest()
                ->get()
                ->map(function (Order $userOrder) {
                    return $this->buildTrackOrderContext($userOrder);
                })
                ->values();

            if ($orderNumberInput !== '') {
                if (str_contains($orderNumberInput, '-')) {
                    $parts = explode('-', $orderNumberInput);
                    $selectedOrderId = (int) end($parts);
                } else {
                    $selectedOrderId = (int) preg_replace('/\D+/', '', $orderNumberInput);
                }
                $order = $selectedOrderId > 0
                    ? Order::where('user_id', auth()->id())->find($selectedOrderId)
                    : null;

                if (!$order && $userOrders->isNotEmpty()) {
                    $order = Order::where('user_id', auth()->id())->latest()->first();
                }
            } elseif ($userOrders->isNotEmpty()) {
                $order = Order::where('user_id', auth()->id())->latest()->first();
            }
        } elseif ($orderNumberInput !== '') {
            if (str_contains($orderNumberInput, '-')) {
                $parts = explode('-', $orderNumberInput);
                $orderId = (int) end($parts);
            } else {
                $orderId = (int) preg_replace('/\D+/', '', $orderNumberInput);
            }

            if ($orderId > 0) {
                $order = Order::find($orderId);

                if ($order) {
                    $billingAddress = json_decode($order->billing_address ?? '{}', true);
                    if (!is_array($billingAddress)) {
                        $billingAddress = [];
                    }

                    $emailMatches = $emailInput !== '' && strtolower((string) ($billingAddress['email'] ?? '')) === strtolower($emailInput);
                    $phoneMatches = $phoneInput !== '' && preg_replace('/\D+/', '', (string) ($billingAddress['phone'] ?? '')) === preg_replace('/\D+/', '', $phoneInput);
                    $ownsOrder = auth()->check() && (int) $order->user_id === (int) auth()->id();

                    if (!($ownsOrder || $emailMatches || $phoneMatches)) {
                        $order = null;
                        $error = 'Please enter the same email or phone number used at checkout.';
                    }
                }
            }
        }

        if ($orderNumberInput !== '' && !$order) {
            $error = $error ?: 'No order was found for the details you entered.';
        }

        return view('polani.track-order', [
            'cartCount' => $this->cartCount(),
            'order' => $order ? $this->buildTrackOrderContext($order) : null,
            'orders' => $userOrders,
            'error' => $error,
            'isAuthenticated' => auth()->check(),
            'query' => [
                'order_number' => $orderNumberInput,
                'email' => $emailInput,
                'phone' => $phoneInput,
            ],
        ]);
    }

    public function product(string $slug)
    {
        $product = Course::with('category')->where('slug', $slug)->firstOrFail();

        return view('polani.product', [
            'product' => $this->productViewModel($product),
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function blogs()
    {
        $blogs = Blog::latest('id')->paginate(12);

        return view('polani.blogs', [
            'blogs' => $blogs,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function blogDetail(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('polani.blog-detail', [
            'blog' => $blog,
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function about()
    {
        return view('polani.about', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function contact()
    {
        return view('polani.contact', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function shippingDelivery()
    {
        return view('polani.shipping', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function returnsRefunds()
    {
        return view('polani.returns', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function faq()
    {
        $publishedFaqs = \App\Models\Faq::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('polani.faq', [
            'cartCount' => $this->cartCount(),
            'publishedFaqs' => $publishedFaqs,
        ]);
    }

    public function storeFaqQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        \App\Models\Faq::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => auth()->check() ? auth()->user()->name : ($request->input('name') ?: 'Guest'),
            'email' => auth()->check() ? auth()->user()->email : $request->input('email'),
            'question' => $request->input('question'),
        ]);

        return redirect()->back()->with('success', 'Your question has been submitted successfully! Our team will answer it shortly.');
    }


    public function termsConditions()
    {
        return view('polani.terms', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function privacyPolicy()
    {
        return view('polani.privacy', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function cart()
    {
        return view('polani.cart', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    public function checkout()
    {
        return view('polani.checkout', [
            'cartCount' => $this->cartCount(),
        ]);
    }

    private function buildTrackOrderContext(Order $order): array
    {
        $status = $order->status ?: 'pending';
        $statusMap = [
            'pending' => ['Placed', 1, 'Your order has been received and is awaiting confirmation.'],
            'paid' => ['Confirmed', 2, 'Your order is confirmed and is being prepared.'],
            'shipped' => ['Shipped', 3, 'Your order has been shipped and is on the way.'],
            'completed' => ['Delivered', 4, 'Your order has been completed successfully.'],
            'failed' => ['Cancelled', 0, 'Your order was cancelled.'],
            'rejected' => ['Cancelled', 0, 'Your order was rejected.'],
        ];

        [$label, $step, $message] = $statusMap[$status] ?? ['Processing', 2, 'Your order is being processed.'];

        $items = collect(json_decode($order->cart_items, true) ?: [])
            ->map(function (array $item) {
                $course = isset($item['course_id']) ? Course::find($item['course_id']) : null;
                if (!$course) {
                    return null;
                }

                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? $course->weekly_price ?? 0);

                return [
                    'name' => $course->name,
                    'slug' => $course->slug,
                    'image' => $course->image_path ? asset($course->image_path) : asset('polani/assets/product-noir-elixir.jpg'),
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $price * $quantity,
                ];
            })
            ->filter()
            ->values();

        $billing = json_decode($order->billing_address ?? '{}', true);
        if (!is_array($billing)) {
            $billing = [];
        }

        return [
            'orderNumber' => sprintf('#PF-%s-%04d', now()->format('Y'), $order->id),
            'orderId' => $order->id,
            'status' => $status,
            'statusLabel' => $label,
            'statusStep' => $step,
            'statusStage' => $step > 0 ? "Step {$step} of 4" : 'Step 0 of 4',
            'statusMessage' => $message,
            'orderDate' => optional($order->created_at)->format('F j, Y') ?? now()->format('F j, Y'),
            'deliveryWindow' => $status === 'completed' ? 'Delivered' : '2 - 4 working days',
            'estimatedDelivery' => $status === 'completed' ? 'Delivered' : '2 - 4 working days',
            'total' => (float) ($order->final_total ?? $order->total ?? 0),
            'paymentMethod' => $this->formatPaymentLabel($order->payment_method),
            'address' => trim(implode(', ', array_filter([
                $billing['address'] ?? null,
                $billing['city'] ?? null,
                $billing['country'] ?? null,
            ]))),
            'email' => $billing['email'] ?? null,
            'phone' => $billing['phone'] ?? null,
            'items' => $items,
        ];
    }

    private function formatPaymentLabel(?string $paymentMethod): string
    {
        $method = trim((string) $paymentMethod);

        if ($method === '') {
            return 'Polani Payment';
        }

        $normalized = strtolower($method);

        return match (true) {
            str_contains($normalized, 'cash') => 'Cash on Delivery',
            str_contains($normalized, 'cod') => 'Cash on Delivery',
            str_contains($normalized, 'easypaisa') => 'Easypaisa',
            str_contains($normalized, 'jazzcash') => 'JazzCash',
            str_contains($normalized, 'bank') => 'Bank Transfer',
            str_contains($normalized, 'card') => 'Card Payment',
            str_contains($normalized, 'paypal') => 'PayPal',
            default => ucwords($method),
        };
    }

    public function addToCart(Request $request, string $slug)
    {
        $product = Course::where('slug', $slug)->firstOrFail();

        if ($product->is_free) {
            return back()->with('error', 'This product is free and cannot be added to cart.');
        }

        $price = (float) ($product->weekly_price ?? 0);

        if (auth()->check()) {
            Shoppingcart::updateOrCreate(
                ['user_id' => auth()->id(), 'course_id' => $product->id],
                ['price' => $price, 'price_type' => null]
            );
        } else {
            $cart = $this->sessionCart();
            $existingIndex = collect($cart)->search(fn ($item) => (int) ($item['course_id'] ?? 0) === (int) $product->id);

            $payload = [
                'id' => $product->id,
                'course_id' => $product->id,
                'lecture_id' => null,
                'name' => $product->name,
                'type' => 'Product',
                'price' => $price,
                'quantity' => 1,
                'image_path' => $product->image_path,
            ];

            if ($existingIndex !== false) {
                $cart[$existingIndex] = $payload;
            } else {
                $cart[] = $payload;
            }

            $this->saveSessionCart($cart);
        }

        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Added to cart.',
                'cartCount' => $this->cartCount(),
            ]);
        }

        return back()->with('success', 'Added to cart.');
    }
}
