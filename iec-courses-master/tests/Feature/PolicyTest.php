<?php

namespace Tests\Feature;

use Tests\TestCase;

class PolicyTest extends TestCase
{
    public function test_shipping_returns_page_loads()
    {
        $response = $this->get(route('polani.shipping-returns'));
        $response->assertStatus(200);
        $response->assertSee('Return &amp; Shipping Policy', false);
        $response->assertSee('1. Shipping Coverage');
        $response->assertSee('13. Contact Us');
    }

    public function test_legacy_policy_urls_redirect()
    {
        $response1 = $this->get('/shipping-delivery');
        $response1->assertRedirect('/shipping-returns');

        $response2 = $this->get('/returns-refunds');
        $response2->assertRedirect('/shipping-returns');

        $response3 = $this->get('/shipping-policy');
        $response3->assertRedirect('/shipping-returns');

        $response4 = $this->get('/returns-exchanges');
        $response4->assertRedirect('/shipping-returns');
    }
}
