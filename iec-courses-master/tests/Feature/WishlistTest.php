<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Course;

class WishlistTest extends TestCase
{
    public function test_wishlist_persistence_flow()
    {
        $prodA = 'johnsons-baby-lotion-500ml';
        $prodB = 'baby-wipes-80-pcs';
        $prodC = 'premium-sippy-cup';
        $prodD = 'feeding-bottle-set';

        // 1. Initial state: Add A, B, C, D to wishlist cookie
        $initialCookie = implode(',', [$prodA, $prodB, $prodC, $prodD]);
        $response = $this->withUnencryptedCookie('wishlist', rawurlencode($initialCookie))
            ->get(route('polani.wishlist'));

        $response->assertStatus(200);
        $response->assertSee('Johnson’s Baby Lotion 500ml');
        $response->assertSee('Baby Wipes 80 Pcs');
        $response->assertSee('Premium Sippy Cup');
        $response->assertSee('Feeding Bottle Set');

        // 2. Remove Product B
        $afterRemoveB = implode(',', [$prodA, $prodC, $prodD]);
        $response2 = $this->withUnencryptedCookie('wishlist', rawurlencode($afterRemoveB))
            ->get(route('polani.wishlist'));

        $response2->assertStatus(200);
        $response2->assertSee('Johnson’s Baby Lotion 500ml');
        $response2->assertDontSee('Baby Wipes 80 Pcs');
        $response2->assertSee('Premium Sippy Cup');
        $response2->assertSee('Feeding Bottle Set');

        // 3. Remove Product A & Product D
        $afterRemoveAD = implode(',', [$prodC]);
        $response3 = $this->withUnencryptedCookie('wishlist', rawurlencode($afterRemoveAD))
            ->get(route('polani.wishlist'));

        $response3->assertStatus(200);
        $response3->assertDontSee('Johnson’s Baby Lotion 500ml');
        $response3->assertDontSee('Baby Wipes 80 Pcs');
        $response3->assertSee('Premium Sippy Cup');
        $response3->assertDontSee('Feeding Bottle Set');

        // 4. Remove Product C -> Empty state
        $response4 = $this->withUnencryptedCookie('wishlist', '')
            ->get(route('polani.wishlist'));

        $response4->assertStatus(200);
        $response4->assertSee('Your wishlist is empty');

        // 5. Add Product B again
        $response5 = $this->withUnencryptedCookie('wishlist', rawurlencode($prodB))
            ->get(route('polani.wishlist'));

        $response5->assertStatus(200);
        $response5->assertSee('Baby Wipes 80 Pcs');
    }
}
