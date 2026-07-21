@extends('polani.layout')

@section('title', 'Cart — Polani Fragrance')
@section('body_class', 'page-cart')

@section('content')
  <x-polani.page-banner
    page-key="cart"
    eyebrow="YOUR BASKET"
    title="Your Cart"
    subtitle="Review your selected Polani fragrances before checkout."
    :cta-text="'Continue Shopping'"
    :cta-url="route('polani.collection')"
    fallback-image="polani/assets/cart_banner.jpeg"
    image-position="right center"
  />

  <livewire:shoppingcart />
@endsection
