<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSecurityPolicyTest extends TestCase
{
    /**
     * Test that CSP headers are applied to responses.
     *
     * @return void
     */
    public function test_csp_headers_are_applied()
    {
        $response = $this->get('/');
        
        $response->assertStatus(302); // Redirects to dashboard
        
        // Follow the redirect
        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
        
        // Check that CSP header is present
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Verify that the CSP header contains expected directives
        $this->assertStringContainsString("default-src 'self'", $cspHeader);
        $this->assertStringContainsString("script-src", $cspHeader);
        $this->assertStringContainsString("style-src", $cspHeader);
        $this->assertStringContainsString("object-src 'none'", $cspHeader);
    }

    /**
     * Test that CSP headers do not contain unsafe directives in production.
     *
     * @return void
     */
    public function test_production_csp_has_no_unsafe_directives()
    {
        // Set environment to production
        config(['app.env' => 'production']);
        
        $response = $this->get('/dashboard');
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Verify that unsafe directives are NOT present in production
        $this->assertStringNotContainsString("'unsafe-inline'", $cspHeader);
        $this->assertStringNotContainsString("'unsafe-eval'", $cspHeader);
        
        // Verify that nonce is present
        $this->assertStringContainsString("'nonce-", $cspHeader);
    }

    /**
     * Test that nonce is generated and included in CSP header.
     *
     * @return void
     */
    public function test_nonce_is_generated_and_included()
    {
        $response = $this->get('/dashboard');
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Check that nonce is present in the header
        $this->assertMatchesRegularExpression("/'nonce-[A-Za-z0-9_-]+'/", $cspHeader);
    }

    /**
     * Test that CSP reporting endpoint exists.
     *
     * @return void
     */
    public function test_csp_reporting_endpoint_exists()
    {
        $response = $this->postJson('/api/csp-report', [
            'csp-report' => [
                'document-uri' => 'https://example.com/test',
                'violated-directive' => 'script-src',
                'blocked-uri' => 'https://evil.com/script.js',
            ]
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok']);
    }
    
    /**
     * Test that script-src-elem directive is properly configured.
     *
     * @return void
     */
    public function test_script_src_elem_directive_is_properly_configured()
    {
        $response = $this->get('/dashboard');
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Check that script-src-elem directive is present
        $this->assertStringContainsString("script-src-elem", $cspHeader);
        
        // Check that nonce is included in script-src-elem
        $this->assertMatchesRegularExpression("/script-src-elem [^;]*'nonce-[A-Za-z0-9_-]+'/", $cspHeader);
    }
    
    /**
     * Test that style-src-elem directive is properly configured.
     *
     * @return void
     */
    public function test_style_src_elem_directive_is_properly_configured()
    {
        $response = $this->get('/dashboard');
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Check that style-src-elem directive is present
        $this->assertStringContainsString("style-src-elem", $cspHeader);
        
        // Check that nonce is included in style-src-elem
        $this->assertMatchesRegularExpression("/style-src-elem [^;]*'nonce-[A-Za-z0-9_-]+'/", $cspHeader);
    }
    
    /**
     * Test that sign-in page has proper CSP headers and no violations.
     *
     * @return void
     */
    public function test_signin_page_has_proper_csp_headers()
    {
        $response = $this->get('/signin');
        
        // Check that the page loads successfully
        $response->assertStatus(200);
        
        // Check that CSP header is present
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
        
        $cspHeader = $response->headers->get('Content-Security-Policy');
        
        // Check that nonce is included in script-src-elem and style-src-elem
        $this->assertMatchesRegularExpression("/script-src-elem [^;]*'nonce-[A-Za-z0-9_-]+'/", $cspHeader);
        $this->assertMatchesRegularExpression("/style-src-elem [^;]*'nonce-[A-Za-z0-9_-]+'/", $cspHeader);
        
        // Check that the page content includes our CSP fix scripts
        $content = $response->getContent();
        $this->assertStringContainsString('csp-early-patch.js', $content);
        $this->assertStringContainsString('csp-global-fix.js', $content);
        $this->assertStringContainsString('signin-page-csp-fix.js', $content);
        $this->assertStringContainsString('csp-compliant-styles.css', $content);
    }
}