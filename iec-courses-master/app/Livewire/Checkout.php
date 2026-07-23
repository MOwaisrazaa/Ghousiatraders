<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use App\Models\Coupon;
use App\Models\UserCourse;
use App\Models\PaymentMethod;
use App\Models\Course;
use App\Models\Lecture;

class Checkout extends Component
{
    public $cartItems = [];
    public $total;
    public $paymentMethod = null; // Start with no default payment method
    public $confirmPayment = false; // Confirmation property
    
    // Unified and extra address fields
    public $fullName = '';
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $address;
    public $address2 = '';
    public $city = '';
    public $area = '';
    public $state = '';
    public $country = 'PK';
    public $postalCode;
    public $orderNotes = '';
    
    // Delivery Method selection
    public $deliveryMethod = 'standard';
    public $shippingCost = 0;
    public $useBilling = true;

    // Card details (client-bound only)
    public $cardName = '';
    public $cardNumber;
    public $expiryDate;
    public $cvv;
    
    public $isProcessing = false;
    public $discount = 0;
    public $couponCode = '';
    public $couponError = '';
    public $couponSuccess = '';
    public $appliedCoupon = null;
    public $paymentMethods = []; // Store all available payment methods

    protected $listeners = [
        'paymentMethodUpdated' => 'updatePaymentMethod',
        'address-selected' => 'handleAddressSelected'
    ];

