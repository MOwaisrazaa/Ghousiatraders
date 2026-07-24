<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public function test_signup_logout_and_signin_lifecycle()
    {
        // 1. Register a new user
        $response = $this->post('/sign-up', [
            'name' => 'Mohammad Owais',
            'email' => 'Owais@Gmail.com', // test mixed case to check normalization
            'password' => 'Owais123!A',
            'password_confirmation' => 'Owais123!A',
            'phone' => '03170010311',
            'country' => 'PK',
            'terms' => '1'
        ]);

        $response->assertRedirect();
        
        // Assert normalized email stored in DB
        $this->assertDatabaseHas('users', [
            'email' => 'owais@gmail.com',
        ]);

        // Assert logged in after registration
        $this->assertTrue(auth()->check());

        // 2. Log out
        $response = $this->post('/logout');
        $response->assertRedirect('/sign-in');
        $this->assertFalse(auth()->check());

        // 3. Try logging in with wrong credentials
        $response = $this->post('/sign-in', [
            'email' => 'owais@gmail.com',
            'password' => 'wrongpassword'
        ]);
        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(auth()->check());

        // 4. Log in with correct credentials (and check email casing normalization during sign-in)
        $response = $this->post('/sign-in', [
            'email' => '  OWAIS@gmail.com  ', // test spaces and uppercase
            'password' => 'Owais123!A'
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertTrue(auth()->check());
        $this->assertEquals('owais@gmail.com', auth()->user()->email);
    }
}
