<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Category;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaAdminReportsTest extends TestCase
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
            'name' => 'Reports Category',
            'slug' => 'reports-category',
        ]);

        // Create product
        $this->testProduct = Course::create([
            'name' => 'Reports Mercedes Benz AMG',
            'slug' => 'reports-mercedes-amg',
            'category_id' => $this->testCategory->id,
            'weekly_price' => 1000,
            'monthly_price' => 3000,
            'purchase_model' => 'flexible',
            'image_path' => 'image.jpg',
            'description' => 'Cool AMG Toy Car',
        ]);
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/admin/reports');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/reports');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_reports_page()
    {
        Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 3000,
            'final_total' => 3000,
            'status' => 'completed',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $this->testProduct->id, 'price' => 3000, 'quantity' => 1]]),
            'billing_address' => json_encode(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/reports');
        
        $response->assertStatus(200);
        $response->assertSee('Reports');
        $response->assertSee('Total Revenue');
        $response->assertSee('Total Orders');
        // Assert we see some stats calculated from seeded order
        $response->assertSee('PKR 3,000');
    }

    public function test_admin_can_filter_reports()
    {
        // 1. COD Order
        Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 2000,
            'final_total' => 2000,
            'status' => 'completed',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $this->testProduct->id, 'price' => 2000, 'quantity' => 1]]),
            'billing_address' => json_encode(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        // 2. Stripe Order
        Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 5000,
            'final_total' => 5000,
            'status' => 'completed',
            'payment_method' => 'stripe',
            'cart_items' => json_encode([['course_id' => $this->testProduct->id, 'price' => 5000, 'quantity' => 1]]),
            'billing_address' => json_encode(['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        // Filter by payment method stripe
        $response = $this->actingAs($this->adminUser)->get('/admin/reports?payment_method=stripe');
        $response->assertStatus(200);
        $response->assertSee('PKR 5,000');
    }

    public function test_admin_can_export_reports_csv()
    {
        Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 3000,
            'final_total' => 3000,
            'status' => 'completed',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $this->testProduct->id, 'price' => 3000, 'quantity' => 1]]),
            'billing_address' => json_encode(['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/reports/export?export_format=csv');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('Ghousia Traders Store Performance Report', $output);
        $this->assertStringContainsString('Total Revenue (PKR)', $output);
    }

    public function test_admin_can_export_reports_print()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/reports/export?export_format=pdf');
        $response->assertStatus(200);
        $response->assertSee('Ghousia Traders Store Performance Report');
        $response->assertSee('Executive Summary');
    }
}
