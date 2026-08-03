<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Course;

class WishlistTest extends TestCase
{
    public function test_wishlist_persistence_flow()
    {
        $courses = Course::take(4)->get();
        if ($courses->count() < 4) {
            $courses = collect([
                Course::create(['name' => 'Product A', 'slug' => 'product-a', 'weekly_price' => 1000]),
                Course::create(['name' => 'Product B', 'slug' => 'product-b', 'weekly_price' => 2000]),
                Course::create(['name' => 'Product C', 'slug' => 'product-c', 'weekly_price' => 3000]),
                Course::create(['name' => 'Product D', 'slug' => 'product-d', 'weekly_price' => 4000]),
            ]);
        }

        $c0 = $courses[0];
        $c1 = $courses[1];
        $c2 = $courses[2];
        $c3 = $courses[3];

        // 1. Initial state: Add A, B, C, D to wishlist cookie
        $initialCookie = implode(',', [$c0->slug, $c1->slug, $c2->slug, $c3->slug]);
        $response = $this->withUnencryptedCookie('wishlist', rawurlencode($initialCookie))
            ->get(route('polani.wishlist'));

        $response->assertStatus(200);
        $response->assertSee($c0->name);
        $response->assertSee($c1->name);
        $response->assertSee($c2->name);
        $response->assertSee($c3->name);

        // 2. Remove Product B
        $afterRemoveB = implode(',', [$c0->slug, $c2->slug, $c3->slug]);
        $response2 = $this->withUnencryptedCookie('wishlist', rawurlencode($afterRemoveB))
            ->get(route('polani.wishlist'));

        $response2->assertStatus(200);
        $response2->assertSee($c0->name);
        $response2->assertDontSee($c1->name);
        $response2->assertSee($c2->name);
        $response2->assertSee($c3->name);

        // 3. Remove Product A & Product D
        $afterRemoveAD = implode(',', [$c2->slug]);
        $response3 = $this->withUnencryptedCookie('wishlist', rawurlencode($afterRemoveAD))
            ->get(route('polani.wishlist'));

        $response3->assertStatus(200);
        $response3->assertDontSee($c0->name);
        $response3->assertDontSee($c1->name);
        $response3->assertSee($c2->name);
        $response3->assertDontSee($c3->name);

        // 4. Remove Product C -> Empty state
        $response4 = $this->withUnencryptedCookie('wishlist', '')
            ->get(route('polani.wishlist'));

        $response4->assertStatus(200);
        $response4->assertSee('Your wishlist is empty');

        // 5. Add Product B again
        $response5 = $this->withUnencryptedCookie('wishlist', rawurlencode($c1->slug))
            ->get(route('polani.wishlist'));

        $response5->assertStatus(200);
        $response5->assertSee($c1->name);
    }
}
