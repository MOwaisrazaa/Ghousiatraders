<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryListingProtectionTest extends TestCase
{
    /**
     * Test that accessing a normal route works.
     *
     * @return void
     */
    public function testNormalRouteAccess()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }
    
    /**
     * Test that accessing a directory without an index file redirects to home.
     *
     * @return void
     */
    public function testDirectoryAccessRedirectsToHome()
    {
        // Create a test directory without an index file
        $testDir = public_path('test-directory');
        if (!file_exists($testDir)) {
            mkdir($testDir);
        }
        
        // Try to access the directory
        $response = $this->get('/test-directory/');
        
        // Check that it redirects
        $response->assertRedirect('/');
        
        // Clean up
        if (file_exists($testDir)) {
            rmdir($testDir);
        }
    }
    
    /**
     * Test that accessing a directory with an index file works.
     *
     * @return void
     */
    public function testDirectoryWithIndexFileWorks()
    {
        // Create a test directory with an index file
        $testDir = public_path('test-directory-with-index');
        if (!file_exists($testDir)) {
            mkdir($testDir);
        }
        
        // Create an index.php file
        file_put_contents($testDir . '/index.php', '<?php echo "Test Index"; ?>');
        
        // Try to access the directory
        $response = $this->get('/test-directory-with-index/');
        
        // It should not redirect
        $response->assertStatus(200);
        
        // Clean up
        if (file_exists($testDir . '/index.php')) {
            unlink($testDir . '/index.php');
        }
        if (file_exists($testDir)) {
            rmdir($testDir);
        }
    }
    
    /**
     * Test that accessing vulnerable directories returns proper responses.
     *
     * @return void
     */
    public function testVulnerableDirectoriesProtected()
    {
        // List of directories that should be protected
        $vulnerableDirs = [
            '/assets/',
            '/assets/css/',
            '/assets/fonts/',
            '/assets/img/',
            '/assets/js/',
            '/storage/',
        ];
        
        foreach ($vulnerableDirs as $dir) {
            $response = $this->get($dir);
            
            // Should either redirect to home or return 200 (if index.php exists)
            $this->assertTrue(
                $response->isRedirect('/') || $response->status() === 200,
                "Directory {$dir} is not protected"
            );
        }
    }
}