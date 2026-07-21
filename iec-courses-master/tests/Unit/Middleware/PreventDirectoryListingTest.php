<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\PreventDirectoryListing;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PreventDirectoryListingTest extends TestCase
{
    /**
     * Test that the middleware allows normal requests to pass through.
     *
     * @return void
     */
    public function testNormalRequestPassesThrough()
    {
        // Create a mock request
        $request = Request::create('/normal-path', 'GET');
        
        // Create the middleware
        $middleware = new PreventDirectoryListing();
        
        // Create a simple next callback
        $next = function ($request) {
            return 'passed';
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next);
        
        // Assert that the request passed through
        $this->assertEquals('passed', $response);
    }
    
    /**
     * Test that the middleware redirects directory access attempts.
     *
     * @return void
     */
    public function testDirectoryAccessRedirects()
    {
        // Mock the Log facade
        Log::shouldReceive('channel')
            ->once()
            ->with('security')
            ->andReturnSelf();
        
        Log::shouldReceive('warning')
            ->once()
            ->with('Directory listing attempt', \Mockery::any());
        
        // Create a mock request for a directory
        $request = Request::create('/directory/', 'GET');
        
        // Create the middleware
        $middleware = new PreventDirectoryListing();
        
        // Create a simple next callback that should not be called
        $next = function ($request) {
            return 'should not reach here';
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next);
        
        // Assert that the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        
        // Assert that it redirects to the home page
        $this->assertEquals('/', $response->getTargetUrl());
    }
    
    /**
     * Test that the middleware allows directory access if an index file exists.
     *
     * @return void
     */
    public function testDirectoryWithIndexFilePassesThrough()
    {
        // Create a mock for the hasIndexFile method
        $middleware = $this->getMockBuilder(PreventDirectoryListing::class)
            ->onlyMethods(['hasIndexFile'])
            ->getMock();
        
        // Configure the mock to return true for hasIndexFile
        $middleware->method('hasIndexFile')
            ->willReturn(true);
        
        // Create a mock request for a directory
        $request = Request::create('/directory-with-index/', 'GET');
        
        // Create a simple next callback
        $next = function ($request) {
            return 'passed';
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next);
        
        // Assert that the request passed through
        $this->assertEquals('passed', $response);
    }
}