<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GhousiaAdminCategoriesTest extends TestCase
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
        $response = $this->get('/admin/categories');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/categories');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_categories_page()
    {
        $category = Category::create([
            'name' => 'Test Main Category',
            'slug' => 'test-main-category',
            'description' => 'Main desc',
            'status' => 'active',
        ]);

        $subCategory = Category::create([
            'name' => 'Test Sub Category',
            'slug' => 'test-sub-category',
            'description' => 'Sub desc',
            'parent_id' => $category->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Categories');
        $response->assertSee('Total Categories');
        $response->assertSee('Test Main Category');
        $response->assertSee('Test Sub Category');
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/categories', [
            'name' => 'New Category',
            'slug' => 'new-category',
            'description' => 'New category description',
            'status' => 'active',
            'display_order' => 5,
            'seo_title' => 'SEO Title',
            'meta_description' => 'SEO Meta description',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
            'slug' => 'new-category',
            'status' => 'active',
            'display_order' => 5,
        ]);
    }

    public function test_admin_cannot_set_self_as_parent()
    {
        $category = Category::create([
            'name' => 'Self Loop Category',
            'slug' => 'self-loop-category',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->put("/admin/categories/{$category->id}", [
            'name' => 'Self Loop Category',
            'slug' => 'self-loop-category',
            'status' => 'active',
            'parent_id' => $category->id,
        ]);

        $response->assertSessionHasErrors(['parent_id']);
    }

    public function test_admin_cannot_set_descendant_as_parent()
    {
        $parent = Category::create([
            'name' => 'Grandparent Category',
            'slug' => 'grandparent-category',
            'status' => 'active',
        ]);

        $child = Category::create([
            'name' => 'Parent Category',
            'slug' => 'parent-category',
            'parent_id' => $parent->id,
            'status' => 'active',
        ]);

        $grandchild = Category::create([
            'name' => 'Child Category',
            'slug' => 'child-category',
            'parent_id' => $child->id,
            'status' => 'active',
        ]);

        // Try to set grandparent's parent to child (grandparent -> child -> grandchild -> grandparent)
        $response = $this->actingAs($this->adminUser)->put("/admin/categories/{$parent->id}", [
            'name' => 'Grandparent Category',
            'slug' => 'grandparent-category',
            'status' => 'active',
            'parent_id' => $grandchild->id,
        ]);

        $response->assertSessionHasErrors(['parent_id']);
    }

    public function test_admin_can_toggle_status()
    {
        $category = Category::create([
            'name' => 'Toggle Status Category',
            'slug' => 'toggle-status-category',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/categories/{$category->id}/toggle-status", [
            'status' => 'hidden',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'status' => 'hidden',
        ]);
    }

    public function test_admin_can_reorder_categories()
    {
        $cat1 = Category::create([
            'name' => 'Cat 1',
            'slug' => 'cat-1',
            'display_order' => 1,
            'status' => 'active',
        ]);

        $cat2 = Category::create([
            'name' => 'Cat 2',
            'slug' => 'cat-2',
            'display_order' => 2,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/categories/reorder', [
            'orders' => [
                ['id' => $cat1->id, 'display_order' => 10],
                ['id' => $cat2->id, 'display_order' => 20],
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('categories', [
            'id' => $cat1->id,
            'display_order' => 10,
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $cat2->id,
            'display_order' => 20,
        ]);
    }

    public function test_admin_cannot_delete_category_with_products()
    {
        $category = Category::create([
            'name' => 'Has Products Category',
            'slug' => 'has-products-category',
            'status' => 'active',
        ]);

        $course = Course::create([
            'name' => 'Assigned Toy',
            'slug' => 'assigned-toy',
            'category_id' => $category->id,
            'description' => 'Assigned toy description',
            'weekly_price' => 5000,
            'monthly_price' => 15000,
            'purchase_model' => 'flexible',
            'image_path' => 'image.png',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/categories/{$category->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_subcategories()
    {
        $parent = Category::create([
            'name' => 'Has Sub Category',
            'slug' => 'has-sub-category',
            'status' => 'active',
        ]);

        $child = Category::create([
            'name' => 'Child Sub',
            'slug' => 'child-sub',
            'parent_id' => $parent->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/categories/{$parent->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $parent->id]);
    }

    public function test_admin_can_delete_empty_category()
    {
        $category = Category::create([
            'name' => 'Empty Category',
            'slug' => 'empty-category',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/categories/{$category->id}");
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
