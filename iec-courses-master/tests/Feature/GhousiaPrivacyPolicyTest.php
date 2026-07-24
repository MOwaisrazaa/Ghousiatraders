<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaPrivacyPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::updateOrCreate(['name' => 'User']);
    }

    public function test_privacy_policy_page_is_accessible()
    {
        $response = $this->get('/privacy-policy');
        $response->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Policy Sections')
            ->assertSee('1. Information We Collect')
            ->assertSee('Last Updated: May 17, 2024')
            ->assertSee('9. Contact Us');
    }
}
