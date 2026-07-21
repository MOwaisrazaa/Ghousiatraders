@extends('polani.layout')

@section('title', 'Checkout — Polani Fragrance')

@section('content')
  <section class="section section--ivory">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow">SECURE</div>
          <h1 class="section-title">Checkout</h1>
        </div>
      </div>

      <livewire:checkout />
    </div>
  </section>
@endsection

