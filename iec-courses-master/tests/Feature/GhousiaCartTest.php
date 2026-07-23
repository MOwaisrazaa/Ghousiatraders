<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use App\Livewire\Shoppingcart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GhousiaCartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a product
        Course::updateOrCreate(
            ['slug' => 'toyota-land-cruiser'],
            [
                'name' => 'Toyota Land Cruiser B/O Car',
                'description' => 'Authentic description',
                'long_description' => 'Authentic long description',
                'weekly_price' => 32999.00,
                'monthly_price' => 32999.00,
                'image_path' => 'ghousiatraders/assets/land_cruiser.png',
                'is_free' => 0,
                'purchase_model' => 'flexible'
            ]
        );

        // Create another product
        Course::updateOrCreate(
            ['slug' => 'mercedes-amg'],
            [
                'name' => 'Mercedes B/O Car (AMG)',
                'description' => 'Mercedes description',
                'long_description' => 'Mercedes long description',
                'weekly_price' => 29999.00,
                'monthly_price' => 29999.00,
                'image_path' => 'ghousiatraders/assets/mercedes_amg.png',
                'is_free' => 0,
                'purchase_model' => 'flexible'
            ]
        );
    }

    public function test_guest_cart_calculations()
    {
        // 1. Initial State (Empty)
        session()->forget('polani_cart');
        $this->get(route('shopping-cart'))
            ->assertOk()
            ->assertSee('Your cart is empty');

        // 2. Add Toyota Land Cruiser once
        $response1 = $this->postJson(route('polani.cart.add', ['slug' => 'toyota-land-cruiser']))
            ->assertJsonStructure(['success', 'cartCount']);

        // Assert session has the item
        $cart = $this->app['session']->get('polani_cart');
        $this->assertCount(1, $cart);
        $this->assertEquals(1, $cart[0]['quantity']);
        $this->assertEquals(32999.00, $cart[0]['price']);

        $product1Id = $cart[0]['course_id'];

        // 3. Test 1: Load Livewire component and assert correctness at quantity 1
        session(['polani_cart' => $cart]);
        Livewire::test(Shoppingcart::class)
            ->assertSet('totalAmount', 32999.00)
            ->assertSee('Toyota Land Cruiser')
            ->assertSee('PKR 32,999');

        // 4. Test 5: Add same product again via controller, passing the session
        $this->withSession(['polani_cart' => $cart])
            ->postJson(route('polani.cart.add', ['slug' => 'toyota-land-cruiser']))
            ->assertOk();

        // Assert quantity is 2 and price remains correct
        $cart = $this->app['session']->get('polani_cart');
        $this->assertEquals(2, $cart[0]['quantity']);
        $this->assertEquals(32999.00, $cart[0]['price']);

        // 5. Test 2: Livewire component handles quantity 2
        session(['polani_cart' => $cart]);
        Livewire::test(Shoppingcart::class)
            ->assertSet('totalAmount', 65998.00)
            ->assertSee('PKR 65,998');

        // 6. Test 3: Click plus button inside Livewire (incrementQuantity)
        session(['polani_cart' => $cart]);
        Livewire::test(Shoppingcart::class)
            ->call('incrementQuantity', $product1Id)
            ->assertSet('totalAmount', 98997.00)
            ->assertSee('PKR 98,997');

        // 7. Test 4: Click minus button inside Livewire (decrementQuantity)
        session(['polani_cart' => $cart]);
        Livewire::test(Shoppingcart::class)
            ->call('incrementQuantity', $product1Id) // To 3
            ->call('decrementQuantity', $product1Id) // To 2
            ->assertSet('totalAmount', 65998.00)
            ->assertSee('PKR 65,998');

        // 8. Test 6: Add second product with different price
        $this->withSession(['polani_cart' => $cart])
            ->postJson(route('polani.cart.add', ['slug' => 'mercedes-amg']))
            ->assertOk();

        $cart = $this->app['session']->get('polani_cart');

        // Livewire calculates sum of both products
        // Toyota (qty 2) = 65,998
        // Mercedes (qty 1) = 29,999
        // Total = 95,997
        session(['polani_cart' => $cart]);
        Livewire::test(Shoppingcart::class)
            ->assertSet('totalAmount', 95997.00)
            ->assertSee('PKR 95,997');

        // 9. Test removal from cart
        session(['polani_cart' => $cart]);
        Livewire::test(Shoppingcart::class)
            ->call('removeFromCart', $product1Id) // Remove Toyota
            ->assertSet('totalAmount', 29999.00)
            ->assertSee('PKR 29,999')
            ->assertDontSee('Toyota Land Cruiser');
    }

    public function test_authenticated_cart_calculations()
    {
        $user = User::factory()->create();
        $toyota = Course::where('slug', 'toyota-land-cruiser')->first();

        // 1. Initial State (Empty)
        $this->actingAs($user)
            ->get(route('shopping-cart'))
            ->assertOk()
            ->assertSee('Your cart is empty');

        // 2. Add Toyota Land Cruiser once
        $this->actingAs($user)
            ->postJson(route('polani.cart.add', ['slug' => 'toyota-land-cruiser']))
            ->assertOk();

        // Assert database has 1 item
        $this->assertDatabaseHas('shoppingcarts', [
            'user_id' => $user->id,
            'course_id' => $toyota->id,
            'quantity' => 1
        ]);

        $cartItem = \App\Models\Shoppingcart::where('user_id', $user->id)->first();
        $this->assertNotNull($cartItem);

        // 3. Load Livewire component for logged-in user
        Livewire::actingAs($user)
            ->test(Shoppingcart::class)
            ->assertSet('totalAmount', 32999.00)
            ->assertSee('Toyota Land Cruiser')
            ->assertSee('PKR 32,999');

        // 4. Add same product again
        $this->actingAs($user)
            ->postJson(route('polani.cart.add', ['slug' => 'toyota-land-cruiser']))
            ->assertOk();

        // Assert database has quantity 2
        $this->assertDatabaseHas('shoppingcarts', [
            'user_id' => $user->id,
            'course_id' => $toyota->id,
            'quantity' => 2
        ]);

        // 5. Test increment inside Livewire
        Livewire::actingAs($user)
            ->test(Shoppingcart::class)
            ->call('incrementQuantity', $cartItem->id)
            ->assertSet('totalAmount', 98997.00); // 3 items

        // 6. Test decrement inside Livewire
        Livewire::actingAs($user)
            ->test(Shoppingcart::class)
            ->call('decrementQuantity', $cartItem->id)
            ->assertSet('totalAmount', 65998.00); // 2 items

        // 7. Test removal inside Livewire
        Livewire::actingAs($user)
            ->test(Shoppingcart::class)
            ->call('removeFromCart', $cartItem->id)
            ->assertSet('totalAmount', 0.0)
            ->assertSee('Your cart is empty');

        // Assert database is empty
        $this->assertDatabaseMissing('shoppingcarts', [
            'user_id' => $user->id
        ]);
    }
}
