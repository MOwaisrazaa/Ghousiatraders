<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function login()
    {
        // Option 1: Use a static guest user if it exists:
        $guestEmail = 'guest@example.com';
        $guestUser = User::where('email', $guestEmail)->first();

        // If the guest user does not exist, create one:
        if (!$guestUser) {
            $guestUser = User::create([
                'name'     => 'Guest User',
                'email'    => $guestEmail,
                'password' => Hash::make(Str::random(10)), // Random password
                // You can add a flag or role to distinguish guest users if needed.
            ]);
        }

        // Log the user in
        Auth::login($guestUser);

        // Redirect to a page (for example, home) with guest permissions
        return redirect()->intended('/');
    }
}
