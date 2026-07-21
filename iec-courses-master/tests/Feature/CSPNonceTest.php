<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CSPNonceTest extends TestCase
{
    /**
     * Test that CSP headers are properly configured with nonces.
     *
     * @return void
     */
    public function test_csp_headers_have_nonces_and_no_unsafe_inline()
    {
        $response = $this->get('/signin');
        
        $response->assertStatus(200);
        
        // Check that CSP header is present
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Check that nonce is included in script-src-elem and style-src-elem
        $this->assertMatchesRegularExpression("/script-src-elem [^;]*'nonce-[A-Za-z0-9+\/=]+'/", $cspHeader);
        $this->assertMatchesRegularExpression("/style-src-elem [^;]*'nonce-[A-Za-z0-9+\/=]+'/", $cspHeader);
        
        // Check that unsafe-inline is not present in script-src-elem and style-src-elem
        // when nonce is present (as it would be ignored anyway)
        if (preg_match("/script-src-elem [^;]+'nonce-[A-Za-z0-9+\/=]+'/", $cspHeader)) {
            $this->assertStringNotContainsString("script-src-elem 'unsafe-inline'", $cspHeader);
        }
        
        if (preg_match("/style-src-elem [^;]+'nonce-[A-Za-z0-9+\/=]+'/", $cspHeader)) {
            $this->assertStringNotContainsString("style-src-elem 'unsafe-inline'", $cspHeader);
        }
        
        // Check that the page content includes our CSP fix scripts
        $content = $response->getContent();
        $this->assertStringContainsString('csp-early-patch.js', $content);
        $this->assertStringContainsString('csp-global-fix.js', $content);
        $this->assertStringContainsString('signin-page-csp-fix.js', $content);
    }
}