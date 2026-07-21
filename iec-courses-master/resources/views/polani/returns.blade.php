@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
@endphp

@section('title', 'Returns & Exchanges — Polani Fragrance')
@section('meta_description', 'Learn about our 7-day return and exchange policy. We ensure a smooth and easy process for all orders.')

@push('head')
<style>
  .policy-page {
    background: #0a0a0a;
    color: #f8e7d0;
    padding: 80px 0;
  }
  .policy-header {
    text-align: center;
    margin-bottom: 60px;
  }
  .policy-header__eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 12px;
  }
  .policy-header__title {
    font-family: var(--serif);
    font-size: 3rem;
    font-weight: 600;
    margin: 0 0 16px;
    color: #f8e7d0;
  }
  .policy-header__divider {
    width: 60px;
    height: 2px;
    background: var(--gold);
    margin: 20px auto;
  }
  .policy-content {
    max-width: 800px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(212, 166, 88, 0.16);
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
  }
  .policy-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
  }
  @media (max-width: 768px) {
    .policy-grid {
      grid-template-columns: 1fr;
    }
    .policy-content {
      padding: 30px 20px;
    }
  }
  .policy-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(212, 166, 88, 0.15);
    border-radius: 12px;
    padding: 24px;
  }
  .policy-card__title {
    font-family: var(--serif);
    font-size: 1.25rem;
    font-weight: 600;
    color: #f8e7d0;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .policy-card__icon {
    color: var(--gold);
    font-size: 1.4rem;
  }
  .policy-card__text {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(248, 231, 208, 0.75);
    margin: 0;
  }
  .policy-section {
    margin-top: 40px;
    border-top: 1px solid rgba(212, 166, 88, 0.15);
    padding-top: 30px;
  }
  .policy-section__title {
    font-family: var(--serif);
    font-size: 1.5rem;
    font-weight: 600;
    color: #f8e7d0;
    margin-bottom: 16px;
  }
  .policy-section__text {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(248, 231, 208, 0.75);
    margin-bottom: 14px;
  }
  .policy-list {
    margin: 0 0 20px 20px;
    padding: 0;
  }
  .policy-list li {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(248, 231, 208, 0.75);
    margin-bottom: 8px;
  }
  .policy-step {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    align-items: flex-start;
  }
  .policy-step__num {
    background: var(--gold);
    color: #111;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    flex-shrink: 0;
    margin-top: 2px;
  }
  .policy-step__title {
    font-weight: 600;
    color: #f8e7d0;
    font-size: 1rem;
    margin-bottom: 4px;
  }
  .policy-step__text {
    font-size: 0.95rem;
    line-height: 1.5;
    color: rgba(248, 231, 208, 0.75);
    margin: 0;
  }
  .policy-content a {
    color: var(--gold);
    text-decoration: underline;
    transition: color 0.2s ease;
  }
  .policy-content a:hover {
    color: #f8e7d0;
  }
</style>
@endpush

@section('content')
<div class="policy-page">
  <div class="container">
    <div class="policy-header">
      <span class="policy-header__eyebrow">Customer Care</span>
      <h1 class="policy-header__title">Returns & Exchanges</h1>
      <div class="policy-header__divider"></div>
    </div>

    <div class="policy-content">
      <div class="policy-grid">
        <div class="policy-card">
          <div class="policy-card__title">
            <span class="policy-card__icon">⏱️</span> 7-Day Window
          </div>
          <p class="policy-card__text">
            We offer a hassle-free <strong>7-day return and exchange policy</strong> starting from the day you receive your package.
          </p>
        </div>

        <div class="policy-card">
          <div class="policy-card__title">
            <span class="policy-card__icon">✨</span> Satisfaction Guarantee
          </div>
          <p class="policy-card__text">
            If you are not completely satisfied with your purchase, you can easily exchange it or request a refund.
          </p>
        </div>
      </div>

      <div class="policy-section">
        <h2 class="policy-section__title">Terms & Conditions for Returns</h2>
        <p class="policy-section__text">
          To qualify for an exchange or a refund, please ensure that:
        </p>
        <ul class="policy-list">
          <li>The product must be unused, in its original packaging, and in the same condition as received.</li>
          <li>The seal or wrapping of the perfume box must not be broken or damaged (except in case of receiving a damaged/incorrect product).</li>
          <li>Products purchased during sales or promotions are only eligible for exchange, not refunds.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2 class="policy-section__title">How to Initiate a Return</h2>
        <p class="policy-section__text">
          Following these simple steps will ensure a smooth process:
        </p>

        <div class="policy-step">
          <div class="policy-step__num">1</div>
          <div class="policy-step__content">
            <div class="policy-step__title">Contact Us</div>
            <p class="policy-step__text">Reach out to our customer care team on WhatsApp or call at <strong>{{ $footer->phone }}</strong>. Provide your name, order number, and reason for return.</p>
          </div>
        </div>

        <div class="policy-step">
          <div class="policy-step__num">2</div>
          <div class="policy-step__content">
            <div class="policy-step__title">Pack the Item</div>
            <p class="policy-step__text">Safely pack the perfume bottle back in its original box and place it inside a secure shipping box to prevent any breakage in transit.</p>
          </div>
        </div>

        <div class="policy-step">
          <div class="policy-step__num">3</div>
          <div class="policy-step__content">
            <div class="policy-step__title">Ship Back</div>
            <p class="policy-step__text">Ship the parcel back to our warehouse. (We will share our warehouse address details with you during step 1). Please note that return shipping fees are paid by the customer.</p>
          </div>
        </div>
      </div>

      <div class="policy-section">
        <h2 class="policy-section__title">Refund Process</h2>
        <p class="policy-section__text">
          Once your returned package is received and inspected by our warehouse quality team:
        </p>
        <ul class="policy-list">
          <li>We will notify you via email/SMS regarding the approval or rejection of your refund request.</li>
          <li>If approved, your refund will be processed within <strong>3 to 5 working days</strong>.</li>
          <li>The refund can be sent directly to your Bank Account, Easypaisa, or JazzCash wallet.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
