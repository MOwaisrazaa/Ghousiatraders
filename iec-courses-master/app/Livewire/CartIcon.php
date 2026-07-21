<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shoppingcart;
use Illuminate\Support\Facades\Cache;

class CartIcon extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        try {
            if (auth()->check()) {
                // Always get fresh cart count without caching to ensure accuracy
                $this->cartCount = Shoppingcart::where('user_id', auth()->id())->count();
            } else {
                $this->cartCount = 0;
            }
        } catch (\Exception $e) {
            // If there's an error, just set cart count to 0
            $this->cartCount = 0;
            \Log::warning('CartIcon error: ' . $e->getMessage());
        }
    }

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function refreshCart()
    {
        $this->updateCartCount();
    }

    public function render()
    {
        // Always get fresh cart count on render
        $this->updateCartCount();
        return view('livewire.cart-icon');
    }
}
