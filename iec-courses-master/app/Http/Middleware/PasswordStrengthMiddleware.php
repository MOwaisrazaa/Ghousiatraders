<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordStrengthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request has a password field
        if ($request->has('password')) {
            $password = $request->input('password');
            
            // Get validation settings from config
            $minLength = config('security.password.min_length', 6);
            $maxLength = config('security.password.max_length', 15);
            $regex = config('security.password.regex', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#+=~])[A-Za-z\d@$!%*?&#+=~\.]{6,15}$/');
            
            // 1. Check password length to prevent very long passwords (DoS)
            if (strlen($password) > $maxLength) {
                return response()->json([
                    'error' => 'Password is too long. Maximum allowed is ' . $maxLength . ' characters.'
                ], 422);
            }
            
            // 2. Check minimum length
            if (strlen($password) < $minLength) {
                return response()->json([
                    'error' => 'Password is too short. Minimum required is ' . $minLength . ' characters.'
                ], 422);
            }
            
            // 3. Check password complexity
            if (!preg_match($regex, $password)) {
                return response()->json([
                    'error' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
                ], 422);
            }
        }
        
        // Continue processing the request
        return $next($request);
    }
} 