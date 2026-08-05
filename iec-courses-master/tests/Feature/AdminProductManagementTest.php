<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\Category;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $this->adminUser->roles()->attach($superAdminRole->id);
    }

    /** @test */
    public function dashboard_and_products_page_add_product_buttons_link_to_same_ghousia_create_page()
    {
        $dashboardResponse = $this->actingAs($this->adminUser)->get('/admin');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee(route('admin.products.create'), false);

        $productsResponse = $this->actingAs($this->adminUser)->get('/admin/products');
        $productsResponse->assertStatus(200);
        $productsResponse->assertSee(route('admin.products.create'), false);
    }

    /** @test */
    public function product_creation_page_uses_ghousia_traders_admin_layout()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/products/create');
        $response->assertStatus(200);
        $response->assertSee('Add New Product');
        $response->assertSee('Back to Products');
        $response->assertSee('Ghousia Traders');
    }

    /** @test */
    public function admin_can_store_new_product_successfully()
    {
        $category = Category::create(['name' => 'Baby Toys', 'slug' => 'baby-toys']);

        $productData = [
            'name' => 'Remote Control Ride-On Bike',
            'sku' => 'GT-B-9901',
            'category_id' => $category->id,
            'weekly_price' => 15500,
            'sale_price' => 14000,
            'cost_price' => 11000,
            'stock' => 15,
            'low_stock_threshold' => 3,
            'status' => 'active',
            'is_featured' => '1',
            'description' => 'Rechargeable battery-operated ride-on bike for toddlers.',
            'long_description' => '<p>High performance dual motor bike with music and LED headlights.</p>',
        ];

        $response = $this->actingAs($this->adminUser)->post('/admin/products', $productData);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Remote Control Ride-On Bike',
            'sku' => 'GT-B-9901',
            'weekly_price' => 15500,
            'stock' => 15,
            'status' => 'active',
        ]);
    }
}
