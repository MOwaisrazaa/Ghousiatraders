@extends('polani.layout')

@php
  $footer = \App\Models\FooterSetting::getSettings();
  $whatsappPhone = preg_replace('/\D+/', '', $footer->phone ?? '');
@endphp

@section('title', 'Contact Us — Polani Fragrance')
@section('meta_description', 'We’re here to help you. Send us a message or reach out by email and phone.')

@push('head')
<style>
  /* Override default contact page styling for symmetrical grid */
  .contact-page {
    background: #0a0a0a;
    color: #f8e7d0;
    padding: 80px 0;
  }

  .contact {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Equal columns */
    gap: 30px;
    align-items: stretch; /* Equal height */
  }

  .contact__card,
  .contact__info {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(212, 166, 88, 0.16);
    border-radius: 20px;
    padding: 40px; /* Spacious, premium padding */
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
  }

  .contact-form {
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: space-between;
    gap: 20px;
  }

  .field input,
  .field textarea {
    border: 1px solid rgba(212, 166, 88, 0.22);
    border-radius: 10px;
    padding: 14px 16px;
    background: rgba(255, 255, 255, 0.05);
    color: #f8e7d0;
    font-size: 0.95rem;
    transition: all 0.25s ease;
    outline: none;
  }

  .field input::placeholder,
  .field textarea::placeholder {
    color: rgba(248, 231, 208, 0.3);
  }

  .field input:focus,
  .field textarea:focus {
    border-color: #d4a658;
    background: rgba(255, 255, 255, 0.07);
    box-shadow: 0 0 0 4px rgba(212, 166, 88, 0.12);
  }

  .field__label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(248, 231, 208, 0.5);
    margin-bottom: 4px;
  }

  .contact__title {
    font-family: 'Playfair Display', serif;
    font-size: 2.1rem;
    font-weight: 600;
    color: #f8e7d0;
    margin-bottom: 24px;
    letter-spacing: 0.01em;
  }

  .info-row {
    border-top: 1px solid rgba(212, 166, 88, 0.15);
    padding: 20px 0;
  }

  .info-row:first-of-type {
    border-top: none;
    padding-top: 0;
  }

  .info-row__icon {
    background: rgba(212, 166, 88, 0.12);
    border: 1px solid rgba(212, 166, 88, 0.25);
    color: #d4a658;
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }

  .info-row__icon svg {
    stroke: currentColor !important;
  }

  .info-row:hover .info-row__icon {
    background: #d4a658;
    color: #111;
    border-color: #d4a658;
  }

  .info-row__title {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #d4a658;
    margin-bottom: 6px;
  }

  .info-row__text {
    color: rgba(248, 231, 208, 0.75);
    font-size: 0.95rem;
    line-height: 1.6;
  }

  .contact-btn {
    height: 50px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    transition: all 0.3s ease;
  }

  @media (max-width: 991px) {
    .contact {
      grid-template-columns: 1fr;
      gap: 24px;
    }
    .contact__card,
    .contact__info {
      padding: 30px 24px;
    }
  }
</style>
@endpush

@section('content')
  <x-polani.page-banner
    page-key="contact"
    eyebrow="GET IN TOUCH"
    title="We’re Here To Help You"
    subtitle="Have a question, suggestion, or need assistance? We would love to hear from you."
    fallback-image="polani/assets/contact-hero.svg"
    image-position="center center"
  />

  <section class="contact-page">
    <div class="container contact">
      <div class="contact__card">
        <div class="contact__title">We’d Love To Hear From You</div>
        <form class="contact-form" data-contact-form>
          <label class="field">
            <span class="field__label">Full Name</span>
            <input type="text" name="name" placeholder="Your full name" required />
          </label>
          <label class="field">
            <span class="field__label">Email Address</span>
            <input type="email" name="email" placeholder="Your email address" required />
          </label>
          <label class="field">
            <span class="field__label">Subject</span>
            <input type="text" name="subject" placeholder="How can we help you?" required />
          </label>
          <label class="field">
            <span class="field__label">Message</span>
            <textarea name="message" rows="6" placeholder="Type your message here…" required></textarea>
          </label>
          <button class="btn btn--primary w-100 contact-btn" type="submit">Send Message</button>
          <div class="form-note muted" data-form-note hidden>Your message has been saved (demo).</div>
        </form>
      </div>

      <aside class="contact__info" aria-label="Contact info">
        <div class="info-row">
          <div class="info-row__icon" aria-hidden="true" data-icon="mail"></div>
          <div>
            <div class="info-row__title">Email Us</div>
            <div class="info-row__text">polanifragnance@gmail.com</div>
            <div class="info-row__text">support@polanifragrance.com</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row__icon" aria-hidden="true" data-icon="phone"></div>
          <div>
            <div class="info-row__title">Call Us</div>
            <div class="info-row__text">{{ $footer->phone }}</div>
            <div class="info-row__text">Mon – Sat: 9AM – 9PM</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row__icon" aria-hidden="true" data-icon="pin"></div>
          <div>
            <div class="info-row__title">Visit Us</div>
            <div class="info-row__text">Dany Craft Tower, 1st Floor</div>
            <div class="info-row__text">Shop no. F6, M.A Jinnah Road</div>
            <div class="info-row__text">Karachi, Pakistan</div>
          </div>
        </div>
        <div class="info-row">
          <div class="info-row__icon" aria-hidden="true" data-icon="clock"></div>
          <div>
            <div class="info-row__title">Business Hours</div>
            <div class="info-row__text">Monday – Saturday: 9:00 AM – 9:00 PM</div>
            <div class="info-row__text">Sunday: Closed</div>
          </div>
        </div>

        <a class="btn btn--primary w-100 contact-btn" href="https://wa.me/{{ $whatsappPhone }}?text={{ rawurlencode('Assalam o Alaikum, I want to order from Polani Fragrance.') }}" target="_blank" rel="noopener noreferrer" style="gap:8px; margin-top: auto; display: inline-flex; align-items: center; justify-content: center;">
          <span class="icon" aria-hidden="true" data-icon="wa"></span> WhatsApp
        </a>
      </aside>
    </div>
  </section>

  <section class="map">
    <div class="map__frame" role="region" aria-label="Store location map">
      <iframe
        class="map__iframe"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3619.8!2d67.0099!3d24.8607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33e7f3c3b3b3b%3A0x0!2sDany+Craft+Tower%2C+Saddar%2C+Karachi!5e0!3m2!1sen!2spk!4v1700000000000!5m2!1sen!2spk"
        width="100%"
        height="100%"
        style="border:0; filter:grayscale(1) invert(0.92) contrast(0.88) brightness(0.82);"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Polani Fragrance store location"
      ></iframe>
      <div class="map__card">
        <div class="map__pin-icon" aria-hidden="true" data-icon="pin"></div>
        <div class="map__brand">POLANI FRAGRANCE</div>
        <div class="map__addr">Dany Craft Tower, 1st Floor,<br>Shop no. F6, M.A Jinnah Road,<br>Karachi, Pakistan</div>
        <a class="btn btn--ghost btn--dark" href="https://maps.google.com/?q=Dany+Craft+Tower,+MA+Jinnah+Road,+Karachi,+Pakistan" target="_blank" rel="noopener noreferrer">Get Directions</a>
      </div>
    </div>
  </section>
@endsection
