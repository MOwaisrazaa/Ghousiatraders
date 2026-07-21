<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle CSRF token mismatches (session timeouts)
        $this->renderable(function (TokenMismatchException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Session expired. Please refresh and try again.'
                ], 419);
            }

            // If the user was attempting a post request and not logged in
            if ($request->isMethod('post') && !auth()->check()) {
                return redirect()->route('sign-in')
                    ->with('error', 'Your session has expired. Please sign in again.');
            }

            // If user is logged in but session expired (refresh token)
            if (auth()->check()) {
                auth()->logout();
                return redirect()->route('sign-in')
                    ->with('error', 'Your session has expired. Please sign in again.');
            }

            // Default case
            return redirect()->route('sign-in')
                ->with('error', 'Your session has expired. Please try again.');
        });

        // Customize auth exceptions
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            return redirect()->route('sign-in')
                ->with('error', 'You need to sign in to access this page.');
        });
    }
}
