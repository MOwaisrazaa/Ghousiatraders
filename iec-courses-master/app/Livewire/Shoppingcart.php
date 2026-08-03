<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Shoppingcart as Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Shoppingcart extends Component
{
    public $cartitems = [];
    public $totalAmount = 0;
    public $isGuest = false;

    public function mount()
    {
        $this->isGuest = !auth()->check();
        $this->loadCartItems();
    }

    private function sessionCart(): array
    {
        return session()->get('polani_cart', []);
    }

    private function saveSessionCart(array $items): void
    {
        session()->put('polani_cart', array_values($items));
        session()->save();
    }

    public function loadCartItems()
    {
        if (auth()->check()) {
            $this->cartitems = Cart::with('course', 'lecture')
                ->where('user_id', auth()->id())
                ->get();

            $userId = auth()->id();
            foreach ($this->cartitems as $item) {
                $shouldRemove = false;

                if ($item->course && $item->course->is_free) {
                    \App\Services\FreeCourseService::enrollInFreeCourse($userId, $item->course_id);
                    $shouldRemove = true;
                } elseif ($item->lecture && $item->lecture->is_free) {
                    \App\Services\FreeCourseService::enrollInFreeLecture($userId, $item->lecture_id);
                    $shouldRemove = true;
                }

                if ($shouldRemove) {
                    $item->delete();
                }
            }

            $this->cartitems = Cart::with('course', 'lecture')
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $this->cartitems = collect($this->sessionCart())->map(function ($item) {
                $course = Course::find($item['course_id'] ?? null);
                if (!$course) {
                    return null;
                }

                $cartItem = new \stdClass();
                $cartItem->id = $course->id;
                $cartItem->course_id = $course->id;
                $cartItem->lecture_id = null;
                $cartItem->price = (float) ($item['price'] ?? $course->weekly_price ?? 0);
                $cartItem->quantity = (int) ($item['quantity'] ?? 1);
                $cartItem->discount_amount = 0;
                $cartItem->discount_reason = null;
                $cartItem->course = (object) [
                    'name' => $course->name,
                    'image_path' => $course->image_path,
                    'is_free' => $course->is_free,
                ];
                $cartItem->lecture = null;
                return $cartItem;
            })->filter()->values();
        }

        $this->calculateTotalAmount();
    }

    public function calculateTotalAmount()
    {
        $this->totalAmount = collect($this->cartitems)->sum(function ($item) {
            $isObject = is_object($item);
            $price = (float) ($isObject ? ($item->price ?? 0) : ($item['price'] ?? 0));
            $quantity = (int) ($isObject ? ($item->quantity ?? 1) : ($item['quantity'] ?? 1));

            return $price * $quantity;
        });
    }

    public function incrementQuantity($itemId)
    {
        if (auth()->check()) {
            $cartItem = Cart::where('id', $itemId)
                ->where('user_id', auth()->id())
                ->first();

            if ($cartItem) {
                $cartItem->quantity = max(1, (int) $cartItem->quantity + 1);
                $cartItem->save();
            }
        } else {
            $cart = $this->sessionCart();

            foreach ($cart as &$item) {
                if ((int) ($item['course_id'] ?? 0) === (int) $itemId) {
                    $item['quantity'] = max(1, (int) ($item['quantity'] ?? 1) + 1);
                    break;
                }
            }
            unset($item);

            $this->saveSessionCart($cart);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', [
            'message' => 'Product quantity updated in your cart.',
            'type' => 'info',
            'actionText' => 'View Cart',
            'actionUrl' => route('shopping-cart')
        ]);
    }

    public function decrementQuantity($itemId)
    {
        if (auth()->check()) {
            $cartItem = Cart::where('id', $itemId)
                ->where('user_id', auth()->id())
                ->first();

            if ($cartItem && (int) $cartItem->quantity > 1) {
                $cartItem->quantity = (int) $cartItem->quantity - 1;
                $cartItem->save();
            }
        } else {
            $cart = $this->sessionCart();

            foreach ($cart as &$item) {
                if ((int) ($item['course_id'] ?? 0) === (int) $itemId) {
                    $currentQuantity = (int) ($item['quantity'] ?? 1);
                    $item['quantity'] = max(1, $currentQuantity - 1);
                    break;
                }
            }
            unset($item);

            $this->saveSessionCart($cart);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated');
        $this->dispatch('show-toast', [
            'message' => 'Product quantity updated in your cart.',
            'type' => 'info',
            'actionText' => 'View Cart',
            'actionUrl' => route('shopping-cart')
        ]);
    }

    public function removeFromCart($itemId)
    {
        $removedItem = collect($this->cartitems)->first(function ($item) use ($itemId) {
            $isObject = is_object($item);
            $id = $isObject ? ($item->id ?? null) : ($item['id'] ?? null);
            $courseId = $isObject ? ($item->course_id ?? null) : ($item['course_id'] ?? null);
            return (int) $id === (int) $itemId || (int) $courseId === (int) $itemId;
        });

        $name = 'Product';
        $price = null;
        $img = null;

        if ($removedItem) {
            $isObject = is_object($removedItem);
            $courseObj = $isObject ? ($removedItem->course ?? null) : ($removedItem['course'] ?? null);
            $courseObj = is_array($courseObj) ? (object) $courseObj : $courseObj;
            if ($courseObj && !empty($courseObj->name)) {
                $name = $courseObj->name;
            }
            if ($courseObj && !empty($courseObj->image_path)) {
                $img = asset($courseObj->image_path);
            }
            $price = (float) ($isObject ? ($removedItem->price ?? 0) : ($removedItem['price'] ?? 0));
        }

        if (auth()->check()) {
            Cart::where(function ($query) use ($itemId) {
                $query->where('id', $itemId)->orWhere('course_id', $itemId);
            })->where('user_id', auth()->id())->delete();
        } else {
            $cart = $this->sessionCart();
            $cart = array_values(array_filter($cart, fn ($item) => (int) ($item['course_id'] ?? 0) !== (int) $itemId));
            $this->saveSessionCart($cart);
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated', count: collect($this->cartitems)->sum('quantity'));
        $this->dispatch('show-toast', [
            'heading' => 'Removed from Cart',
            'name' => $name,
            'subtitle' => 'Item removed from your cart',
            'price' => $price,
            'image' => $img,
            'type' => 'amber'
        ]);
    }

    public function checkout()
    {
        if ($this->cartitems->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        if (!auth()->check()) {
            session()->put('url.intended', route('checkout'));
            return redirect()->route('sign-in')
                ->with('error', 'Please sign in to continue with checkout for COD or online payment.');
        }

        return redirect()->route('checkout', [
            'cartItems' => $this->cartitems->map(function ($item) {
                $payload = [
                    'course' => $item->course ? ['name' => $item->course->name, 'id' => $item->course_id] : null,
                    'lecture' => $item->lecture ? ['name' => $item->lecture->name, 'id' => $item->lecture_id] : null,
                    'course_id' => $item->course_id,
                    'lecture_id' => $item->lecture_id,
                    'quantity' => 1,
                    'price' => $item->price,
                ];

                if (!empty($item->discount_amount)) {
                    $payload['original_price'] = $item->original_price;
                    $payload['discount_amount'] = $item->discount_amount;
                    $payload['discount_reason'] = $item->discount_reason;
                }

                return $payload;
            })->toArray(),
            'total' => $this->totalAmount,
        ]);
    }

    public function clearCart()
    {
        if (auth()->check()) {
            Cart::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('polani_cart');
        }

        $this->loadCartItems();
        $this->dispatch('cartUpdated', count: 0);
        $this->dispatch('show-toast', [
            'heading' => 'Cart Cleared',
            'name' => 'Shopping Cart',
            'subtitle' => 'All items removed from your cart',
            'type' => 'amber'
        ]);
    }

    public function render()
    {
        $this->loadCartItems();
        return view('livewire.shoppingcart', [
            'cartitems' => $this->cartitems,
            'totalAmount' => $this->totalAmount,
        ])->layout('layouts.app');
    }
}