    public function mount()
    {
        if (!auth()->check()) {
            session()->put('url.intended', route('checkout'));
            return redirect()->route('sign-in')
                ->with('error', 'Please sign in to place your order, whether you pay by COD or online.');
        }

        $user = auth()->user();
        $this->firstName = $user->first_name ?? '';
        $this->lastName = $user->last_name ?? '';
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        
        if (empty($this->firstName) && empty($this->lastName) && !empty($user->name)) {
            $parts = explode(' ', trim($user->name), 2);
            $this->firstName = $parts[0] ?? '';
            $this->lastName = $parts[1] ?? '';
        }
        $this->fullName = trim($this->firstName . ' ' . $this->lastName);
        $this->country = $user->country ?? 'PK';
        $this->city = $user->city ?? '';
        $this->state = $user->state ?? '';
        $this->address = $user->address ?? '';
        $this->postalCode = $user->postal_code ?? '';

        // Get cart items and total from request or database shopping cart
        $cartSource = [];
        if (auth()->check()) {
            $dbCart = \App\Models\Shoppingcart::where('user_id', auth()->id())->get();
            foreach ($dbCart as $dbItem) {
                $cartSource[] = [
                    'course_id' => $dbItem->course_id,
                    'price' => (float)$dbItem->price,
                    'quantity' => (int)$dbItem->quantity,
                ];
            }
        }
        
        if (empty($cartSource)) {
            $cartSource = request('cartItems', session('polani_cart', []));
        }

        $this->cartItems = collect($cartSource)->map(function ($item) {
            if (isset($item['course']) && $item['course'] !== null) {
                return [
                    'name' => $item['course']['name'] ?? 'Unknown Course',
                    'type' => 'Course',
                    'price' => (float)($item['price']),
                    'quantity' => $item['quantity'] ?? 1,
                    'course_id' => $item['course']['id'] ?? null,
                    'image_path' => $item['course']['image_path'] ?? null,
                ];
            } elseif (isset($item['lecture']) && $item['lecture'] !== null) {
                return [
                    'name' => $item['lecture']['name'] ?? 'Unknown Lecture',
                    'type' => 'Lecture',
                    'price' => (float)($item['price']),
                    'quantity' => $item['quantity'] ?? 1,
                    'lecture_id' => $item['lecture']['id'] ?? null,
                    'image_path' => $item['lecture']['image_path'] ?? null,
                ];
            } elseif (isset($item['course_id'])) {
                $course = Course::find($item['course_id']);
                if ($course) {
                    return [
                        'name' => $course->name,
                        'type' => 'Product',
                        'price' => (float) ($item['price'] ?? $course->weekly_price),
                        'quantity' => $item['quantity'] ?? 1,
                        'course_id' => $course->id,
                        'image_path' => $course->image_path,
                    ];
                }
            } elseif (isset($item['lecture_id'])) {
                $lecture = Lecture::find($item['lecture_id']);
                if ($lecture) {
                    return [
                        'name' => $lecture->name,
                        'type' => 'Lecture',
                        'price' => (float) ($item['price'] ?? $lecture->weekly_price),
                        'quantity' => $item['quantity'] ?? 1,
                        'lecture_id' => $lecture->id,
                        'image_path' => $lecture->image_path,
                    ];
                }
            }
            return null;
        })->filter()->values();

        // Calculate subtotal
        $subtotal = 0;
        foreach ($this->cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $this->total = (float) request('total', $subtotal);

        // If no items or total is 0, redirect back to cart
        if ($this->cartItems->isEmpty() || $this->total <= 0) {
            session()->flash('error', 'Your cart is empty or invalid.');
            return redirect()->route('shopping-cart');
        }

        // Load only active payment methods from the database
        $this->paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Set default payment method (first active method)
        if ($this->paymentMethods->isNotEmpty()) {
            $this->paymentMethod = $this->paymentMethods->first()->key;
        }

        // Initialize shipping cost
        $this->updatedDeliveryMethod($this->deliveryMethod);
    }

    public function updatedDeliveryMethod($value)
    {
        if ($value === 'express') {
            $this->shippingCost = 250;
        } else {
            $this->shippingCost = 0;
        }
    }

    public function createOrder()
    {
        $this->isProcessing = true;

        if (!auth()->check()) {
            $this->isProcessing = false;
            session()->put('url.intended', route('checkout'));

            return redirect()->route('sign-in')
                ->with('error', 'Please sign in to place your order, whether you pay by COD or online.');
        }

        if ($this->cartItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            $this->isProcessing = false;
            return;
        }

        // Split Full Name into First and Last names dynamically
        $parts = explode(' ', trim($this->fullName), 2);
        $this->firstName = $parts[0] ?? 'User';
        $this->lastName = $parts[1] ?? 'Customer';
        
        // Auto-assign state
        $this->state = $this->city;

        // Validate that no free items are in the cart
        foreach ($this->cartItems as $item) {
            if (isset($item['course_id'])) {
                $course = \App\Models\Course::find($item['course_id']);
                if ($course && $course->is_free) {
                    $this->isProcessing = false;
                    session()->flash('error', 'Free courses cannot be purchased. Please remove "' . $course->name . '" from your cart and use the free enrollment option instead.');
                    return redirect()->route('shopping-cart');
                }
            } elseif (isset($item['lecture_id'])) {
                $lecture = \App\Models\Lecture::find($item['lecture_id']);
                if ($lecture && $lecture->is_free) {
                    $this->isProcessing = false;
                    session()->flash('error', 'Free lectures cannot be purchased. Please remove "' . $lecture->name . '" from your cart and use the free enrollment option instead.');
                    return redirect()->route('shopping-cart');
                }
            }
        }

        // Validate that restricted course lectures aren't being purchased individually
        foreach ($this->cartItems as $item) {
            if (isset($item['lecture_id'])) {
                $lecture = \App\Models\Lecture::find($item['lecture_id']);
                
                if ($lecture && $lecture->belongsToRestrictedCourse()) {
                    $this->isProcessing = false;
                    session()->flash('error', 
                        'Lecture "' . $lecture->name . '" can only be purchased as part of the complete course. Please remove it from your cart.');
                    return redirect()->route('shopping-cart');
                }
            }
        }

        // Calculate final total after discount and shipping
        $finalTotal = max(0, $this->total - $this->discount + $this->shippingCost);

        // If total is 0 after discount/shipping, set payment method to free
        if ($finalTotal <= 0) {
            $this->paymentMethod = 'free';
        }

        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'country' => 'required',
            'city' => 'required',
            'area' => 'required|string|max:255',
            'postalCode' => 'required|string|max:20',
            'paymentMethod' => 'required',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'cart_items' => $this->cartItems->toJson(),
            'total' => $this->total,
            'discount' => $this->discount,
            'final_total' => $finalTotal,
            'status' => ($finalTotal <= 0 || $this->paymentMethod === 'free') ? 'completed' : 'pending',
            'payment_method' => $this->paymentMethod,
            'billing_address' => json_encode([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'address2' => $this->address2,
                'country' => $this->country,
                'state' => $this->state,
                'city' => $this->city,
                'area' => $this->area,
                'postal_code' => $this->postalCode,
                'notes' => $this->orderNotes,
                'delivery_method' => $this->deliveryMethod,
                'shipping_cost' => $this->shippingCost,
            ]),
            'coupon_code' => $this->appliedCoupon ? $this->appliedCoupon->code : null,
        ]);

