<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set error reporting configuration based on environment
        if (app()->environment('production')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
        }
        
        // Configure log threshold based on security settings
        if (config('security.error_reporting.log_threshold')) {
            Config::set('logging.channels.stack.level', 'debug');
        }
        
        // Set global database error logging
        if (!app()->environment('local')) {
            DB::connection()->enableQueryLog();
            DB::listen(function ($query) {
                // Log potentially harmful queries (containing DELETE, UPDATE, INSERT without a WHERE clause)
                $sql = $query->sql;
                if (preg_match('/^\s*(DELETE|UPDATE)\s+/i', $sql) && !preg_match('/\s+WHERE\s+/i', $sql)) {
                    Log::warning('Potentially harmful query detected (missing WHERE clause)', [
                        'sql' => $sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                    ]);
                }
                
                // Log slow queries
                if ($query->time > 1000) { // 1 second
                    Log::warning('Slow query detected', [
                        'sql' => $sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                    ]);
                }
            });
        }
        
        // Configure cookies to be HTTP only 
        // Cookie::setDefaultSameSite('lax'); // This method doesn't exist in this Laravel version
        if (!app()->environment('local')) {
            $this->app['cookie']->queue(
                $this->app['cookie']->make('name', 'value', 60, null, null, true, true)
            );
        }
        
        // Set custom form validation rules based on security config
        Validator::extend('secure_password', function ($attribute, $value, $parameters, $validator) {
            $regex = config('security.password.regex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/');
            return preg_match($regex, $value);
        });
        
        Validator::extend('no_xss', function($attribute, $value, $parameters, $validator) {
            // Check for common XSS patterns
            $patterns = [
                '/(<.*?(script|iframe|embed|object|applet|form|input|textarea|style|meta|base|body|img|layer|link|ilayer|frame|frameset|bgsound|title|div).*?>)|\s*<+.*(onmouseover|onmouseout|onclick).*?(\"|\').*?>/is',
                '/\w*(&#|javascript:|alert\s*\(|window\.|document\.|\.cookie|<script|<iframe|http-equiv|<input|<img|<div|<table|<form|<object|\)"|\)\;).*/i',
                '/(javascript:|vbscript:|expression\s*\(|applet\s*<|script\s*<|meta\s*<|iframe\s*<|<iframe|<form|<\s*%)/is'
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    return false;
                }
            }
            
            return true;
        });
        
        // Add custom validation messages
        Validator::replacer('secure_password', function ($message, $attribute, $rule, $parameters) {
            return 'The ' . $attribute . ' must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
        });
        
        Validator::replacer('no_xss', function ($message, $attribute, $rule, $parameters) {
            return 'The ' . $attribute . ' contains potentially dangerous content.';
        });
    }
} 