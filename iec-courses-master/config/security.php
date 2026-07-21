<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | This file contains various security settings for the application.
    |
    */

    // Password constraints
    'password' => [
        'min_length' => 6,
        'max_length' => 15,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'regex' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#+=~])[A-Za-z\d@$!%*?&#+=~\.]{6,15}$/',
    ],

    // Form field validation constraints
    'fields' => [
        'name' => [
            'allowed_pattern' => '/^[A-Za-z\s\.\-]+$/',
            'min_length' => 4,
            'max_length' => 100,
        ],
        'email' => [
            'max_length' => 100,
        ],
        'username' => [
            'allowed_pattern' => '/^[A-Za-z0-9]+$/',
            'max_length' => 25,
        ],
        'website' => [
            'allowed_pattern' => '/^[a-z0-9\/:\-\.]+$/',
            'max_length' => 100,
        ],
        'gender' => [
            'max_length' => 5,
        ],
        'credit_card' => [
            'allowed_pattern' => '/^[0-9]+$/',
            'max_length' => 16,
        ],
        'mobile' => [
            'allowed_pattern' => '/^[0-9]+$/',
            'max_length' => 15,
        ],
        'skills' => [
            'max_length' => 500,
        ],
        'date_time' => [
            'allowed_pattern' => '/^[A-Za-z0-9:\/\-\.]+$/',
            'max_length' => 13,
        ],
        'zip_code' => [
            'allowed_pattern' => '/^[0-9]+$/',
            'max_length' => 8,
        ],
        'address' => [
            'allowed_pattern' => '/^[A-Za-z0-9:\/\-#\,\.]+$/',
            'max_length' => 150,
        ],
        'country_city' => [
            'max_length' => 50,
        ],
        'age' => [
            'allowed_pattern' => '/^[0-9]+$/',
            'max_length' => 3,
        ],
        'occupation' => [
            'allowed_pattern' => '/^[A-Za-z:\/\-#\,\+\=\.]+$/',
            'max_length' => 256,
        ],
        'education' => [
            'allowed_pattern' => '/^[A-Za-z:\/\-#\,\+\=\.]+$/',
            'max_length' => 256,
        ],
        'search' => [
            'max_length' => 100,
        ],
        'comment' => [
            'max_length' => 500,
        ],
    ],

    // Login throttling settings
    'login_throttling' => [
        'max_attempts' => 5,
        'decay_minutes' => 1,
    ],

    // Security headers
    'headers' => [
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'x_frame_options' => 'SAMEORIGIN',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'microphone=self, camera=self',
    ],

    // Error reporting and logging
    'error_reporting' => [
        'production' => false, // Hide all errors in production
        'log_threshold' => 1,  // Log all errors
    ],

    // Cookie security
    'cookies' => [
        'http_only' => true,
        'secure' => env('APP_ENV') !== 'local',
        'same_site' => 'lax',
    ],
]; 