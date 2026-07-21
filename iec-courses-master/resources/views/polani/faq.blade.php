@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
@endphp

@section('title', 'Frequently Asked Questions (FAQ) — Polani Fragrance')
@section('meta_description', 'Have questions? Find answers to commonly asked questions about our products, ordering, shipping, and returns.')

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
  .faq-category {
    margin-bottom: 40px;
  }
  .faq-category__title {
    font-family: var(--serif);
    font-size: 1.6rem;
    font-weight: 600;
    color: var(--gold);
    border-bottom: 2px solid rgba(212, 166, 88, 0.15);
    padding-bottom: 10px;
    margin-bottom: 20px;
  }
  .faq-item {
    border-bottom: 1px solid rgba(212, 166, 88, 0.15);
    padding: 20px 0;
  }
  .faq-item:last-child {
    border-bottom: none;
  }
  .faq-item summary {
    font-family: var(--sans);
    font-size: 1.05rem;
    font-weight: 600;
    color: #f8e7d0;
    cursor: pointer;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    outline: none;
    user-select: none;
  }
  .faq-item summary::-webkit-details-marker {
    display: none;
  }
  .faq-item summary::after {
    content: '+';
    font-family: var(--sans);
    font-size: 1.5rem;
    font-weight: 400;
    color: var(--gold);
    transition: transform 0.3s ease;
    line-height: 1;
    margin-left: 20px;
  }
  .faq-item[open] summary::after {
    transform: rotate(45deg);
  }
  .faq-item__body {
    margin-top: 14px;
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(248, 231, 208, 0.75);
  }
  .faq-item__body p {
    margin: 0 0 10px;
  }
  .faq-item__body p:last-child {
    margin-bottom: 0;
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
      <span class="policy-header__eyebrow">Assistance</span>
      <h1 class="policy-header__title">Frequently Asked Questions</h1>
      <div class="policy-header__divider"></div>
    </div>

    <div class="policy-content">
      
      {{-- Category: Products & Fragrances --}}
      <div class="faq-category">
        <h2 class="faq-category__title">Products & Fragrances</h2>
        
        <details class="faq-item">
          <summary>What is Extrait de Parfum?</summary>
          <div class="faq-item__body">
            <p>Extrait de Parfum is the highest concentration of fragrance oil available, typically containing between 20% and 40% aromatic compounds. This makes it much richer, more complex, and longer-lasting than Eau de Parfum (EDP) or Eau de Toilette (EDT).</p>
          </div>
        </details>

        <details class="faq-item">
          <summary>Are Polani Fragrances long-lasting?</summary>
          <div class="faq-item__body">
            <p>Yes, absolutely! Since our entire collection is formulated in Extrait de Parfum concentration, they typically last between 8 to 12 hours on the skin, and even longer on clothing, depending on the specific fragrance profile and climate conditions.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary>Can I customize my perfume bottle or box?</summary>
          <div class="faq-item__body">
            <p>Yes! We are proud to offer custom-engraved perfume bottles and personalized luxury boxes for gifting, weddings, corporate events, or personal collections. Please get in touch with our team via WhatsApp to discuss customization options.</p>
          </div>
        </details>
      </div>

      {{-- Category: Ordering & Payments --}}
      <div class="faq-category">
        <h2 class="faq-category__title">Ordering & Payments</h2>
        
        <details class="faq-item">
          <summary>What payment methods do you accept?</summary>
          <div class="faq-item__body">
            <p>We accept <strong>Cash on Delivery (COD)</strong>, Online Bank Transfers, Easypaisa, and JazzCash. You can choose your preferred payment option at checkout.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary>How can I change or cancel my order?</summary>
          <div class="faq-item__body">
            <p>Orders are processed very quickly. If you need to make changes or cancel your order, please contact us on WhatsApp at <strong>{{ $footer->phone }}</strong> within 2 hours of placing the order.</p>
          </div>
        </details>
      </div>

      {{-- Category: Shipping & Delivery --}}
      <div class="faq-category">
        <h2 class="faq-category__title">Shipping & Delivery</h2>
        
        <details class="faq-item">
          <summary>How much do you charge for shipping?</summary>
          <div class="faq-item__body">
            <p>We charge a flat standard rate of Rs. 250 for shipping nationwide. However, all orders above Rs. 10,000 are eligible for free shipping.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary>How can I track my order?</summary>
          <div class="faq-item__body">
            <p>You can track your order status live on our website by going to the <a href="{{ route('polani.track-order') }}">Track Order</a> page. Simply enter your Order Number (e.g. #PF-2026-0001) and either your email or phone number to view live details.</p>
          </div>
        </details>
      </div>

      {{-- Category: Returns & Exchanges --}}
      <div class="faq-category">
        <h2 class="faq-category__title">Returns & Exchanges</h2>
        
        <details class="faq-item">
          <summary>What is your return policy?</summary>
          <div class="faq-item__body">
            <p>We offer a hassle-free 7-day return and exchange policy for unused items in their original packaging with unbroken seals. For complete details, please visit our <a href="{{ route('polani.returns') }}">Returns & Exchanges</a> page.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary>How do I return a damaged product?</summary>
          <div class="faq-item__body">
            <p>If you receive a damaged, leaking, or incorrect perfume bottle, please contact us immediately on WhatsApp with a photo/video of the issue. We will ship a replacement bottle to you free of charge.</p>
          </div>
        </details>
      </div>

      @if($publishedFaqs && $publishedFaqs->count() > 0)
        {{-- Category: Community Q&A --}}
        <div class="faq-category" style="margin-top: 40px;">
          <h2 class="faq-category__title">Community Q&A</h2>
          
          @foreach($publishedFaqs as $faq)
            <details class="faq-item">
              <summary>{{ $faq->question }}</summary>
              <div class="faq-item__body">
                <p>{{ $faq->answer }}</p>
                <small style="display: block; margin-top: 8px; color: var(--gold); opacity: 0.7; font-size: 0.8rem;">
                  Answered by {{ $faq->answerer ? $faq->answerer->name : 'Admin' }} on {{ $faq->answered_at ? $faq->answered_at->format('M d, Y') : $faq->updated_at->format('M d, Y') }}
                </small>
              </div>
            </details>
          @endforeach
        </div>
      @endif

      {{-- Ask a Question Section --}}
      <div style="margin-top: 60px; border-top: 1px solid rgba(212, 166, 88, 0.2); padding-top: 40px;">
        <h3 style="font-family: var(--serif); font-size: 1.8rem; font-weight: 600; color: var(--gold); margin-bottom: 8px; text-align: center;">Have a Different Question?</h3>
        <p style="text-align: center; color: rgba(248, 231, 208, 0.7); font-size: 0.95rem; margin-bottom: 30px;">Submit your query below and our fragrance experts will respond shortly.</p>
        
        @if(session('success'))
          <div style="background: rgba(25, 135, 84, 0.15); border: 1px solid #198754; color: #a3cfbb; padding: 15px; border-radius: 10px; margin-bottom: 25px; text-align: center;">
            {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('polani.faq.ask') }}" method="POST" style="max-width: 600px; margin: 0 auto;">
          @csrf
          @guest
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
              <div>
                <label style="display: block; font-size: 0.85rem; color: var(--gold); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">Your Name</label>
                <input type="text" name="name" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(212,166,88,0.25); border-radius: 8px; padding: 12px; color: #f8e7d0; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='rgba(212,166,88,0.25)'" />
              </div>
              <div>
                <label style="display: block; font-size: 0.85rem; color: var(--gold); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">Your Email</label>
                <input type="email" name="email" required style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(212,166,88,0.25); border-radius: 8px; padding: 12px; color: #f8e7d0; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='rgba(212,166,88,0.25)'" />
              </div>
            </div>
          @endguest

          <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 0.85rem; color: var(--gold); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">Your Question</label>
            <textarea name="question" rows="4" required placeholder="Type your question here..." style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(212,166,88,0.25); border-radius: 8px; padding: 12px; color: #f8e7d0; outline: none; resize: vertical; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--gold)'" onblur="this.style.borderColor='rgba(212,166,88,0.25)'"></textarea>
          </div>

          <div style="text-align: center;">
            <button type="submit" style="background: var(--gold); color: #000; border: none; border-radius: 30px; padding: 14px 40px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 166, 88, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(212, 166, 88, 0.4)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 15px rgba(212, 166, 88, 0.25)'">
              Submit Question
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
