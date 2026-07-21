@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
@endphp

@section('title', 'Shipping & Delivery — Polani Fragrance')
@section('meta_description', 'Everything you need to know about our shipping rates, delivery timelines, and courier partners.')

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
      <h1 class="policy-header__title">Shipping & Delivery</h1>
      <div class="policy-header__divider"></div>
    </div>

    <div class="policy-content">
      <div class="policy-grid">
        <div class="policy-card">
          <div class="policy-card__title">
            <span class="policy-card__icon">🚚</span> Delivery Timelines
          </div>
          <p class="policy-card__text">
            <strong>Karachi:</strong> 1 to 2 working days.<br>
            <strong>Other Cities:</strong> 3 to 5 working days.<br>
            <em>Please note that delivery times may vary during sales or festive seasons.</em>
          </p>
        </div>

        <div class="policy-card">
          <div class="policy-card__title">
            <span class="policy-card__icon">💳</span> Shipping Charges
          </div>
          <p class="policy-card__text">
            <strong>Standard Shipping:</strong> Rs. 250 nationwide.<br>
            <strong>Free Shipping:</strong> Automatically applied to all orders above Rs. 10,000.
          </p>
        </div>
      </div>

      <div class="policy-section">
        <h2 class="policy-section__title">Order Processing</h2>
        <p class="policy-section__text">
          All orders placed before 3:00 PM (Monday to Saturday) are processed and dispatched on the same day. Orders placed on Sundays or public holidays will be processed on the next working day.
        </p>
        <p class="policy-section__text">
          Once your order is dispatched, you will receive an SMS and email notification with your tracking details.
        </p>
      </div>

      <div class="policy-section">
        <h2 class="policy-section__title">Our Courier Partners</h2>
        <p class="policy-section__text">
          To ensure secure and timely delivery of your luxury fragrances, we partner with Pakistan's leading logistics services:
        </p>
        <ul class="policy-list">
          <li><strong>TCS Logistics:</strong> Swift deliveries across all major cities.</li>
          <li><strong>Leopards Courier:</strong> Reliable tracking and extensive countrywide coverage.</li>
          <li><strong>M&P Courier:</strong> Secure handling of premium parcels.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2 class="policy-section__title">Important Information</h2>
        <p class="policy-section__text">
          Please make sure to provide a complete and correct shipping address along with an active phone number. Our delivery partners will attempt delivery up to two times. If they are unable to contact you, the package will be returned to our warehouse.
        </p>
        <p class="policy-section__text">
          If you have any questions or need to update your delivery address, please contact us immediately via WhatsApp or call us at <strong>{{ $footer->phone }}</strong>.
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
