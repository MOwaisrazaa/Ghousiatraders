@extends('polani.layout')

@section('title', 'Terms & Conditions — Polani Fragrance')
@section('meta_description', 'Please read our terms and conditions carefully before using our website or placing an order.')

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
      <span class="policy-header__eyebrow">Legal Notice</span>
      <h1 class="policy-header__title">Terms & Conditions</h1>
      <div class="policy-header__divider"></div>
    </div>

    <div class="policy-content">
      <p class="policy-section__text" style="font-size: 1.05rem; line-height: 1.6; font-weight: 500; color: #f8e7d0; margin-bottom: 30px;">
        Welcome to Polani Fragrance. These Terms and Conditions govern your use of our website and the purchase of our products. By accessing the site or placing an order, you agree to be bound by these terms.
      </p>

      <div class="policy-section">
        <span class="policy-section__num">01</span>
        <h2 class="policy-section__title">General Conditions</h2>
        <p class="policy-section__text">
          We reserve the right to refuse service to anyone for any reason at any time. You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the service, use of the service, or access to the service without express written permission by us.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">02</span>
        <h2 class="policy-section__title">Product Accuracy & Specifications</h2>
        <p class="policy-section__text">
          We make every effort to display the colors, descriptions, packaging, and imagery of our perfumes as accurately as possible. However, the actual display color you see depends on your device screen settings, and we cannot guarantee that your monitor's display of any color will be completely accurate.
        </p>
        <p class="policy-section__text">
          All descriptions of products or product pricing are subject to change at any time without notice, at our sole discretion. We reserve the right to discontinue any product at any time.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">03</span>
        <h2 class="policy-section__title">Ordering & Payment</h2>
        <p class="policy-section__text">
          By placing an order, you represent that all details you provide to us are true and accurate, that you are authorized to use the credit card or wallet used, and that there are sufficient funds to cover the cost of the products.
        </p>
        <p class="policy-section__text">
          We reserve the right to refuse or cancel any order. In the event that we make a change to or cancel an order, we will attempt to notify you by contacting the email, billing address, or phone number provided at the time the order was made.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">04</span>
        <h2 class="policy-section__title">Delivery & Return Policies</h2>
        <p class="policy-section__text">
          The delivery of products is carried out by third-party logistics firms. While we strive to ensure timely dispatch, we are not liable for delayed deliveries caused by courier processing errors, weather conditions, or local strikes.
        </p>
        <p class="policy-section__text">
          Our return and exchange rules are governed by our specific policy document. Please refer to our <a href="{{ route('polani.returns') }}" style="color: var(--gold); text-decoration: none;">Returns & Exchanges</a> guidelines for more information.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">05</span>
        <h2 class="policy-section__title">Intellectual Property</h2>
        <p class="policy-section__text">
          All content included on this website, such as text, graphics, logos, button icons, images, audio clips, digital downloads, data compilations, and software, is the property of Polani Fragrance and is protected by local and international copyright laws.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">06</span>
        <h2 class="policy-section__title">Limitation of Liability</h2>
        <p class="policy-section__text">
          In no case shall Polani Fragrance, our directors, officers, employees, affiliates, agents, contractors, or interns be liable for any injury, loss, claim, or any direct, indirect, incidental, punitive, special, or consequential damages of any kind, including, without limitation lost profits, lost revenue, lost savings, loss of data, replacement costs, or any similar damages, whether based in contract, tort (including negligence), strict liability or otherwise.
        </p>
      </div>

      <div class="policy-section">
        <span class="policy-section__num">07</span>
        <h2 class="policy-section__title">Governing Law</h2>
        <p class="policy-section__text">
          These Terms and Conditions and any separate agreements whereby we provide you services shall be governed by and construed in accordance with the laws of the Islamic Republic of Pakistan.
        </p>
      </div>

      <div class="policy-update">
        Last Updated: June 2026
      </div>
    </div>
  </div>
</div>
@endsection
