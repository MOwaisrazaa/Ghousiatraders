<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Order;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $attributes = []): Course
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-category'],
            ['name' => 'Test Category']
        );

        return Course::create(array_merge([
            'slug' => 'test-product-' . uniqid(),
            'sku' => 'GT-TEST-' . rand(100, 999),
            'name' => 'Test Product',
            'category_id' => $category->id,
            'description' => 'Short description',
            'long_description' => '<p>Detailed description</p>',
            'weekly_price' => 1000,
            'monthly_price' => 1000,
            'sale_price' => 900,
            'stock' => 10,
            'low_stock_threshold' => 2,
            'status' => 'active',
            'is_featured' => false,
            'image_path' => 'ghousiatraders/assets/product.png',
        ], $attributes));
    }

    public function test_unreviewed_product_shows_empty_state_and_no_fake_reviews()
    {
        $product = $this->createProduct([
            'slug' => 'test-toy-car',
            'name' => 'Test Toy Car',
        ]);

        $response = $this->get('/product/test-toy-car');

        $response->assertStatus(200);
        $response->assertSee('No customer reviews yet');
        $response->assertDontSee('Hamza R.');
        $response->assertDontSee('Usman K.');
        $response->assertDontSee('Adeel M.');
        $response->assertDontSee('128 Reviews');
    }

    public function test_guest_user_sees_sign_in_required_notice()
    {
        $product = $this->createProduct([
            'slug' => 'test-ride-on',
            'name' => 'Test Ride On',
        ]);

        $response = $this->get('/product/test-ride-on');

        $response->assertStatus(200);
        $response->assertSee('Sign in required');
    }

    public function test_user_without_order_cannot_submit_review()
    {
        $user = User::factory()->create();
        $product = $this->createProduct([
            'slug' => 'test-bike',
            'name' => 'Test Bike',
        ]);

        $response = $this->actingAs($user)->postJson(route('products.rate', ['course' => $product->id]), [
            'rating' => 5,
            'title' => 'Great product!',
            'comment' => 'This is a test review.',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Only verified purchasers of this product can submit a review.',
        ]);
    }

    public function test_verified_purchaser_can_submit_review_and_requires_admin_approval()
    {
        $user = User::factory()->create(['name' => 'Test Purchaser']);
        $product = $this->createProduct([
            'slug' => 'verified-car',
            'name' => 'Verified Car',
        ]);

        Order::create([
            'user_id' => $user->id,
            'cart_items' => json_encode([['course_id' => $product->id, 'name' => $product->name, 'price' => 1000]]),
            'total' => 1000,
            'final_total' => 1000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->postJson(route('products.rate', ['course' => $product->id]), [
            'rating' => 5,
            'title' => 'Verified Excellent Car',
            'comment' => 'Truly amazing quality product!',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'rateable_id' => $product->id,
            'rating' => 5,
            'title' => 'Verified Excellent Car',
            'status' => 'pending',
            'is_approved' => false,
            'is_verified_purchase' => true,
        ]);

        // Log out user so guest response is truly unauthenticated
        auth()->logout();

        // Guest viewing PDP must NOT see the pending review in public reviews section
        $guestResponse = $this->get('/product/verified-car');
        $guestResponse->assertStatus(200);
        $guestResponse->assertDontSee('Verified Excellent Car');
        $guestResponse->assertSee('No customer reviews yet');

        // Admin approves review
        $rating = Rating::where('user_id', $user->id)->first();
        $rating->update([
            'status' => 'approved',
            'is_approved' => true,
            'show_publicly' => true,
        ]);

        // Now approved review appears on public PDP for all users
        $pdpResponse2 = $this->get('/product/verified-car');
        $pdpResponse2->assertStatus(200);
        $pdpResponse2->assertSee('Verified Excellent Car');
        $pdpResponse2->assertSee('Test Purchaser');
        $pdpResponse2->assertSee('Verified Purchase');
        $pdpResponse2->assertDontSee('No customer reviews yet');
    }

    public function test_purchaser_can_edit_existing_review_without_creating_duplicates()
    {
        $user = User::factory()->create(['name' => 'Updating Customer']);
        $product = $this->createProduct([
            'slug' => 'editable-toy',
            'name' => 'Editable Toy',
        ]);

        Order::create([
            'user_id' => $user->id,
            'cart_items' => json_encode([['course_id' => $product->id, 'name' => $product->name, 'price' => 1000]]),
            'total' => 1000,
            'final_total' => 1000,
            'status' => 'completed',
        ]);

        // First submission
        $this->actingAs($user)->postJson(route('products.rate', ['course' => $product->id]), [
            'rating' => 4,
            'title' => 'Initial Title',
            'comment' => 'Initial comment text.',
        ]);

        $this->assertEquals(1, Rating::where('user_id', $user->id)->where('rateable_id', $product->id)->count());

        // Second submission (Edit)
        $this->actingAs($user)->postJson(route('products.rate', ['course' => $product->id]), [
            'rating' => 5,
            'title' => 'Updated Best Title',
            'comment' => 'Updated comment text.',
        ]);

        // Database should still contain exactly 1 rating record
        $this->assertEquals(1, Rating::where('user_id', $user->id)->where('rateable_id', $product->id)->count());
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'rating' => 5,
            'title' => 'Updated Best Title',
            'comment' => 'Updated comment text.',
        ]);
    }
}
