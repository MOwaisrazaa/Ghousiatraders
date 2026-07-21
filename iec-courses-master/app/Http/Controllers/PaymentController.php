<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\UserCourse;
use App\Models\PaymentMethod;
use App\Models\Lecture;

class PaymentController extends Controller
{
    public function success($order)
    {
        $order = Order::findOrFail($order);
        $userId = auth()->id() ?: $order->user_id;

        // Update order status to 'paid' or 'completed'
        if ($order->status !== 'completed') {
            $order->update(['status' => 'paid']);
        }

        // Grant user access only when the order belongs to a logged-in user
        $cartItems = json_decode($order->cart_items, true);

        // Log the cart items for debugging
        \Illuminate\Support\Facades\Log::info('Cart items in order success:', ['items' => $cartItems]);

        if ($userId && is_array($cartItems)) {
            foreach ($cartItems as $item) {
                \Illuminate\Support\Facades\Log::info('Processing item:', ['item' => $item]);

                if (isset($item['course_id'])) {
                    // Check if user already has access to this course
                    $existingAccess = UserCourse::where([
                        'user_id' => $userId,
                        'course_id' => $item['course_id'],
                        'lecture_id' => null,
                        'status' => 'active'
                    ])->first();

                    if (!$existingAccess) {
                        $courseAccess = UserCourse::create([
                            'user_id' => $userId,
                            'course_id' => $item['course_id'],
                            'lecture_id' => null,
                            'status' => 'active',
                            'order_id' => $order->id
                        ]);
                        \Illuminate\Support\Facades\Log::info('Created course access:', ['access' => $courseAccess]);
                    } else {
                        \Illuminate\Support\Facades\Log::info('User already has course access:', ['course_id' => $item['course_id']]);
                    }
                } elseif (isset($item['lecture_id'])) {
                    // For lecture purchases, get the associated course_id (may be null for standalone)
                    $lecture = Lecture::find($item['lecture_id']);

                    if ($lecture) {
                        // Check if user already has access to this lecture - handle standalone lectures
                        $existingAccessQuery = UserCourse::where([
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
                            $lectureAccess = UserCourse::create([
                                'user_id' => $userId,
                                'course_id' => $lecture->course_id, // Will be null for standalone lectures
                                'lecture_id' => $item['lecture_id'],
                                'status' => 'active',
                                'order_id' => $order->id
                            ]);
                            \Illuminate\Support\Facades\Log::info('Created lecture access:', ['access' => $lectureAccess]);
                        } else {
                            \Illuminate\Support\Facades\Log::info('User already has lecture access:', ['lecture_id' => $item['lecture_id']]);
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::warning('Lecture not found', ['lecture_id' => $item['lecture_id']]);
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning('Item has no course_id or lecture_id', ['item' => $item]);
                }
            }
        } elseif ($userId) {
            \Illuminate\Support\Facades\Log::error('Cart items is not an array', ['cart_items' => $order->cart_items]);
        }

        if ($userId) {
            \App\Models\Shoppingcart::where('user_id', $userId)->delete();
        }

        $context = $this->buildOrderStatusContext($order);

        return view('polani.order-status', [
            'order' => $order,
            'message' => 'Your order has been placed successfully. A confirmation email has been sent to your inbox.',
            'paymentMethod' => PaymentMethod::where('key', $order->payment_method)->first(),
            'statusLabel' => 'Order Confirmed',
            'statusTone' => 'success',
            'actionLabel' => 'Continue Shopping',
            'actionUrl' => route('polani.collection'),
            'orderNumber' => $context['orderNumber'],
            'orderDate' => $context['orderDate'],
            'estimatedDelivery' => $context['estimatedDelivery'],
            'billingAddress' => $context['billingAddress'],
            'orderedItems' => $context['orderedItems'],
            'recommendations' => $context['recommendations'],
            'paymentLabel' => $context['paymentLabel'],
            'subtotal' => $context['subtotal'],
            'shippingLabel' => $context['shippingLabel'],
            'totalAmount' => $context['totalAmount'],
        ]);
    }

    public function cancel($order)
    {
        $order = Order::findOrFail($order);

        // Update order status to 'failed'
        $order->update(['status' => 'failed']);

        return redirect()->route('checkout')->with('error', 'Payment was cancelled. Please try again.');
    }

    public function pending($order)
    {
        $order = Order::findOrFail($order);

        // Get payment method details from database
        $paymentMethod = \App\Models\PaymentMethod::where('key', $order->payment_method)->first();

        // Get payment instructions
        $instructions = $paymentMethod ? $paymentMethod->instructions : $this->getDefaultInstructions($order->payment_method);
        $context = $this->buildOrderStatusContext($order);

        return view('polani.order-status', [
            'order' => $order,
            'message' => $instructions,
            'paymentMethod' => $paymentMethod,
            'statusLabel' => 'Order Received',
            'statusTone' => 'pending',
            'actionLabel' => 'Continue Shopping',
            'actionUrl' => route('polani.collection'),
            'orderNumber' => $context['orderNumber'],
            'orderDate' => $context['orderDate'],
            'estimatedDelivery' => $context['estimatedDelivery'],
            'billingAddress' => $context['billingAddress'],
            'orderedItems' => $context['orderedItems'],
            'recommendations' => $context['recommendations'],
            'paymentLabel' => $context['paymentLabel'],
            'subtotal' => $context['subtotal'],
            'shippingLabel' => $context['shippingLabel'],
            'totalAmount' => $context['totalAmount'],
        ]);
    }

    private function buildOrderStatusContext(Order $order): array
    {
        $cartItems = json_decode($order->cart_items, true);
        $orderedItems = [];
        $excludeIds = [];
        $subtotal = (float) ($order->total ?? 0);
        $totalAmount = (float) ($order->final_total ?? $order->total ?? 0);

        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                if (isset($item['course_id'])) {
                    $product = Course::find($item['course_id']);

                    if ($product) {
                        $quantity = (int) ($item['quantity'] ?? 1);
                        $price = (float) ($item['price'] ?? $product->weekly_price ?? 0);
                        $orderedItems[] = [
                            'name' => $product->name,
                            'slug' => $product->slug,
                            'image' => $product->image_path ? asset($product->image_path) : asset('polani/assets/product-noir-elixir.jpg'),
                            'quantity' => $quantity,
                            'unit_price' => $price,
                            'line_total' => $price * $quantity,
                        ];
                        $excludeIds[] = $product->id;
                    }
                }
            }
        }

        if (empty($orderedItems)) {
            $fallbackProducts = Course::query()->orderBy('id')->take(3)->get();
            foreach ($fallbackProducts as $product) {
                $orderedItems[] = [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->image_path ? asset($product->image_path) : asset('polani/assets/product-noir-elixir.jpg'),
                    'quantity' => 1,
                    'unit_price' => (float) ($product->weekly_price ?? 0),
                    'line_total' => (float) ($product->weekly_price ?? 0),
                ];
                $excludeIds[] = $product->id;
            }
        }

        $recommendations = Course::query()
            ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
            ->orderBy('id')
            ->take(4)
            ->get();

        $billingAddressData = json_decode($order->billing_address ?? '{}', true);
        if (!is_array($billingAddressData)) {
            $billingAddressData = [];
        }

        $fullName = trim(($billingAddressData['first_name'] ?? '') . ' ' . ($billingAddressData['last_name'] ?? ''));
        $addressLines = array_filter([
            $billingAddressData['address'] ?? null,
            trim(($billingAddressData['city'] ?? '') . (!empty($billingAddressData['state']) ? ', ' . $billingAddressData['state'] : '')),
            trim(($billingAddressData['country'] ?? '') . (!empty($billingAddressData['postal_code']) ? ' ' . $billingAddressData['postal_code'] : '')),
        ]);

        $paymentLabel = $this->formatPaymentLabel($order->payment_method);

        return [
            'orderNumber' => sprintf('#PF-%s-%04d', now()->format('Y'), $order->id),
            'orderDate' => optional($order->created_at)->format('F j, Y') ?? now()->format('F j, Y'),
            'estimatedDelivery' => now()->addDays(4)->format('M j') . ' - ' . now()->addDays(8)->format('M j, Y'),
            'billingAddress' => [
                'name' => $fullName ?: 'Polani Customer',
                'email' => $billingAddressData['email'] ?? null,
                'phone' => $billingAddressData['phone'] ?? null,
                'lines' => $addressLines,
            ],
            'orderedItems' => $orderedItems,
            'recommendations' => $recommendations,
            'paymentLabel' => $paymentLabel,
            'subtotal' => $subtotal,
            'shippingLabel' => 'Free',
            'totalAmount' => $totalAmount,
        ];
    }

    private function formatPaymentLabel(?string $method): string
    {
        return match ($method) {
            'card' => 'Card Payment',
            'cash' => 'Cash on Delivery',
            'jazzcash' => 'JazzCash',
            'easypaisa' => 'Easypaisa',
            'banktransfer' => 'Bank Transfer',
            default => $method ? ucwords(str_replace(['_', '-'], ' ', $method)) : 'Polani Payment',
        };
    }

    private function getDefaultInstructions($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'cash':
                return 'Please visit our office to make your cash payment. Remember to bring your order number.';

            case 'jazzcash':
                return 'Please send your payment to our Jazz Cash account: +92 333 1234567 and send the screenshot to +92 312 9876543.';

            case 'easypaisa':
                return 'Please send your payment to our Easypaisa account: +92 345 1234567 and send the screenshot to +92 312 9876543.';

            case 'banktransfer':
                return 'Please transfer the amount to our bank account: 1234-5678-9012-3456 (HBL Pakistan) and send the payment confirmation to +92 312 9876543.';

            default:
                return 'Your order is being processed. You will receive further instructions shortly.';
        }
    }
}
