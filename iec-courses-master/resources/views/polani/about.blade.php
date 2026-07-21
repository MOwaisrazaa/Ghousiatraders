@extends('polani.layout')

@section('title', 'Our Story — Polani Fragrance')
@section('meta_description', 'The journey of Polani Fragrance — from a dream to Pakistan\'s first Extrait de Parfum brand.')

@section('content')
<style>
  /* ═══════════════════════════════════
     OUR STORY PAGE — Dark / Gold Theme
  ═══════════════════════════════════ */
  .os-page { background: #0e0c09; color: #f8e7d0; }

  /* ── Hero ── */
  .os-hero {
    background: #0e0c09;
    text-align: center;
    padding: 72px 24px 56px;
    border-bottom: 1px solid rgba(212,166,88,0.12);
  }
  .os-hero__eyebrow {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: rgba(212,166,88,0.7);
    margin-bottom: 12px;
  }
  .os-hero__title {
    font-size: clamp(3rem, 8vw, 6rem);
    font-weight: 900;
    letter-spacing: -0.02em;
    line-height: 1;
    color: #f8e7d0;
    margin: 0 0 4px;
  }
  .os-hero__title-gold { color: #d4a658; }
  .os-hero__brand-sub {
    font-size: 0.82rem;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: rgba(212,166,88,0.5);
    margin-bottom: 22px;
  }
  .os-hero__ornament {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin: 18px auto;
    max-width: 340px;
  }
  .os-hero__ornament-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(212,166,88,0.5));
  }
  .os-hero__ornament-line:last-child {
    background: linear-gradient(90deg, rgba(212,166,88,0.5), transparent);
  }
  .os-hero__ornament-gem { color: #d4a658; font-size: 1rem; }
  .os-hero__tagline {
    max-width: 520px;
    margin: 0 auto;
    font-size: 1rem;
    line-height: 1.75;
    color: rgba(248,231,208,0.6);
  }

  /* ── Main body ── */
  .os-body {
    padding: 72px 0 0;
    background: #0e0c09;
  }
  .os-container {
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 28px;
  }

  /* ── Two-column grid ── */
  .os-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    align-items: start;
  }
  @media(max-width: 860px) {
    .os-grid { grid-template-columns: 1fr; gap: 48px; }
  }

  /* ── Timeline ── */
  .os-timeline { display: flex; flex-direction: column; gap: 32px; }
  .os-step { display: flex; gap: 18px; align-items: flex-start; }
  .os-step__icon {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    border-radius: 50%;
    border: 1.5px solid rgba(212,166,88,0.45);
    background: rgba(212,166,88,0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
  }
  .os-step__icon svg {
    width: 22px;
    height: 22px;
    stroke: #d4a658;
    fill: none;
    stroke-width: 1.6;
    stroke-linecap: round;
    stroke-linejoin: round;
  }
  .os-step__content {}
  .os-step__title {
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #d4a658;
    margin-bottom: 6px;
  }
  .os-step__text {
    font-size: 0.92rem;
    line-height: 1.72;
    color: rgba(248,231,208,0.65);
  }

  /* ── Right column ── */
  .os-right { display: flex; flex-direction: column; gap: 28px; }

  /* Photo card */
  .os-photo-card {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(212,166,88,0.15);
    background: #1a1208;
    aspect-ratio: 4/3;
    position: relative;
  }
  .os-photo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.6s ease;
  }
  .os-photo-card:hover img { transform: scale(1.04); }
  .os-photo-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(10,8,4,0.7) 100%);
  }

  /* Quote card */
  .os-quote-card {
    background: #141008;
    border: 1px solid rgba(212,166,88,0.2);
    border-radius: 20px;
    padding: 36px 32px;
  }
  .os-quote-card__icon {
    font-size: 3.5rem;
    line-height: 1;
    color: #d4a658;
    font-family: Georgia, serif;
    margin-bottom: 4px;
    display: block;
  }
  .os-quote-card__eyebrow {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: rgba(212,166,88,0.5);
    margin-bottom: 14px;
  }
  .os-quote-card__headline {
    font-size: clamp(1.3rem, 3vw, 1.8rem);
    font-weight: 900;
    line-height: 1.2;
    color: #f8e7d0;
    letter-spacing: -0.01em;
    margin-bottom: 18px;
  }
  .os-quote-card__rule {
    width: 40px;
    height: 2px;
    background: linear-gradient(90deg, #d4a658, transparent);
    border-radius: 2px;
    margin-bottom: 16px;
  }
  .os-quote-card__text {
    font-size: 0.92rem;
    line-height: 1.72;
    color: rgba(248,231,208,0.6);
  }

  /* ── Gallery ── */
  .os-gallery {
    margin-top: 72px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 4px;
  }
  @media(max-width: 640px) {
    .os-gallery { grid-template-columns: repeat(2, 1fr); }
  }
  .os-gallery__item {
    aspect-ratio: 1;
    overflow: hidden;
    background: #1a1208;
  }
  .os-gallery__item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
  }
  .os-gallery__item:hover img { transform: scale(1.07); }

  /* ── Footer tagline ── */
  .os-tagline {
    background: #0e0c09;
    border-top: 1px solid rgba(212,166,88,0.1);
    text-align: center;
    padding: 56px 24px;
  }
  .os-tagline__icon-ring {
    width: 58px;
    height: 58px;
    border: 1.5px solid rgba(212,166,88,0.45);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
  }
  .os-tagline__icon-ring svg {
    width: 26px;
    height: 26px;
    stroke: #d4a658;
    fill: none;
    stroke-width: 1.5;
    stroke-linecap: round;
  }
  .os-tagline__text {
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: rgba(212,166,88,0.7);
  }
  .os-tagline__ornament {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    max-width: 300px;
    margin: 14px auto 0;
  }
  .os-tagline__ornament::before,
  .os-tagline__ornament::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(212,166,88,0.3);
  }
  .os-tagline__ornament span { color: #d4a658; font-size: 0.9rem; }
</style>

<div class="os-page">

  {{-- ── HERO ── --}}
  <div class="os-hero">
    <div class="os-hero__eyebrow">The Journey of</div>
    <h1 class="os-hero__title">POLANI<br><span class="os-hero__title-gold">FRAGRANCE</span></h1>
    <div class="os-hero__brand-sub">Est. 2023 · Pakistan</div>
    <div class="os-hero__ornament">
      <div class="os-hero__ornament-line"></div>
      <span class="os-hero__ornament-gem">✦</span>
      <div class="os-hero__ornament-line"></div>
    </div>
    <p class="os-hero__tagline">
      Every great fragrance has a story.<br>
      Ours is a journey of passion, patience and<br>
      a dream that slowly turned into a brand.
    </p>
  </div>

  {{-- ── BODY ── --}}
  <div class="os-body">
    <div class="os-container">
      <div class="os-grid">

        {{-- Left: Timeline --}}
        <div class="os-timeline">

          <div class="os-step">
            <div class="os-step__icon">
              <svg viewBox="0 0 24 24"><path d="M12 2C8 2 4 6 4 10c0 5 8 12 8 12s8-7 8-12c0-4-4-8-8-8z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="os-step__content">
              <div class="os-step__title">The Dream</div>
              <div class="os-step__text">From a young age, Shayan Polani dreamed of building his own business. With limited resources, he started his professional journey with a job, but the dream never faded.</div>
            </div>
          </div>

          <div class="os-step">
            <div class="os-step__icon">
              <svg viewBox="0 0 24 24"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
            </div>
            <div class="os-step__content">
              <div class="os-step__title">The Passion</div>
              <div class="os-step__text">A deep love for fragrance inspired him to learn the art of perfumery. He explored notes, blends, formulation techniques and everything required to create a scent that leaves a lasting impression.</div>
            </div>
          </div>

          <div class="os-step">
            <div class="os-step__icon">
              <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div class="os-step__content">
              <div class="os-step__title">The Beginning — 2023</div>
              <div class="os-step__text">In 2023, he started crafting his own perfumes and selling on a small scale. It was a humble beginning fueled by passion, dedication and a desire to create something meaningful.</div>
            </div>
          </div>

          <div class="os-step">
            <div class="os-step__icon">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="os-step__content">
              <div class="os-step__title">The Encouragement</div>
              <div class="os-step__text">Positive feedback and heartfelt reviews from customers gave the confidence to keep moving forward and dream bigger.</div>
            </div>
          </div>

          <div class="os-step">
            <div class="os-step__icon">
              <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
            </div>
            <div class="os-step__content">
              <div class="os-step__title">The Milestone — 2024</div>
              <div class="os-step__text">In 2024, the dream took its official shape as the company was registered under the name Polani Fragrance.</div>
            </div>
          </div>

          <div class="os-step">
            <div class="os-step__icon">
              <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div class="os-step__content">
              <div class="os-step__title">The Brand Today</div>
              <div class="os-step__text">Polani Fragrance offers a complete range of Extrait de Parfum — the first brand in Pakistan to introduce an entire collection in Extrait concentration. We also offer customization, allowing you to create a fragrance that is truly yours.</div>
            </div>
          </div>

        </div>

        {{-- Right: Photo + Quote --}}
        <div class="os-right">

          <div class="os-photo-card">
            <img
              src="{{ asset('polani/assets/founder.jpg') }}"
              alt="Shayan Polani — Founder of Polani Fragrance"
              style="object-position: center top;"
            >
            <div class="os-photo-card__overlay"></div>
          </div>

          <div class="os-quote-card">
            <span class="os-quote-card__icon">"</span>
            <div class="os-quote-card__eyebrow">This is more than a business.</div>
            <div class="os-quote-card__headline">
              It is a dream that turned into reality.
            </div>
            <div class="os-quote-card__rule"></div>
            <div class="os-quote-card__text">
              From a small start to a growing fragrance brand, the journey continues
              with the same belief — quality, passion and trust.
            </div>
          </div>

        </div>
      </div>

      {{-- Gallery --}}
      <div class="os-gallery">
        <div class="os-gallery__item">
          <img src="{{ asset('polani/assets/cat-attars.jpeg') }}" alt="Polani Attars" loading="lazy">
        </div>
        <div class="os-gallery__item">
          <img src="{{ asset('polani/assets/cat-oud.jpeg') }}" alt="Polani Oud" loading="lazy">
        </div>
        <div class="os-gallery__item">
          <img src="{{ asset('polani/assets/cat-women.jpeg') }}" alt="Polani Women" loading="lazy">
        </div>
        <div class="os-gallery__item">
          <img src="{{ asset('polani/assets/cat-candles.jpg') }}" alt="Scented Candles" loading="lazy">
        </div>
      </div>
    </div>
  </div>

  {{-- ── FOOTER TAGLINE ── --}}
  <div class="os-tagline">
    <div class="os-tagline__icon-ring">
      <svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 1 7 7c0 5-7 13-7 13S5 14 5 9a7 7 0 0 1 7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
    </div>
    <div class="os-tagline__text">More than a fragrance, it's a statement.</div>
    <div class="os-tagline__ornament"><span>✦</span></div>
  </div>

</div>
@endsection
