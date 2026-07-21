<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        return view('laravel-examples.user-profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (config('app.is_demo') && in_array(Auth::id(), [1])) {
            return back()->with('error', "You are in a demo version. You are not allowed to change the email for default users.");
        }

        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:100', 'regex:/^[A-Za-z\s\.\-]+$/'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . Auth::id()],
            'location' => ['nullable', 'string', 'max:150', 'regex:/^[A-Za-z0-9\s\.\-\,\#]+$/'],
            'phone' => ['nullable', 'regex:/^\+?[0-9]{10,15}$/'],
            'about' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Name is required',
            'name.regex' => 'Name may only contain letters, spaces, dots and hyphens',
            'email.required' => 'Email is required',
            'location.regex' => 'Location may contain only letters, numbers, spaces, dots, hyphens, commas and hash symbols',
            'phone.regex' => 'Invalid phone number format. Use only numbers with optional + prefix (10-15 digits)',
        ]);

        $user = User::find(Auth::id());

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'phone' => $request->phone,
            'about' => $request->about,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
