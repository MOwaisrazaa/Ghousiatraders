@extends('polani.layout')

@section('title', 'Sign In — Polani Fragrance')

@section('content')
  <section class="section section--ivory">
    <div class="container" style="max-width: 560px;">
      <div class="section-head">
        <div>
          <div class="eyebrow">WELCOME BACK</div>
          <h1 class="section-title">Sign In</h1>
        </div>
      </div>

      @if (session('status'))
        <div class="prose" style="margin-bottom: 12px;">
          <p style="color:#166534">{{ session('status') }}</p>
        </div>
      @endif

      @if (session('warning'))
        <div class="prose" style="margin-bottom: 12px;">
          <p style="color:#92400e">{{ session('warning') }}</p>
        </div>
      @endif

      @if (session('error'))
        <div class="prose" style="margin-bottom: 12px;">
          <p style="color:#b91c1c">{{ session('error') }}</p>
        </div>
      @endif

      <form class="card" method="POST" action="{{ url('/sign-in') }}">
        @csrf

        <div class="card__body" style="display:grid; gap: 12px;">
          <label class="field">
            <span class="field__label">Email</span>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
          </label>

          <label class="field">
            <span class="field__label">Password</span>
            <input type="password" name="password" required />
            @error('password') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
          </label>

          <label class="check" style="display:flex; gap:10px; align-items:center;">
            <input type="checkbox" name="remember" value="1" />
            <span class="muted">Remember me</span>
          </label>

          <button class="btn btn--primary w-100" type="submit">Sign In</button>

          <div style="display:flex; align-items:center; gap:12px; margin:2px 0;">
            <span style="flex:1; height:1px; background:rgba(23,23,23,.12)"></span>
            <span class="muted" style="font-size:12px; letter-spacing:.14em; text-transform:uppercase;">or</span>
            <span style="flex:1; height:1px; background:rgba(23,23,23,.12)"></span>
          </div>

          <a class="btn btn--ghost btn--dark w-100" href="{{ route('google.redirect') }}" style="display:flex; justify-content:center; align-items:center; gap:10px;">
            <span aria-hidden="true" data-icon="google"></span>
            Continue with Google
          </a>

          <div class="muted" style="text-align:center;">
            Don’t have an account?
            <a class="link" href="{{ route('sign-up') }}">Create one</a>
          </div>

          <div class="muted" style="text-align:center;">
            <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection
