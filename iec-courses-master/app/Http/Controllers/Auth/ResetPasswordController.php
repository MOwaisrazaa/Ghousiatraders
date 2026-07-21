<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    /**
     * Show the form for resetting the password.
     */
    public function create(Request $request, $token)
    {
        // Verify token exists and is not expired
        $resetToken = PasswordResetToken::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetToken) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'This password reset link has expired or is invalid.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $resetToken->email
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'token' => ['required', 'string'],
            'password' => [
                'required',
                'min:6',
                'max:15',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#+=~])[A-Za-z\d@$!%*?&#+=~\.]{6,15}$/',
                'confirmed'
            ],
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'token.required' => 'Invalid reset link',
            'password.required' => 'Password is required',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
            'password.confirmed' => 'Passwords do not match',
        ]);

        // Verify token exists and is not expired
        $resetToken = PasswordResetToken::where('token', $request->token)
            ->where('email', $request->email)
            ->where('expires_at', '>', now())
            ->first();

        if (!$resetToken) {
            return back()->withErrors(['token' => 'This password reset link has expired or is invalid.']);
        }

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        try {
            // Update password
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the reset token
            $resetToken->delete();

            Log::info('Password reset successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->route('sign-in')
                ->with('success', 'Your password has been reset successfully. Please sign in with your new password.');
        } catch (\Exception $e) {
            Log::error('Failed to reset password', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['password' => 'Failed to reset password. Please try again later.']);
        }
    }
}
