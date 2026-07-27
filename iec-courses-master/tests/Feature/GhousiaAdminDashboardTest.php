<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the roles
        $adminRole = Role::updateOrCreate(['name' => 'Admin']);
        $userRole = Role::updateOrCreate(['name' => 'User']);

        // Create an Admin user
        $this->adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admintest@example.com',
        ]);
        $this->adminUser->roles()->attach($adminRole);

        // Create a normal User
        $this->normalUser = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'normaluser@example.com',
        ]);
        $this->normalUser->roles()->attach($userRole);
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Welcome back, Admin!')
            ->assertSee('Total Sales')
            ->assertSee('Total Orders')
            ->assertSee('Total Customers')
            ->assertSee('Total Products')
            ->assertSee('Avg. Order Value')
            ->assertSee('Best-Selling Products')
            ->assertSee('Quick Actions')
            ->assertSee('Low Stock Products');
    }

    public function test_dashboard_filters_by_date_range()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin?start_date=2024-05-12&end_date=2024-05-18');
        
        $response->assertOk()
            ->assertSee('2024-05-12')
            ->assertSee('2024-05-18');
    }

    public function test_dashboard_global_search()
    {
        // Create dummy product/course
        $product = Course::create([
            'name' => 'Exclusive Baby Toy',
            'slug' => 'exclusive-baby-toy',
            'description' => 'A luxury toy for babies',
            'weekly_price' => 2000,
            'monthly_price' => 7000,
            'purchase_model' => 'flexible',
            'image_path' => 'polani/assets/product-toy.jpg',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get('/admin?search=Exclusive');
        
        $response->assertOk()
            ->assertSee('Exclusive Baby Toy');
    }
}
