@extends('polani.layout')

@section('title', 'Forgot Password — Polani Fragrance')

@section('content')
  <section class="section section--ivory">
    <div class="container" style="max-width: 560px;">
      <div class="section-head">
        <div>
          <div class="eyebrow">RESET PASSWORD</div>
          <h1 class="section-title">Forgot Password</h1>
        </div>
      </div>

      @if (session('status'))
        <div class="prose" style="margin-bottom: 12px; padding: 12px; background: rgba(79, 200, 100, 0.1); border: 1px solid rgba(79, 200, 100, 0.2); border-radius: 8px;">
          <p style="color:#166534; margin: 0;">{{ session('status') }}</p>
        </div>
      @endif

      @if (session('success'))
        <div class="prose" style="margin-bottom: 12px; padding: 12px; background: rgba(79, 200, 100, 0.1); border: 1px solid rgba(79, 200, 100, 0.2); border-radius: 8px;">
          <p style="color:#166534; margin: 0;">{{ session('success') }}</p>
        </div>
      @endif

      <form class="card" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="card__body" style="display:grid; gap: 16px;">
          <p class="muted" style="margin: 0; font-size: 0.95rem; line-height: 1.6;">
            No worries! Enter your email address below and we'll send you a secure link to reset your password.
          </p>

          <label class="field">
            <span class="field__label">Email Address</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. yourname@email.com" required autofocus />
            @error('email') <div class="muted" style="color:#b91c1c; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div> @enderror
          </label>

          <button class="btn btn--primary w-100" type="submit">Send Reset Link</button>

          <div class="muted" style="text-align:center; margin-top: 10px;">
            Remember your password?
            <a class="link" href="{{ route('sign-in') }}">Sign In</a>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection
