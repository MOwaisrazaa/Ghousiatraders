<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaSignupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure User role exists
        Role::updateOrCreate(['name' => 'User']);
    }

    public function test_signup_page_is_accessible()
    {
        $response = $this->get('/sign-up');
        $response->assertOk()
            ->assertSee('Sign Up')
            ->assertSee('Full Name');
    }

    public function test_signup_validation_errors()
    {
        // 1. Submit empty form
        $response = $this->post('/sign-up', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'phone' => '',
            'country' => 'PK',
            'terms' => '0'
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'phone', 'terms']);
    }

    public function test_signup_password_regex_validation()
    {
        // Password must contain uppercase, lowercase, digit, and special char, min 6, max 15
        $response = $this->post('/sign-up', [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => 'simple', // invalid length, no uppercase, no digit, no special
            'password_confirmation' => 'simple',
            'phone' => '03126788631',
            'country' => 'PK',
            'terms' => '1'
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_signup_successful_registration()
    {
        $response = $this->post('/sign-up', [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'phone' => '03126788631',
            'country' => 'PK',
            'terms' => '1'
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // Assert user was created in the database and phone was normalized
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'phone' => '923126788631' // 0 is removed and 92 is prepended
        ]);

        // Assert user is logged in
        $this->assertTrue(auth()->check());
        $this->assertEquals('johndoe@gmail.com', auth()->user()->email);
    }
}