        if ($this->appliedCoupon) {
            $this->appliedCoupon->incrementUses();
        }

        // Clear the cart after successful order
        if (auth()->check()) {
            \App\Models\Shoppingcart::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('polani_cart');
        }

        // For free orders (zero amount after coupon), redirect to success page directly
        if ($finalTotal <= 0 || $this->paymentMethod === 'free') {
            $this->isProcessing = false;

            // Grant user access to the purchased courses and lectures
            $userId = auth()->id();
            if ($userId) {
                foreach ($this->cartItems as $item) {
                    if (isset($item['course_id'])) {
                        // Check if user already has access to this course
                        $existingAccess = \App\Models\UserCourse::where([
                            'user_id' => $userId,
                            'course_id' => $item['course_id'],
                            'lecture_id' => null,
                            'status' => 'active'
                        ])->first();

                        if (!$existingAccess) {
                            \App\Models\UserCourse::create([
                                'user_id' => $userId,
                                'course_id' => $item['course_id'],
                                'lecture_id' => null,
                                'status' => 'active',
                                'order_id' => $order->id
                            ]);
                        }
                    } elseif (isset($item['lecture_id'])) {
                        // For lecture purchases, get the course_id from the lecture
                        $lecture = \App\Models\Lecture::find($item['lecture_id']);

                        if ($lecture) {
                            $existingAccessQuery = \App\Models\UserCourse::where([
                                'user_id' => $userId,
                                'lecture_id' => $item['lecture_id'],
                                'status' => 'active'
                            ]);
                            
                            if ($lecture->course_id) {
                                $existingAccessQuery->where('course_id', $lecture->course_id);
                            } else {
                                $existingAccessQuery->whereNull('course_id');
                            }
                            
                            $existingAccess = $existingAccessQuery->first();

                            if (!$existingAccess) {
                                \App\Models\UserCourse::create([
                                    'user_id' => $userId,
                                    'course_id' => $lecture->course_id,
                                    'lecture_id' => $item['lecture_id'],
                                    'status' => 'active',
                                    'order_id' => $order->id
                                ]);
                            }
                        }
                    }
                }
            }

            if ($userId) {
                return redirect()->route('payment.success', ['order' => $order->id])
                    ->with('success', 'Your order has been completed successfully! You can now access your courses.');
            }

            return redirect()->route('home')
                ->with('success', 'Your order has been completed successfully!');
        }

