<?php

/**
 * Helper functions for Livewire CSP compatibility
 */

if (!function_exists('livewire_csp_init')) {
    /**
     * Initialize Livewire CSP compatibility
     * 
     * @return void
     */
    function livewire_csp_init()
    {
        // For Livewire v3, we don't need to add scripts directly
        // The JavaScript in livewire-helpers.js handles this functionality
        
        // Add a meta tag with the nonce to the head section
        if (class_exists('\Livewire\Livewire')) {
            // Register a view composer to add the nonce meta tag
            \Illuminate\Support\Facades\View::composer('*', function ($view) {
                $nonce = csp_nonce();
                $view->with('cspNonce', $nonce);
            });
        }
    }
}