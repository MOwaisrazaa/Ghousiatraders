<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GhousiaAdminOrdersTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;

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
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/admin/orders');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/orders');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_orders_page()
    {
        $course = Course::create([
            'name' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'Desc',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'path.jpg',
        ]);

        Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 5000,
            'final_total' => 5000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $course->id, 'price' => 5000]]),
            'billing_address' => json_encode([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567',
                'address' => 'Test Address',
                'city' => 'Lahore',
                'country' => 'Pakistan'
            ])
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('Orders');
        $response->assertSee('Total Orders');
        $response->assertSee('John Doe');
    }

    public function test_admin_can_filter_orders()
    {
        $course = Course::create([
            'name' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'Desc',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'path.jpg',
        ]);
        
        $order1 = Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 5000,
            'final_total' => 5000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $course->id, 'price' => 5000]]),
            'billing_address' => json_encode(['first_name' => 'First', 'last_name' => 'User', 'email' => 'first@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        $order2 = Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 10000,
            'final_total' => 10000,
            'status' => 'paid',
            'payment_method' => 'easypaisa',
            'cart_items' => json_encode([['course_id' => $course->id, 'price' => 10000]]),
            'billing_address' => json_encode(['first_name' => 'Second', 'last_name' => 'User', 'email' => 'second@example.com', 'phone' => '67890', 'address' => 'Addr', 'city' => 'Khi'])
        ]);

        // Filter by Search
        $response = $this->actingAs($this->adminUser)->get('/admin/orders?search=First');
        $response->assertStatus(200);
        $response->assertSee('First User');
        $response->assertDontSee('Second User');

        // Filter by Status
        $response = $this->actingAs($this->adminUser)->get('/admin/orders?status=processing');
        $response->assertStatus(200);
        $response->assertSee('Second User');
        $response->assertDontSee('First User');

        // Filter by Payment method
        $response = $this->actingAs($this->adminUser)->get('/admin/orders?payment_method=easypaisa');
        $response->assertStatus(200);
        $response->assertSee('Second User');
        $response->assertDontSee('First User');
    }

    public function test_admin_can_export_orders()
    {
        $course = Course::create([
            'name' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'Desc',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'path.jpg',
        ]);
        
        Order::create([
            'user_id' => null,
            'total' => 5000,
            'final_total' => 5000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $course->id, 'price' => 5000]]),
            'billing_address' => json_encode(['first_name' => 'Export', 'last_name' => 'User', 'email' => 'export@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/orders/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Export User', $response->streamedContent());
    }

    public function test_admin_can_create_order()
    {
        $course = Course::create([
            'name' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'Desc',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'path.jpg',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/orders/store', [
            'user_id' => $this->normalUser->id,
            'course_id' => $course->id,
            'payment_method' => 'cod',
            'status' => 'pending',
            'total' => 5000,
            'discount' => 1000,
            'first_name' => 'Created',
            'last_name' => 'Order',
            'email' => 'created@example.com',
            'phone' => '03001234567',
            'address' => 'Plot 123',
            'city' => 'Karachi'
        ]);

        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->normalUser->id,
            'total' => 5000,
            'discount' => 1000,
            'final_total' => 4000,
            'status' => 'pending',
            'payment_method' => 'cod'
        ]);
    }

    public function test_admin_can_duplicate_order()
    {
        $course = Course::create([
            'name' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'Desc',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'path.jpg',
        ]);
        
        $order = Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 5000,
            'final_total' => 5000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $course->id, 'price' => 5000]]),
            'billing_address' => json_encode(['first_name' => 'Duplicate', 'last_name' => 'User', 'email' => 'dup@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/order/{$order->id}/duplicate");
        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('success');
        
        $this->assertEquals(2, Order::where('user_id', $this->normalUser->id)->count());
    }

    public function test_admin_can_delete_order()
    {
        $course = Course::create([
            'name' => 'Course 1',
            'slug' => 'course-1',
            'description' => 'Desc',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'path.jpg',
        ]);
        
        $order = Order::create([
            'user_id' => $this->normalUser->id,
            'total' => 5000,
            'final_total' => 5000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'cart_items' => json_encode([['course_id' => $course->id, 'price' => 5000]]),
            'billing_address' => json_encode(['first_name' => 'Delete', 'last_name' => 'User', 'email' => 'del@example.com', 'phone' => '12345', 'address' => 'Addr', 'city' => 'Lhr'])
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/order/{$order->id}/delete");
        $response->assertRedirect(route('admin.orders'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