        $this->isProcessing = false;
        return $this->initiatePayment($order);
    }

    public function initiatePayment($order)
    {
        try {
            // Get the selected payment method details
            $selectedMethod = PaymentMethod::where('key', $this->paymentMethod)->firstOrFail();

            if ($this->paymentMethod === 'card') {
                // Initialize Stripe
                Stripe::setApiKey(env('STRIPE_SECRET'));

                // Calculate the final total after discount and shipping
                $finalTotal = max(0, $this->total - $this->discount + $this->shippingCost);

                // Create a Stripe session
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Order Total',
                            ],
                            'unit_amount' => $finalTotal * 100, // Convert to cents
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('payment.success', ['order' => $order->id]),
                    'cancel_url' => route('payment.cancel', ['order' => $order->id]),
                    'customer_email' => $this->email,
                    'billing_address_collection' => 'required',
                    'shipping_address_collection' => [
                        'allowed_countries' => ['US', 'CA', 'GB', 'PK'],
                    ],
                ]);

                // Save the Stripe session ID to the order
                $order->update([
                    'payment_data' => json_encode(['session_id' => $session->id])
                ]);

                return redirect($session->url);
            } else {
                // For manual payment methods - cash, bank transfer, mobile payments
                return redirect()->route('payment.pending', ['order' => $order->id])
                    ->with('success', 'Your order has been placed. ' . $selectedMethod->instructions);
            }
        } catch (ApiErrorException $e) {
            $this->isProcessing = false;
            return redirect()->back()->with('error', 'Payment could not be processed: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->isProcessing = false;
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }

    public function updatePaymentMethod($method)
    {
        $this->paymentMethod = $method;
        $this->confirmPayment = false;
    }

    public function updatedConfirmPayment($value)
    {
    }

    public function updatedPaymentMethod($value)
    {
        $this->confirmPayment = false;
    }

    public function applyCoupon()
    {
        $this->validate([
            'couponCode' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code';
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        if (!$coupon->is_active) {
            $this->couponError = 'This coupon is inactive';
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        if ($coupon->max_uses !== null && $coupon->uses_count >= $coupon->max_uses) {
            $this->couponError = 'This coupon has reached its maximum usage limit';
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        $now = now();
        if ($now < $coupon->valid_from) {
            $this->couponError = 'This coupon is not yet valid. Valid from: ' . $coupon->valid_from->format('M d, Y');
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        if ($now > $coupon->valid_until) {
            $this->couponError = 'This coupon has expired. Expired on: ' . $coupon->valid_until->format('M d, Y');
            $this->couponSuccess = '';
            $this->discount = 0;
            $this->appliedCoupon = null;
            return;
        }

        $this->discount = $coupon->calculateDiscount($this->total);
        $this->couponSuccess = 'Coupon applied successfully!';
        $this->couponError = '';
        $this->appliedCoupon = $coupon;

        // Check if the total after discount is zero
        if (($this->total - $this->discount) <= 0) {
            $user = auth()->user();

            if (empty($this->firstName)) {
                $this->firstName = $user->first_name ?? explode(' ', $user->name)[0] ?? 'User';
            }
            if (empty($this->lastName)) {
                $this->lastName = $user->last_name ?? (count(explode(' ', $user->name)) > 1 ? explode(' ', $user->name)[1] : 'Customer');
            }
            $this->fullName = trim($this->firstName . ' ' . $this->lastName);
            if (empty($this->email)) {
                $this->email = $user->email;
            }
            if (empty($this->phone)) {
                $this->phone = $user->phone ?? '';
            }
            if (empty($this->address)) {
                $this->address = $user->address ?? 'Not Provided';
            }
            if (empty($this->country)) {
                $this->country = $user->country ?? 'PK';
            }
            if (empty($this->state)) {
                $this->state = $user->state ?? 'Not Provided';
            }
            if (empty($this->city)) {
                $this->city = $user->city ?? 'Not Provided';
            }
            if (empty($this->postalCode)) {
                $this->postalCode = $user->postal_code ?? '00000';
            }

            $this->paymentMethod = 'free';
            $this->confirmPayment = true;
        }
    }

    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->discount = 0;
        $this->couponError = '';
        $this->couponSuccess = '';
        $this->appliedCoupon = null;
    }

    public function handleAddressSelected($addressData)
    {
        $this->address = $addressData['address'];
        $this->city = $addressData['city'];
        $this->state = $addressData['state'];
        $this->country = $addressData['country'];
        $this->postalCode = $addressData['postalCode'];
    }
}
