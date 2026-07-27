<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GhousiaAdminProductsTest extends TestCase
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
        $response = $this->get('/admin/products');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/products');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_products_page()
    {
        $category = Category::create([
            'name' => 'Toy Category',
            'slug' => 'toy-category',
        ]);

        $product = Course::create([
            'name' => 'Mercedes Toy Car',
            'slug' => 'mercedes-toy-car',
            'sku' => 'GT-M-001',
            'category_id' => $category->id,
            'weekly_price' => 25000,
            'monthly_price' => 25000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 15,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Great car description',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/products?per_page=100');
        $response->assertStatus(200);
        $response->assertSee('Mercedes Toy Car');
        $response->assertSee('GT-M-001');
        $response->assertSee('Toy Category');
    }

    public function test_admin_can_create_product()
    {
        $category = Category::create([
            'name' => 'Toys',
            'slug' => 'toys',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/products', [
            'name' => 'New Premium Toy Car',
            'sku' => 'GT-P-9999',
            'category_id' => $category->id,
            'weekly_price' => 35000,
            'stock' => 20,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'A new premium toy',
            'long_description' => 'Longer detailed description of premium toy',
            'is_featured' => 1,
        ]);

        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'New Premium Toy Car',
            'sku' => 'GT-P-9999',
            'weekly_price' => 35000,
            'is_featured' => 1,
        ]);
    }

    public function test_admin_can_update_product()
    {
        $product = Course::create([
            'name' => 'Update Target Toy',
            'slug' => 'update-target-toy',
            'sku' => 'GT-U-001',
            'weekly_price' => 15000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Initial desc',
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'sku' => 'GT-U-999',
            'weekly_price' => 18000,
            'stock' => 8,
            'low_stock_threshold' => 3,
            'status' => 'draft',
            'description' => 'Updated description text',
            'is_featured' => 0,
        ]);

        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'sku' => 'GT-U-999',
            'weekly_price' => 18000,
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_toggle_featured()
    {
        $product = Course::create([
            'name' => 'Featured Test Toy',
            'slug' => 'featured-test-toy',
            'weekly_price' => 10000,
            'monthly_price' => 10000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'is_featured' => false,
            'description' => 'Desc',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/products/{$product->id}/toggle-featured");
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_toggle_status()
    {
        $product = Course::create([
            'name' => 'Status Toggle Test Toy',
            'slug' => 'status-toggle-test-toy',
            'weekly_price' => 10000,
            'monthly_price' => 10000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Desc',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/products/{$product->id}/toggle-status", [
            'status' => 'draft',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_update_stock()
    {
        $product = Course::create([
            'name' => 'Stock Test Toy',
            'slug' => 'stock-test-toy',
            'weekly_price' => 10000,
            'monthly_price' => 10000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Desc',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/products/{$product->id}/update-stock", [
            'stock' => 45,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 45,
        ]);
    }

    public function test_admin_cannot_delete_product_assigned_to_orders()
    {
        $product = Course::create([
            'name' => 'Assigned Test Toy',
            'slug' => 'assigned-test-toy',
            'weekly_price' => 10000,
            'monthly_price' => 10000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Desc',
        ]);

        $order = Order::create([
            'order_number' => 'GT-ORD-1111',
            'status' => 'processing',
            'payment_status' => 'paid',
            'payment_method' => 'cod',
            'subtotal' => 10000,
            'total' => 10000,
            'final_total' => 10000,
            'billing_address' => json_encode(['first_name' => 'Test', 'last_name' => 'User']),
            'cart_items' => json_encode([['course_id' => $product->id, 'price' => 10000]]),
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/products/{$product->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_admin_can_delete_unreferenced_product()
    {
        $product = Course::create([
            'name' => 'Delete Me Toy',
            'slug' => 'delete-me-toy',
            'weekly_price' => 10000,
            'monthly_price' => 10000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Desc',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/products/{$product->id}");
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_export_products()
    {
        $category = Category::create(['name' => 'Toys', 'slug' => 'toys']);
        $product = Course::create([
            'name' => 'Exportable Toy',
            'slug' => 'exportable-toy',
            'sku' => 'GT-EXP-9',
            'category_id' => $category->id,
            'weekly_price' => 12000,
            'monthly_price' => 12000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'description' => 'Desc',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/products/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('GT-EXP-9', $output);
        $this->assertStringContainsString('Exportable Toy', $output);
    }

    public function test_admin_can_import_products_csv()
    {
        $csvContent = "Product Name,SKU,Category,Price,Sale Price,Cost Price,Stock,Low Stock Threshold,Status,Is Featured,Description\n"
                    . "Imported Teddy,GT-TED-01,Plush Toys,3500,3000,,15,3,active,Yes,A soft teddy toy\n"
                    . "Imported Duck,,Plush Toys,1500,,,50,5,draft,No,A cute yellow duck\n";

        $file = UploadedFile::fake()->createWithContent('products.csv', $csvContent);

        $response = $this->actingAs($this->adminUser)->post('/admin/products/import', [
            'csv_file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Imported Teddy',
            'sku' => 'GT-TED-01',
            'weekly_price' => 3500,
            'stock' => 15,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Imported Duck',
            'weekly_price' => 1500,
            'status' => 'draft',
        ]);
        
        $this->assertDatabaseHas('categories', [
            'name' => 'Plush Toys',
            'slug' => 'plush-toys',
        ]);
    }
}
