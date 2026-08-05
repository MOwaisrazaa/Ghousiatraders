<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Course;
use App\Models\Category;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_detail_page_loads_successfully_without_fragrance_notes_error()
    {
        $category = Category::create([
            'slug' => 'test-baby-care-cat',
            'name' => 'Test Baby Care',
        ]);

        $product = Course::create([
            'slug' => 'test-diaper-rash-cream',
            'sku' => 'GT-TEST-999',
            'name' => 'Test Diaper Cream 100g',
            'category_id' => $category->id,
            'description' => 'Gentle diaper rash protection cream for babies.',
            'long_description' => '<p>Zinc oxide protective barrier cream for babies.</p>',
            'weekly_price' => 650,
            'monthly_price' => 650,
            'sale_price' => 590,
            'stock' => 20,
            'low_stock_threshold' => 5,
            'status' => 'active',
            'is_featured' => true,
            'image_path' => 'ghousiatraders/assets/diaper_cream.png',
        ]);

        $response = $this->get('/product/test-diaper-rash-cream');

        $response->assertStatus(200);
        $response->assertSee('Test Diaper Cream 100g');
        $response->assertSee('Test Baby Care');
        $response->assertSee('PKR 590');
        $response->assertSee('In Stock');
        $response->assertSee('Add to Cart');
        $response->assertSee('Add to Wishlist');
        $response->assertSee('Zinc oxide protective barrier cream');

        // Verify fragrance notes and Polani terms are completely absent
        $response->assertDontSee('Top Notes');
        $response->assertDontSee('Heart Notes');
        $response->assertDontSee('Base Notes');
        $response->assertDontSee('About This Fragrance');
    }
}
