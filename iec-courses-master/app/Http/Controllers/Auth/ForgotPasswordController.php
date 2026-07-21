<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetToken;
use App\Services\DibaEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    protected $emailService;

    public function __construct(DibaEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Show the form for requesting a password reset link.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset link to the user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:100'],
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // For security, don't reveal if email exists
            return back()->with('success', 'If an account exists with this email, you will receive a password reset link shortly.');
        }

        try {
            // Generate a unique token
            $token = Str::random(64);
            $expiresAt = now()->addMinutes(60); // Token expires in 60 minutes

            // Delete any existing reset tokens for this email
            PasswordResetToken::where('email', $user->email)->delete();

            // Create new password reset token
            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // Generate reset link
            $resetLink = route('password.reset', ['token' => $token]);

            // Send password reset email using Diba API
            $emailResult = $this->emailService->sendPasswordResetEmail(
                $user->email,
                $user->name,
                $resetLink
            );

            if (!$emailResult['success']) {
                Log::error('Failed to send password reset email via Diba API', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $emailResult['message'],
                    'data' => $emailResult['data']
                ]);

                return back()->withErrors(['email' => 'Failed to send password reset link. Please try again later.']);
            }

            Log::info('Password reset link sent successfully via Diba API', [
                'user_id' => $user->id,
                'email' => $user->email,
                'expires_at' => $expiresAt
            ]);

            return back()->with('success', 'If an account exists with this email, you will receive a password reset link shortly.');
        } catch (\Exception $e) {
            Log::error('Failed to send password reset link', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['email' => 'Failed to send password reset link. Please try again later.']);
        }
    }
}
