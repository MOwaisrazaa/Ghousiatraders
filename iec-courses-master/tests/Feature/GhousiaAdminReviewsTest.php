<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Rating;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaAdminReviewsTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;
    protected $testProduct;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::updateOrCreate(['name' => 'Admin']);
        $userRole = Role::updateOrCreate(['name' => 'User']);

        // Create users
        $this->adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admintest@example.com',
        ]);
        $this->adminUser->roles()->attach($adminRole);

        $this->normalUser = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'normaluser@example.com',
        ]);
        $this->normalUser->roles()->attach($userRole);

        // Create product
        $this->testProduct = Course::create([
            'name' => 'Test Toy Car',
            'slug' => 'test-toy-car',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'image.jpg',
            'description' => 'Test Description',
        ]);
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/admin/reviews');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/reviews');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_reviews_page()
    {
        $review = Rating::create([
            'reviewer_name' => 'John Doe',
            'rateable_type' => Course::class,
            'rateable_id' => $this->testProduct->id,
            'rating' => 5,
            'comment' => 'Amazing product!',
            'status' => 'approved',
            'is_approved' => true,
            'show_publicly' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/reviews');
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Amazing product!');
    }

    public function test_admin_can_create_review_manually()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/reviews', [
            'product_id' => $this->testProduct->id,
            'user_id' => $this->normalUser->id,
            'reviewer_name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'rating' => 4,
            'comment' => 'Very good toy!',
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('admin.reviews.index'));
        $this->assertDatabaseHas('ratings', [
            'reviewer_name' => 'Jane Smith',
            'rating' => 4,
            'comment' => 'Very good toy!',
            'status' => 'pending',
            'is_approved' => false,
            'show_publicly' => false,
        ]);
    }

    public function test_admin_can_update_review()
    {
        $review = Rating::create([
            'reviewer_name' => 'Old Name',
            'rateable_type' => Course::class,
            'rateable_id' => $this->testProduct->id,
            'rating' => 3,
            'comment' => 'Old comment',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/reviews/{$review->id}", [
            'reviewer_name' => 'Updated Name',
            'rating' => 5,
            'comment' => 'Updated comment',
            'status' => 'approved',
            'moderation_note' => 'Approved manually by admin.',
        ]);

        $response->assertRedirect(route('admin.reviews.index'));
        $this->assertDatabaseHas('ratings', [
            'id' => $review->id,
            'reviewer_name' => 'Updated Name',
            'rating' => 5,
            'comment' => 'Updated comment',
            'status' => 'approved',
            'is_approved' => true,
            'show_publicly' => true,
            'moderation_note' => 'Approved manually by admin.',
        ]);
    }

    public function test_admin_can_approve_review()
    {
        $review = Rating::create([
            'reviewer_name' => 'Status User',
            'rateable_type' => Course::class,
            'rateable_id' => $this->testProduct->id,
            'rating' => 4,
            'comment' => 'Nice',
            'status' => 'pending',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/reviews/{$review->id}/approve");
        $response->assertRedirect();
        
        $this->assertEquals('approved', $review->fresh()->status);
        $this->assertTrue($review->fresh()->is_approved);
    }

    public function test_admin_can_reject_review()
    {
        $review = Rating::create([
            'reviewer_name' => 'Status User',
            'rateable_type' => Course::class,
            'rateable_id' => $this->testProduct->id,
            'rating' => 4,
            'comment' => 'Nice',
            'status' => 'approved',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/reviews/{$review->id}/reject", [
            'moderation_note' => 'Spam comment.',
        ]);
        $response->assertRedirect();

        $this->assertEquals('rejected', $review->fresh()->status);
        $this->assertFalse($review->fresh()->is_approved);
        $this->assertEquals('Spam comment.', $review->fresh()->moderation_note);
    }

    public function test_admin_can_mark_pending()
    {
        $review = Rating::create([
            'reviewer_name' => 'Status User',
            'rateable_type' => Course::class,
            'rateable_id' => $this->testProduct->id,
            'rating' => 4,
            'comment' => 'Nice',
            'status' => 'approved',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/reviews/{$review->id}/pending");
        $response->assertRedirect();

        $this->assertEquals('pending', $review->fresh()->status);
        $this->assertFalse($review->fresh()->is_approved);
    }

    public function test_admin_can_delete_review()
    {
        $review = Rating::create([
            'reviewer_name' => 'Delete User',
            'rateable_type' => Course::class,
            'rateable_id' => $this->testProduct->id,
            'rating' => 4,
            'comment' => 'Delete comment',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/reviews/{$review->id}");
        $response->assertRedirect(route('admin.reviews.index'));

        $this->assertDatabaseMissing('ratings', [
            'id' => $review->id,
        ]);
    }
}
