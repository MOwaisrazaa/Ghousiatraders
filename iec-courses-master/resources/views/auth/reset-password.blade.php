@extends('polani.layout')

@section('title', 'Reset Password — Polani Fragrance')

@section('content')
  <section class="section section--ivory">
    <div class="container" style="max-width: 560px;">
      <div class="section-head">
        <div>
          <div class="eyebrow">CREATE NEW PASSWORD</div>
          <h1 class="section-title">Reset Password</h1>
        </div>
      </div>

      @if (session('status'))
        <div class="prose" style="margin-bottom: 12px; padding: 12px; background: rgba(79, 200, 100, 0.1); border: 1px solid rgba(79, 200, 100, 0.2); border-radius: 8px;">
          <p style="color:#166534; margin: 0;">{{ session('status') }}</p>
        </div>
      @endif

      <form class="card" method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}" />
        <input type="hidden" name="token" value="{{ $token }}" />

        <div class="card__body" style="display:grid; gap: 16px;">
          <p class="muted" style="margin: 0; font-size: 0.95rem; line-height: 1.6;">
            Set a strong password to secure your account and regain access.
          </p>

          <label class="field">
            <span class="field__label">New Password</span>
            <input type="password" id="password" name="password" required autofocus />
            @error('password') <div class="muted" style="color:#b91c1c; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div> @enderror
          </label>

          <label class="field">
            <span class="field__label">Confirm Password</span>
            <input type="password" id="password_confirmation" name="password_confirmation" required />
            @error('password_confirmation') <div class="muted" style="color:#b91c1c; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div> @enderror
          </label>

          <button class="btn btn--primary w-100" type="submit">Reset Password</button>

          <div class="muted" style="text-align:center; margin-top: 10px;">
            Remember your password?
            <a class="link" href="{{ route('sign-in') }}">Sign In</a>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection
