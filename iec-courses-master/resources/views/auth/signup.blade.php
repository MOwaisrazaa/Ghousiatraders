@extends('polani.layout')

@section('title', 'Sign Up — Polani Fragrance')

@section('content')
  <section class="section section--ivory">
    <div class="container" style="max-width: 720px;">
      <div class="section-head">
        <div>
          <div class="eyebrow">JOIN POLANI</div>
          <h1 class="section-title">Create Account</h1>
        </div>
      </div>

      @if (session('message'))
        <div class="prose" style="margin-bottom: 12px;">
          <p style="color:#92400e">{{ session('message') }}</p>
        </div>
      @endif

      @php
        $countriesJson = [];
        try {
            $countriesJson = json_decode(file_get_contents(public_path('assets/js/countrycode.json')), true) ?: [];
        } catch (\Throwable $e) {
            $countriesJson = [];
        }
      @endphp

      <form class="card" method="POST" action="{{ url('/sign-up') }}">
        @csrf

        <div class="card__body" style="display:grid; gap: 14px;">
          <div class="grid-2">
            <label class="field">
              <span class="field__label">Full name</span>
              <input type="text" name="name" value="{{ old('name') }}" required />
              @error('name') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </label>

            <label class="field">
              <span class="field__label">Email</span>
              <input type="email" name="email" value="{{ old('email') }}" required />
              @error('email') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </label>
          </div>

          <div class="grid-2">
            <label class="field">
              <span class="field__label">Password</span>
              <input type="password" name="password" required />
              @error('password') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </label>

            <label class="field">
              <span class="field__label">Confirm password</span>
              <input type="password" name="password_confirmation" required />
            </label>
          </div>

          <div class="grid-2">
            <label class="field">
              <span class="field__label">Country</span>
              <select name="country" required>
                @foreach($countriesJson as $countryItem)
                  <option value="{{ $countryItem['code'] }}" {{ old('country', 'PK') == $countryItem['code'] ? 'selected' : '' }}>
                    {{ $countryItem['name'] }}
                  </option>
                @endforeach
              </select>
              @error('country') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </label>

            <label class="field">
              <span class="field__label">Phone</span>
              <input type="text" name="phone" value="{{ old('phone') }}" required />
              @error('phone') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
            </label>
          </div>

          <label class="check" style="display:flex; gap:10px; align-items:center;">
            <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required />
            <span class="muted">I agree to the terms & conditions</span>
            @error('terms') <div class="muted" style="color:#b91c1c">{{ $message }}</div> @enderror
          </label>

          <button class="btn btn--primary w-100" type="submit">Create Account</button>

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
            Already have an account?
            <a class="link" href="{{ route('sign-in') }}">Sign in</a>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection
