<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaTrackOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::updateOrCreate(['name' => 'User']);
    }

    public function test_track_order_page_is_accessible()
    {
        $response = $this->get('/track-order');
        $response->assertOk()
            ->assertSee('Track Your Order')
            ->assertSee('Order ID')
            ->assertSee('Email / Phone Number');
    }

    public function test_track_order_not_found()
    {
        $response = $this->get('/track-order?order_number=GT-999&email=notfound@example.com');
        $response->assertOk()
            ->assertSee('No order was found for the details you entered.');
    }

    public function test_track_order_successful()
    {
        // 1. Create a dummy user
        $user = User::factory()->create();

                // 2. Create a dummy product/course
        $course = Course::create([
            'name' => 'Johnson’s Baby Lotion 500ml',
            'slug' => 'johnsons-baby-lotion-500ml',
            'description' => 'Gentle care for soft, healthy skin',
            'weekly_price' => 1250,
            'monthly_price' => 5000,
            'purchase_model' => 'flexible',
            'image_path' => 'polani/assets/product-noir-elixir.jpg',
        ]);

        // 3. Create an order
        $order = Order::create([
            'user_id' => $user->id,
            'cart_items' => json_encode([
                [
                    'course_id' => $course->id,
                    'quantity' => 1,
                    'price' => 1250,
                ]
            ]),
            'total' => 1250,
            'discount' => 0,
            'final_total' => 1250,
            'status' => 'pending',
            'payment_method' => 'cash on delivery',
            'billing_address' => json_encode([
                'first_name' => 'Ali',
                'last_name' => 'Raza',
                'email' => 'ali@example.com',
                'phone' => '03211234567',
                'address' => '12, Main Market, DHA Phase 6',
                'city' => 'Lahore',
                'country' => 'Pakistan',
                'shipping_cost' => 0,
            ])
        ]);

        // 4. Request with valid credentials
        $response = $this->get('/track-order?order_number=GT-' . $order->id . '&email=ali@example.com');
        $response->assertOk()
            ->assertSee('#GT-' . $order->id)
            ->assertSee('Johnson’s Baby Lotion 500ml')
            ->assertSee('Gentle care for soft, healthy skin')
            ->assertSee('Cash on Delivery')
            ->assertSee('Out for delivery');
    }
}
