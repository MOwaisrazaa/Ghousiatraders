<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    private function disableProxyEnvironment(): void
    {
        foreach (['HTTP_PROXY', 'HTTPS_PROXY', 'ALL_PROXY', 'GIT_HTTP_PROXY', 'GIT_HTTPS_PROXY'] as $proxyKey) {
            putenv($proxyKey);
            unset($_SERVER[$proxyKey], $_ENV[$proxyKey]);
        }
    }

    public function redirect(Request $request)
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');

        if (!$clientId || !$redirectUri) {
            return redirect()->route('sign-in')->with('error', 'Google login is not configured yet.');
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
            'access_type' => 'online',
            'include_granted_scopes' => 'true',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback(Request $request)
    {
        if ($request->filled('error')) {
            return redirect()->route('sign-in')->with('error', 'Google sign-in was cancelled.');
        }

        $state = $request->session()->pull('google_oauth_state');
        if (!$state || !$request->filled('state') || !hash_equals($state, $request->string('state')->toString())) {
            return redirect()->route('sign-in')->with('error', 'Google sign-in could not be completed. Please try again.');
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        if (!$clientId || !$clientSecret || !$redirectUri) {
            return redirect()->route('sign-in')->with('error', 'Google login is not configured yet.');
        }

        $this->disableProxyEnvironment();

        $tokenResponse = Http::asForm()
            ->withOptions(['proxy' => null])
            ->timeout(20)
            ->post('https://oauth2.googleapis.com/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
            'code' => $request->string('code')->toString(),
        ]);

        if (!$tokenResponse->successful()) {
            Log::warning('Google token exchange failed', ['body' => $tokenResponse->body()]);
            return redirect()->route('sign-in')->with('error', 'Unable to sign in with Google right now.');
        }

        $accessToken = $tokenResponse->json('access_token');
        if (!$accessToken) {
            return redirect()->route('sign-in')->with('error', 'Google did not return an access token.');
        }

        $profileResponse = Http::withToken($accessToken)
            ->withOptions(['proxy' => null])
            ->timeout(20)
            ->get('https://openidconnect.googleapis.com/v1/userinfo');
        if (!$profileResponse->successful()) {
            Log::warning('Google profile fetch failed', ['body' => $profileResponse->body()]);
            return redirect()->route('sign-in')->with('error', 'Unable to fetch Google profile.');
        }

        $googleUser = $profileResponse->json();
        $googleId = $googleUser['sub'] ?? null;
        $email = $googleUser['email'] ?? null;
        $name = $googleUser['name'] ?? ($googleUser['given_name'] ?? 'Google User');

        if (!$googleId || !$email) {
            return redirect()->route('sign-in')->with('error', 'Google account details were incomplete.');
        }

        $user = User::where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'password' => bcrypt(Str::random(32)),
            ]);

            $userRole = Role::where('name', 'User')->first();
            if ($userRole) {
                $user->roles()->attach($userRole);
            }
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleId,
                'name' => $user->name ?: $name,
            ])->save();
        }

        Auth::login($user, true);

        if ($user->isSuperAdmin()) {
            return redirect('/admin')->with('success', 'Welcome back, Super Admin!');
        }

        if ($user->isAdmin()) {
            return redirect('/admin')->with('success', 'Welcome back, Admin!');
        }

        return redirect()->intended(route('home'))->with('success', 'Signed in with Google successfully.');
    }
}
