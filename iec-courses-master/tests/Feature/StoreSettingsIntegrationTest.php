<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Services\StoreSettingsService;

class StoreSettingsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $this->adminUser->roles()->attach($superAdminRole->id);
    }

    /** @test */
    public function store_setting_helper_returns_default_values()
    {
        $this->assertEquals('Ghousia Traders', store_setting('store_name'));
        $this->assertEquals('0321-1234567', store_setting('store_phone'));
        $this->assertEquals('info@ghousiatraders.com', store_setting('store_email'));
        $this->assertEquals('5000', store_setting('shipping_free_threshold'));
    }

    /** @test */
    public function admin_can_update_general_and_store_info_settings()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'tab' => 'general',
            'store_name' => 'Ghousia Traders Flagship',
            'store_email' => 'contact@ghousiaflagship.com',
            'store_phone' => '0300-9998877',
            'store_currency' => 'PKR',
            'store_timezone' => 'Asia/Karachi',
            'date_format' => 'F d, Y',
            'time_format' => '12h',
            'items_per_page' => '25',
        ]);

        $response->assertRedirect('/admin/settings?tab=general');
        $this->assertEquals('Ghousia Traders Flagship', store_setting('store_name'));
        $this->assertEquals('contact@ghousiaflagship.com', store_setting('store_email'));
        $this->assertEquals('0300-9998877', store_setting('store_phone'));
    }

    /** @test */
    public function admin_can_update_header_and_shipping_settings()
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'tab' => 'header',
            'topbar_free_shipping_text' => 'Free Express Shipping Over PKR 7,000',
            'shipping_free_threshold' => '7000',
            'topbar_quality_text' => 'Guaranteed Original Toys',
            'topbar_support_text' => 'Help Line: 0300-1112233',
            'header_support_phone' => '0300-1112233',
            'track_order_btn_label' => 'Track Your Parcel',
            'header_search_placeholder' => 'Search products...',
            'show_top_info_bar' => '1',
        ]);

        $response->assertRedirect('/admin/settings?tab=header');
        $this->assertEquals('Free Express Shipping Over PKR 7,000', store_setting('topbar_free_shipping_text'));
        $this->assertEquals('7000', store_setting('shipping_free_threshold'));
    }

    /** @test */
    public function storefront_header_and_footer_display_updated_settings()
    {
        StoreSettingsService::setMultiple([
            'public_store_name' => 'Ghousia Traders Store',
            'store_phone' => '0333-7778899',
            'header_support_phone' => '0333-7778899',
            'footer_phone' => '0333-7778899',
            'store_email' => 'support@ghousiatraders.com',
            'copyright_name' => 'Ghousia Traders Enterprise',
            'topbar_free_shipping_text' => 'Free Nationwide Delivery Above PKR 6,000',
            'facebook_enabled' => '1',
            'facebook_url' => 'https://facebook.com/gtstore',
            'twitter_enabled' => '0',
            'twitter_url' => 'https://twitter.com/gtstore',
        ]);

        $pageResponse = $this->get('/');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Ghousia Traders Store');
        $pageResponse->assertSee('0333-7778899');
        $pageResponse->assertSee('support@ghousiatraders.com');
        $pageResponse->assertSee('Ghousia Traders Enterprise');
        $pageResponse->assertSee('https://facebook.com/gtstore');
        $pageResponse->assertDontSee('https://twitter.com/gtstore');
    }

    /** @test */
    public function contact_page_and_policies_use_dynamic_store_settings()
    {
        StoreSettingsService::setMultiple([
            'primary_phone' => '0345-0001122',
            'footer_phone' => '0345-0001122',
            'support_email' => 'helpdesk@ghousia.com',
            'footer_email' => 'helpdesk@ghousia.com',
            'footer_address' => 'Building 45, Commercial Plaza, Lahore',
        ]);

        $contactResponse = $this->get('/contact');
        $contactResponse->assertStatus(200);
        $contactResponse->assertSee('0345-0001122');
        $contactResponse->assertSee('helpdesk@ghousia.com');
        $contactResponse->assertSee('Building 45, Commercial Plaza, Lahore');

        $policyResponse = $this->get('/shipping-returns');
        $policyResponse->assertStatus(200);
        $policyResponse->assertSee('0345-0001122');
        $policyResponse->assertSee('helpdesk@ghousia.com');

        $privacyResponse = $this->get('/privacy-policy');
        $privacyResponse->assertStatus(200);
        $privacyResponse->assertSee('0345-0001122');
        $privacyResponse->assertSee('helpdesk@ghousia.com');
    }

    /** @test */
    public function multiline_business_hours_does_not_contain_literal_entity_encoded_newlines()
    {
        $multilineText = "Monday - Saturday: 10:00 AM - 10:00 PM\r\nSunday: Closed";
        
        $this->actingAs($this->adminUser)->post('/admin/settings', [
            'tab' => 'footer',
            'footer_business_hours' => $multilineText,
            'show_payment_logos' => '1',
        ]);

        $this->assertDatabaseMissing('settings', [
            'key' => 'footer_business_hours',
            'value' => "Monday - Saturday: 10:00 AM - 10:00 PM&#13;&#10;Sunday: Closed"
        ]);

        $hours = store_setting('footer_business_hours');
        $this->assertStringNotContainsString('&#13;&#10;', $hours);
        $this->assertStringContainsString("Monday - Saturday: 10:00 AM - 10:00 PM\nSunday: Closed", $hours);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('&#13;&#10;');
        $response->assertSee('Monday - Saturday: 10:00 AM - 10:00 PM');
        $response->assertSee('Sunday: Closed');
    }

    /** @test */
    public function footer_payment_badges_toggle_and_render_active_methods()
    {
        \App\Models\PaymentMethod::updateOrCreate(
            ['key' => 'cash'],
            ['name' => 'Cash Payment', 'icon' => 'fas fa-money-bill-wave', 'is_active' => true, 'sort_order' => 1]
        );
        \App\Models\PaymentMethod::updateOrCreate(
            ['key' => 'easypaisa'],
            ['name' => 'Easypaisa', 'icon' => 'fas fa-wallet', 'is_active' => true, 'sort_order' => 2]
        );

        StoreSettingsService::set('show_payment_logos', '1');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Cash Payment');
        $response->assertSee('Easypaisa');

        // Turn off show_payment_logos
        StoreSettingsService::set('show_payment_logos', '0');

        $responseDisabled = $this->get('/');
        $responseDisabled->assertStatus(200);
        $responseDisabled->assertDontSee('footer-payment-section');
    }
}
