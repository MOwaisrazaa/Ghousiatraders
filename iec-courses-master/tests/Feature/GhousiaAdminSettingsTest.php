<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Models\FooterSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GhousiaAdminSettingsTest extends TestCase
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
            'name' => 'Admin Settings Test',
            'email' => 'adminsettings@example.com',
        ]);
        $this->adminUser->roles()->attach($adminRole);

        $this->normalUser = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'normalsettingsuser@example.com',
        ]);
        $this->normalUser->roles()->attach($userRole);
    }

    public function test_guest_is_redirected_to_sign_in()
    {
        $response = $this->get('/admin/settings');
        $response->assertRedirect(route('sign-in'));
    }

    public function test_customer_is_denied_access()
    {
        $response = $this->actingAs($this->normalUser)->get('/admin/settings');
        $response->assertRedirect();
        $this->assertTrue(session()->has('error'));
    }

    public function test_admin_can_access_settings_page()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Settings');
        $response->assertSee('General Settings');
        $response->assertSee('Store Name');
    }

    public function test_admin_can_update_general_settings()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'tab' => 'general',
            'store_name' => 'Super Ghousia Traders',
            'store_email' => 'super@ghousia.com',
            'store_phone' => '+92 300 9876543',
            'store_currency' => 'PKR',
            'store_timezone' => 'Asia/Karachi',
            'date_format' => 'F d, Y',
            'time_format' => '12h',
            'items_per_page' => 50,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Super Ghousia Traders', Setting::get('store_name'));
        $this->assertEquals('super@ghousia.com', Setting::get('store_email'));
        
        // Assert sync with FooterSetting
        $footer = FooterSetting::getSettings();
        $this->assertEquals('Super Ghousia Traders', $footer->brand_name);
        $this->assertEquals('super@ghousia.com', $footer->email);
    }

    public function test_admin_can_update_store_address()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'tab' => 'address',
            'address_line_1' => 'Plot 45, Sector G',
            'address_line_2' => 'Korangi Industrial Area',
            'city' => 'Karachi',
            'state' => 'Sindh',
            'country' => 'Pakistan',
            'postal_code' => '74900',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Plot 45, Sector G', Setting::get('address_line_1'));
        
        // Assert sync with FooterSetting address concatenation
        $footer = FooterSetting::getSettings();
        $this->assertStringContainsString('Plot 45, Sector G', $footer->address);
        $this->assertStringContainsString('Karachi', $footer->address);
    }

    public function test_admin_can_toggle_two_factor_auth()
    {
        $this->assertFalse(Setting::get('two_factor_enabled', false));

        $response = $this->actingAs($this->adminUser)->post('/admin/settings/security', [
            'security_action' => 'toggle_2fa',
        ]);

        $response->assertRedirect();
        $this->assertTrue((bool) Setting::get('two_factor_enabled'));
    }

    public function test_admin_can_configure_smtp()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings/smtp', [
            'from_name' => 'Ghousia Sender',
            'from_email' => 'sender@ghousia.com',
            'mail_driver' => 'smtp',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => '2525',
            'smtp_encryption' => 'tls',
            'smtp_username' => 'test_user_smtp',
            'smtp_password' => 'secret_pass_123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('smtp.mailtrap.io', Setting::get('smtp_host'));
        $this->assertEquals('test_user_smtp', Setting::get('smtp_username'));
        
        // Password should be encrypted
        $rawPassword = decrypt(Setting::get('smtp_password'));
        $this->assertEquals('secret_pass_123', $rawPassword);
    }

    public function test_admin_can_update_appearance()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'tab' => 'appearance',
            'theme' => 'dark',
            'primary_color' => 'blue',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('dark', Setting::get('theme'));
        $this->assertEquals('blue', Setting::get('primary_color'));
    }
}
