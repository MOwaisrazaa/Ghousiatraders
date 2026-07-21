@extends('polani.layout')

@section('title', 'Privacy Policy — Polani Fragrance')
@section('meta_description', 'Our privacy policy explains how we collect, use, protect, and manage your personal data when you shop with us.')

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
  @media (max-width: 768px) {
    .policy-content {
      padding: 30px 20px;
    }
  }
  .policy-section {
    margin-bottom: 35px;
    border-top: 1px solid rgba(212, 166, 88, 0.15);
    padding-top: 25px;
  }
  .policy-section:first-of-type {
    border-top: none;
    padding-top: 0;
  }
  .policy-section__num {
    font-family: var(--serif);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gold);
    display: block;
    margin-bottom: 6px;
  }
  .policy-section__title {
    font-family: var(--serif);
    font-size: 1.4rem;
    font-weight: 600;
    color: #f8e7d0;
    margin: 0 0 14px;
  }
  .policy-section__text {
    font-size: 0.95rem;
    line-height: 1.65;
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
  .policy-update {
    font-size: 0.85rem;
    color: #888;
    font-style: italic;
    border-top: 1px solid rgba(212, 166, 88, 0.15);
    padding-top: 20px;
    margin-top: 40px;
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
      <span class="policy-header__eyebrow">Privacy & Trust</span>
      <h1 class="policy-header__title">Privacy Policy</h1>
      <div class="policy-header__divider"></div>
    </div>

    <div class="policy-content">
      <p class="policy-section__text" style="font-size: 1.05rem; line-height: 1.6; font-weight: 500; color: #f8e7d0; margin-bottom: 30px;">
        At Polani Fragrance, we value your trust and are committed to protecting your personal information. This Privacy Policy details how we collect, use, and secure your data when you visit our store or make a purchase.
      </p>

      <div class="policy-section">
        <span class="policy-section__num">01</span>
        <h2 class="policy-section__title">Information We Collect</h2>
        <p class="policy-section__text">
          When you purchase products or register an account on our website, we collect personal information you provide to us, including:
        </p>
        <ul class="policy-list">
          <li><strong>Identity Data:</strong> Full name, username, or account login credentials.</li>
          <li><strong>Contact Data:</strong> Email address, phone number, and physical shipping address.</li>
          <li><strong>Transaction Data:</strong> Details about payments, order history, and product preferences.</li>
          <li><strong>Technical Data:</strong> IP address, browser type, device information, and activity logs.</li>
        </ul>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">02</span>
        <h2 class="policy-section__title">How We Use Your Data</h2>
        <p class="policy-section__text">
          We use your personal data to provide a seamless shopping experience. Specific purposes include:
        </p>
        <ul class="policy-list">
          <li>Processing and delivering your orders, including sending order updates via SMS/email.</li>
          <li>Managing your customer account and providing support.</li>
          <li>Verifying order details to prevent fraudulent transactions.</li>
          <li>Improving our website performance, layout, and product offerings.</li>
        </ul>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">03</span>
        <h2 class="policy-section__title">Cookies and Site Activity</h2>
        <p class="policy-section__text">
          We use cookies and similar tracking technologies to enhance your browsing experience. Cookies help us remember your shopping cart items, recognize you when you return to our website, and analyze web traffic. You can choose to disable cookies through your browser settings, though it may limit some features of the site (like retaining items in your shopping cart).
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">04</span>
        <h2 class="policy-section__title">Information Sharing</h2>
        <p class="policy-section__text">
          We do not sell, rent, or trade your personal information with third parties. We only share necessary delivery data (name, phone, and address) with our logistics partners (TCS, Leopards, M&P) to fulfill shipping.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">05</span>
        <h2 class="policy-section__title">Data Security</h2>
        <p class="policy-section__text">
          To protect your personal information, we take reasonable precautions and follow industry best practices to make sure it is not inappropriately lost, misused, accessed, disclosed, altered or destroyed.
        </p>
        <p class="policy-section__text">
          All order transmission details are encrypted using Secure Socket Layer (SSL) technology. Password data is securely hashed on our servers, and payment transactions are processed through secure gateways.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">06</span>
        <h2 class="policy-section__title">Your Rights</h2>
        <p class="policy-section__text">
          You have the right to request access to the personal data we hold about you, request corrections to incomplete information, or request the deletion of your account and personal history from our servers. Please contact our support team to make such requests.
        </p>
      </div>

      <div class="policy-update">
        Last Updated: June 2026
      </div>
    </div>
  </div>
</div>
@endsection
