<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\PaymentMethod;
use App\Models\Role;
use App\Models\User;
use App\Models\Shoppingcart;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Checkout;

class GhousiaCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure User role exists
        Role::updateOrCreate(['name' => 'User']);
        
        // Ensure payment methods exist
        PaymentMethod::updateOrCreate(
            ['key' => 'cod'],
            ['name' => 'Cash on Delivery', 'is_active' => true, 'sort_order' => 1]
        );
        PaymentMethod::updateOrCreate(
            ['key' => 'card'],
            ['name' => 'Credit/Debit Card', 'is_active' => true, 'sort_order' => 2]
        );
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/sign-in');
    }

    public function test_authenticated_user_with_empty_cart_is_redirected_to_shopping_cart()
    {
        $user = User::factory()->create([
            'password' => bcrypt('SecurePass123!')
        ]);
        
        $response = $this->actingAs($user)->get('/checkout');
        $response->assertRedirect('/shopping-cart');
    }

    public function test_checkout_calculations_and_order_creation()
    {
        $user = User::factory()->create([
            'name' => 'Ali Raza',
            'email' => 'aliraza@example.com',
            'phone' => '03211234567',
            'password' => bcrypt('SecurePass123!')
        ]);

        $course = Course::create([
            'slug' => 'johnson-baby-lotion',
            'name' => 'Johnson Baby Lotion',
            'description' => 'Baby care product',
            'long_description' => 'Long baby care product',
            'weekly_price' => 1250.00,
            'monthly_price' => 1250.00,
            'image_path' => 'ghousiatraders/assets/baby_lotion.png',
            'is_free' => 0,
            'purchase_model' => 'flexible'
        ]);

        // Add to cart
        Shoppingcart::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'quantity' => 1,
            'price' => 1250
        ]);

        // Access checkout page
        $response = $this->actingAs($user)->get('/checkout');
        $response->assertOk();

        // Use Livewire to test the checkout component properties and order creation
        Livewire::actingAs($user)
            ->test(Checkout::class)
            ->assertSet('fullName', 'Ali Raza')
            ->assertSet('email', 'aliraza@example.com')
            ->assertSet('phone', '03211234567')
            ->assertSet('total', 1250.0)
            ->assertSet('shippingCost', 0)
            ->assertSet('deliveryMethod', 'standard')
            // Toggle to express delivery
            ->set('deliveryMethod', 'express')
            ->assertSet('shippingCost', 250)
            // Fill address info
            ->set('address', '12, Main Market, DHA Phase 6')
            ->set('address2', 'Near Park View')
            ->set('city', 'Lahore')
            ->set('area', 'DHA Phase 6')
            ->set('postalCode', '54000')
            ->set('paymentMethod', 'cod')
            ->set('orderNotes', 'Handle with care')
            // Create order
            ->call('createOrder')
            ->assertHasNoErrors()
            ->assertRedirect();

        // Assert order was saved in the database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 1250.0,
            'discount' => 0,
            'final_total' => 1500.0, // 1250 + 250 shipping
            'payment_method' => 'cod'
        ]);

        // Assert order billing address has correct metadata
        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        
        $billing = json_decode($order->billing_address, true);
        $this->assertEquals('Ali', $billing['first_name']);
        $this->assertEquals('Raza', $billing['last_name']);
        $this->assertEquals('12, Main Market, DHA Phase 6', $billing['address']);
        $this->assertEquals('Near Park View', $billing['address2']);
        $this->assertEquals('DHA Phase 6', $billing['area']);
        $this->assertEquals('Handle with care', $billing['notes']);
        $this->assertEquals('express', $billing['delivery_method']);
        $this->assertEquals(250, $billing['shipping_cost']);

        // Assert shopping cart was cleared
        $this->assertDatabaseMissing('shoppingcarts', [
            'user_id' => $user->id
        ]);
    }
}
