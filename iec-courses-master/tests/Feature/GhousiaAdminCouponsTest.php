<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaAdminCouponsTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;
    protected $testProduct;
    protected $testCategory;

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

        // Create product category
        $this->testCategory = Category::create([
            'name' => 'Toys Category',
            'slug' => 'toys-category',
        ]);

        // Create product
        $this->testProduct = Course::create([
            'name' => 'Test Mercedes Car',
            'slug' => 'test-mercedes-car',
            'category_id' => $this->testCategory->id,
            'weekly_price' => 1000,
            'monthly_price' => 3000,
            'purchase_model' => 'flexible',
            'image_path' => 'image.jpg',
            'description' => 'Nice Toy Car',
        ]);
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/admin/coupons');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/coupons');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_coupons_page()
    {
        $coupon = Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 1000,
            'max_uses' => 100,
            'uses_count' => 0,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDays(5),
            'is_active' => true,
            'description' => 'Welcome coupon description',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/coupons');
        $response->assertStatus(200);
        $response->assertSee('WELCOME10');
        $response->assertSee('Welcome coupon description');
    }

    public function test_admin_can_create_coupon()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/coupons', [
            'code' => 'SAVE500',
            'type' => 'fixed',
            'value' => 500,
            'min_order_amount' => 2000,
            'max_uses' => 50,
            'valid_from' => now()->format('Y-m-d\TH:i'),
            'valid_until' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'is_active' => 1,
            'description' => 'Save PKR 500 on order',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'code' => 'SAVE500',
            'type' => 'fixed',
            'value' => 500,
            'min_order_amount' => 2000,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'DISCOUNT20',
            'type' => 'percentage',
            'value' => 20,
            'valid_from' => now(),
            'valid_until' => now()->addDays(5),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/coupons/{$coupon->id}", [
            'code' => 'DISCOUNT25',
            'type' => 'percentage',
            'value' => 25,
            'valid_from' => now()->format('Y-m-d\TH:i'),
            'valid_until' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'is_active' => 1,
            'description' => 'Updated discount',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'code' => 'DISCOUNT25',
            'value' => 25,
        ]);
    }

    public function test_admin_can_duplicate_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'DUPLICATEME',
            'type' => 'percentage',
            'value' => 15,
            'valid_from' => now(),
            'valid_until' => now()->addDays(5),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/coupons/{$coupon->id}/duplicate");
        $response->assertRedirect(route('admin.coupons.index'));
        
        $this->assertDatabaseHas('coupons', [
            'code' => 'DUPLICATEME_COPY1',
            'value' => 15,
        ]);
    }

    public function test_admin_can_toggle_coupon_status()
    {
        $coupon = Coupon::create([
            'code' => 'TOGGLEME',
            'type' => 'percentage',
            'value' => 10,
            'valid_from' => now(),
            'valid_until' => now()->addDays(5),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/coupons/{$coupon->id}/toggle");
        $response->assertRedirect();
        $this->assertFalse($coupon->fresh()->is_active);
    }

    public function test_admin_can_delete_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'DELETEME',
            'type' => 'percentage',
            'value' => 10,
            'valid_from' => now(),
            'valid_until' => now()->addDays(5),
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/coupons/{$coupon->id}");
        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', [
            'id' => $coupon->id,
        ]);
    }

    public function test_coupon_cart_validation_rules()
    {
        // 1. Minimum order validation
        $couponMinOrder = Coupon::create([
            'code' => 'MIN1000',
            'type' => 'fixed',
            'value' => 100,
            'min_order_amount' => 1000,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDay(),
            'is_active' => true,
        ]);

        // Cart total 500 (lower than 1000 limit)
        $validation = $couponMinOrder->isValidForCart([], 500, $this->normalUser->id);
        $this->assertFalse($validation['valid']);
        $this->assertStringContainsString('Minimum order amount', $validation['error']);

        // Cart total 1500 (higher than 1000 limit)
        $validation2 = $couponMinOrder->isValidForCart([], 1500, $this->normalUser->id);
        $this->assertTrue($validation2['valid']);

        // 2. Excluded product check
        $couponExcluded = Coupon::create([
            'code' => 'EXCLUDEPROD',
            'type' => 'percentage',
            'value' => 10,
            'excluded_products' => [$this->testProduct->id],
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDay(),
            'is_active' => true,
        ]);

        $cartItems = [
            ['course_id' => $this->testProduct->id]
        ];

        $validationExcluded = $couponExcluded->isValidForCart($cartItems, 1000, $this->normalUser->id);
        $this->assertFalse($validationExcluded['valid']);
        $this->assertStringContainsString('cannot be used with some items', $validationExcluded['error']);
    }
}
