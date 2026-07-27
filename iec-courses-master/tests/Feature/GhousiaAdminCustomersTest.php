<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaAdminCustomersTest extends TestCase
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
        $response = $this->get('/admin/customers');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/customers');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_customers_page()
    {
        $customer = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '0300-1234567',
            'status' => 'active',
            'group' => 'regular',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/customers');
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('johndoe@example.com');
    }

    public function test_admin_can_create_customer()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/customers', [
            'name' => 'Jane Smith',
            'email' => 'JANESMITH@example.com', // Test trim and lowercase conversion
            'phone' => '0312-3456789',
            'password' => 'secret123',
            'status' => 'active',
            'group' => 'new',
            'shipping_address' => 'Shipping Address',
            'billing_address' => 'Billing Address',
            'notes' => 'Some administrator notes',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'status' => 'active',
            'group' => 'new',
            'shipping_address' => 'Shipping Address',
            'billing_address' => 'Billing Address',
            'notes' => 'Some administrator notes',
        ]);
    }

    public function test_admin_can_update_customer()
    {
        $customer = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'status' => 'active',
            'group' => 'regular',
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/customers/{$customer->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '0321-7654321',
            'status' => 'inactive',
            'group' => 'vip',
            'shipping_address' => 'New Ship',
            'billing_address' => 'New Bill',
            'notes' => 'New notes',
        ]);

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'inactive',
            'group' => 'vip',
        ]);
    }

    public function test_admin_can_toggle_status()
    {
        $customer = User::factory()->create([
            'name' => 'Status User',
            'email' => 'status@example.com',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/customers/{$customer->id}/toggle-status", [
            'status' => 'inactive',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('inactive', $customer->fresh()->status);
    }

    public function test_admin_cannot_delete_customer_with_orders()
    {
        $customer = User::factory()->create([
            'name' => 'Active Buyer',
            'email' => 'buyer@example.com',
        ]);

        // Create order for customer
        Order::create([
            'order_number' => 'GT-ORDER-999',
            'user_id' => $customer->id,
            'status' => 'processing',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'subtotal' => 5000,
            'shipping_charges' => 200,
            'discount' => 0,
            'total' => 5000,
            'final_total' => 5200,
            'billing_address' => json_encode(['email' => 'buyer@example.com']),
            'cart_items' => json_encode([['course_id' => 1, 'price' => 5000]]),
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/customers/{$customer->id}");
        $response->assertRedirect(route('admin.customers.index'));
        
        // Assert user still exists and status is inactive
        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_delete_customer_without_orders()
    {
        $customer = User::factory()->create([
            'name' => 'No Orders User',
            'email' => 'noorders@example.com',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/customers/{$customer->id}");
        $response->assertRedirect(route('admin.customers.index'));
        
        $this->assertDatabaseMissing('users', [
            'id' => $customer->id,
        ]);
    }

    public function test_admin_can_export_customers()
    {
        $customer = User::factory()->create([
            'name' => 'Exportable Customer',
            'email' => 'exportable@example.com',
            'phone' => '0333-1122334',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/customers/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('Exportable Customer', $output);
        $this->assertStringContainsString('exportable@example.com', $output);
        $this->assertStringContainsString('0333-1122334', $output);
    }
}
