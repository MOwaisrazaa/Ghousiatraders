@extends('polani.layout')

@section('title', 'Access Restricted — Polani Fragrance')

@section('content')
  <section class="section section--ivory">
    <div class="container" style="max-width: 600px;">
      <div class="section-head">
        <div style="text-align: center; width: 100%;">
          <div class="eyebrow" style="color: #b91c1c;">SECURITY ALERT</div>
          <h1 class="section-title">{{ session('error', 'Access Restricted') }}</h1>
        </div>
      </div>

      <div class="card">
        <div class="card__body" style="display:grid; gap: 20px;">
          @if(session('error_details'))
            <div class="prose" style="padding: 16px; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px;">
              <p style="color:#b91c1c; margin: 0; line-height: 1.6;">
                {{ session('error_details') }}
              </p>
            </div>
          @endif

          @if(session('error_type') === 'ip_limit_reached')
            <div style="display: grid; gap: 10px;">
              <h3 style="font-family: var(--serif); font-size: 1.25rem; font-weight: 500; margin: 0;">Recommended actions:</h3>
              <ul style="padding-left: 20px; margin: 0; display: grid; gap: 8px; line-height: 1.5;" class="muted">
                <li>Log in from one of your previously used internet connections.</li>
                <li>Ensure you are not connected to a VPN or proxy service.</li>
                <li>Contact our support team to reset your IP location limit.</li>
                <li>This limit helps secure your account against unauthorized access.</li>
              </ul>
            </div>
          @endif

          @if(session('support_contact'))
            <div style="padding: 16px; background: rgba(23, 23, 23, 0.03); border: 1px solid rgba(23, 23, 23, 0.08); border-radius: 8px; display: grid; gap: 8px;">
              <h4 style="font-family: var(--serif); font-size: 1.1rem; font-weight: 500; margin: 0;">Need Assistance?</h4>
              <p class="muted" style="margin: 0; font-size: 0.9rem;">
                Our customer support team is available to help resolve any access restrictions quickly.
              </p>
              <div>
                <a href="mailto:{{ session('support_contact') }}" class="btn btn--primary" style="display: inline-block; padding: 8px 16px; text-decoration: none; font-size: 0.9rem;">
                  Contact Support
                </a>
              </div>
            </div>
          @endif

          <div style="display: flex; gap: 12px; margin-top: 10px;">
            <a href="{{ route('sign-in') }}" class="btn btn--ghost btn--dark" style="flex: 1; text-align: center; text-decoration: none;">
              Try Again
            </a>
            <a href="{{ route('home') }}" class="btn btn--primary" style="flex: 1; text-align: center; text-decoration: none;">
              Go to Homepage
            </a>
          </div>

          <p class="muted" style="text-align: center; font-size: 0.8rem; margin: 0; margin-top: 10px;">
            These security measures are in place to safeguard your account.
          </p>
        </div>
      </div>
    </div>
  </section>
@endsection

