@extends('polani.layout')

@section('title', $page->name . ' — Polani Fragrance')
@section('meta_description', 'Polani Fragrance Store Page')

@push('head')
<style>
  .custom-page {
    background: #0a0a0a;
    color: #f8e7d0;
    padding: 80px 0;
  }
  .page-header {
    text-align: center;
    margin-bottom: 60px;
  }
  .page-header__eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 12px;
  }
  .page-header__title {
    font-family: var(--serif);
    font-size: 3rem;
    font-weight: 600;
    margin: 0 0 16px;
    color: #f8e7d0;
  }
  .page-header__divider {
    width: 60px;
    height: 2px;
    background: var(--gold);
    margin: 20px auto;
  }
  .page-content {
    max-width: 800px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(212, 166, 88, 0.16);
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
  }
  @media (max-width: 768px) {
    .page-content {
      padding: 30px 20px;
    }
  }
  .page-body {
    font-size: 1rem;
    line-height: 1.8;
    color: rgba(248, 231, 208, 0.85);
  }
  .page-body p {
    margin-bottom: 20px;
  }
  .page-body h2 {
    font-family: var(--serif);
    font-size: 1.8rem;
    color: #f8e7d0;
    margin-top: 40px;
    margin-bottom: 20px;
    font-weight: 500;
  }
  .page-body h3 {
    font-family: var(--serif);
    font-size: 1.4rem;
    color: #f8e7d0;
    margin-top: 30px;
    margin-bottom: 15px;
    font-weight: 500;
  }
  .page-body ul, .page-body ol {
    margin-bottom: 20px;
    padding-left: 20px;
  }
  .page-body li {
    margin-bottom: 8px;
  }
  .page-body a {
    color: var(--gold);
    text-decoration: underline;
    transition: color 0.2s ease;
  }
  .page-body a:hover {
    color: #fff;
  }
</style>
@endpush

@section('content')
<div class="custom-page">
  <div class="container">
    <div class="page-header">
      <span class="page-header__eyebrow">Polani Fragrance</span>
      <h1 class="page-header__title">{{ $page->name }}</h1>
      <div class="page-header__divider"></div>
    </div>

    <div class="page-content">
      <div class="page-body">
        {!! $page->content !!}
      </div>
    </div>

    @if(isset($products) && count($products) > 0)
      <div style="margin-top: 80px;">
        <div class="page-header" style="margin-bottom: 40px;">
          <span class="page-header__eyebrow">Explore Our Products</span>
          <h2 class="page-header__title" style="font-size: 2.2rem; line-height:1.2;">Featured Collection</h2>
          <div class="page-header__divider" style="margin-top: 15px;"></div>
        </div>
        
        <div class="product-grid" style="margin-top: 40px;">
          @foreach($products as $product)
            @include('polani.partials.product-card', ['product' => $product])
          @endforeach
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
