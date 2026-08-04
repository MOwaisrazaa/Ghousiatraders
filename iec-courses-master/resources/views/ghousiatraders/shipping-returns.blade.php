@extends('ghousiatraders.layouts.app')

@section('title', 'Return & Shipping Policy — Ghousia Traders')

@push('head')
<style>
    /* Shipping, Returns & Exchanges Page Styles (Matches Ghousia Traders Design System) */
    .policy-page {
        background-color: #fffcf8;
        padding: 40px 0 80px 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #351b0d;
    }

    .policy-container {
        max-width: var(--container-width, 1200px);
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Breadcrumb */
    .policy-breadcrumb {
        font-size: 0.88rem;
        color: #8a7355;
        margin-bottom: 24px;
        font-weight: 500;
    }

    .policy-breadcrumb a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .policy-breadcrumb a:hover {
        color: #d7a64a;
    }

    .policy-breadcrumb span {
        margin: 0 8px;
        color: #d1c1ad;
    }

    /* Hero Section Banner */
    .policy-hero-card {
        background: #fff8ee;
        border: 1px solid rgba(215, 166, 74, 0.25);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 40px;
        margin-bottom: 40px;
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(300px, 0.75fr);
        gap: 40px;
        align-items: center;
        overflow: hidden;
    }

    .policy-hero-left {
        display: flex;
        flex-direction: column;
    }

    .policy-hero-title {
        font-size: 2.6rem;
        font-weight: 800;
        color: #351b0d;
        margin: 0;
        line-height: 1.2;
    }

    .policy-hero-line {
        width: 60px;
        height: 3px;
        background-color: #8a7355;
        margin: 16px 0;
        border-radius: 2px;
    }

    .policy-hero-desc {
        color: #654c38;
        font-size: 1rem;
        line-height: 1.6;
        margin: 0;
    }

    .policy-hero-right {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .policy-hero-img {
        width: 100%;
        max-width: 360px;
        height: auto;
        object-fit: contain;
        border-radius: 14px;
    }

    /* Two-column layout */
    .policy-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.3fr) minmax(0, 0.7fr);
        gap: 30px;
        align-items: start;
    }

    /* Sidebar navigation card */
    .sidebar-sticky-wrapper {
        position: sticky;
        top: 100px;
        z-index: 10;
    }

    .sections-card {
        background: #ffffff;
        border: 1px solid rgba(215, 166, 74, 0.22);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    }

    .sections-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #351b0d;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid rgba(215, 166, 74, 0.15);
    }

    .policy-nav-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .policy-nav-item a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        color: #654c38;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 8px;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .policy-nav-item a i {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: #8a7355;
        transition: color 0.2s ease;
    }

    .policy-nav-item a:hover {
        color: #351b0d;
        background: #fdf5e6;
    }

    .policy-nav-item.active a {
        color: #351b0d;
        background: #fff3df;
        border-left-color: #8a7355;
    }

    .policy-nav-item.active a i {
        color: #44240f;
    }

    /* Main Content Card */
    .policy-content-card {
        background: #ffffff;
        border: 1px solid rgba(215, 166, 74, 0.22);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    }

    .policy-content-section {
        scroll-margin-top: 110px;
        margin-bottom: 35px;
        border-top: 1.5px solid rgba(215, 166, 74, 0.1);
        padding-top: 30px;
    }

    .policy-content-section:first-child {
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }

    .policy-section-num {
        font-size: 0.82rem;
        font-weight: 800;
        color: #d7a64a;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: block;
        margin-bottom: 6px;
    }

    .policy-section-heading {
        font-size: 1.4rem;
        font-weight: 800;
        color: #351b0d;
        margin: 0 0 16px 0;
    }

    .policy-section-p {
        font-size: 0.95rem;
        line-height: 1.65;
        color: #554030;
        margin: 0 0 16px 0;
    }

    .policy-section-list {
        margin: 0 0 16px 20px;
        padding: 0;
    }

    .policy-section-list li {
        font-size: 0.92rem;
        line-height: 1.6;
        color: #554030;
        margin-bottom: 8px;
    }

    .policy-action-btn-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .policy-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #351B0D;
        color: #FFFFFF !important;
        padding: 10px 22px;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(53, 27, 13, 0.15);
    }

    .policy-btn-primary:hover {
        background: #5C3E21;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(53, 27, 13, 0.25);
    }

    .policy-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFF8EE;
        color: #351B0D !important;
        border: 1.5px solid rgba(215, 166, 74, 0.4);
        padding: 10px 22px;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .policy-btn-secondary:hover {
        background: #FDF5E6;
        border-color: #D7A64A;
        transform: translateY(-2px);
    }

    .last-updated-box {
        margin-top: 40px;
        background: #fff8ee;
        border: 1.5px solid rgba(215, 166, 74, 0.18);
        border-radius: 10px;
        padding: 14px 20px;
        font-size: 0.88rem;
        color: #8a7355;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Responsive Queries */
    @media (max-width: 991px) {
        .policy-hero-card {
            grid-template-columns: 1fr;
            padding: 30px;
            gap: 30px;
        }

        .policy-hero-right {
            min-height: auto;
        }

        .policy-layout-grid {
            grid-template-columns: 1fr;
        }

        .sidebar-sticky-wrapper {
            position: static;
            margin-bottom: 30px;
        }

        .sections-card {
            padding: 16px;
        }

        .policy-nav-list {
            flex-direction: row;
            overflow-x: auto;
            flex-wrap: nowrap;
            gap: 8px;
            padding-bottom: 8px;
        }

        .policy-nav-item {
            flex: 0 0 auto;
        }

        .policy-nav-item a {
            padding: 8px 16px;
            border-left: none;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
        }

        .policy-nav-item.active a {
            border-left-color: transparent;
            border-bottom-color: #8a7355;
        }
    }

    @media (max-width: 768px) {
        .policy-content-card {
            padding: 24px;
        }
        .policy-hero-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
  @php
    $lastUpdatedDate = 'August 2026';
    $footer = $footer ?? \App\Models\FooterSetting::getSettings();
  @endphp

  <section class="policy-page">
    <div class="policy-container">
      
      <!-- Breadcrumbs -->
      <div class="policy-breadcrumb">
          <a href="{{ route('home') }}">Home</a>
          <span>&gt;</span>
          Return & Shipping Policy
      </div>

      <!-- Hero Banner Section -->
      <div class="policy-hero-card">
          <div class="policy-hero-left">
              <h1 class="policy-hero-title">Return & Shipping Policy</h1>
              <div class="policy-hero-line"></div>
              <p class="policy-hero-desc">Learn about our delivery process, shipping timelines, returns, exchanges and refund conditions.</p>
          </div>
          <div class="policy-hero-right">
              <img src="{{ asset('ghousiatraders/assets/shop_hero.png') }}" alt="Return & Shipping Policy — Ghousia Traders" class="policy-hero-img">
          </div>
      </div>

      <!-- Main Layout Grid -->
      <div class="policy-layout-grid">
          
          <!-- Sticky Sidebar Sections -->
          <div class="sidebar-sticky-wrapper">
              <div class="sections-card">
                  <h3 class="sections-card-title">Policy Sections</h3>
                  <ul class="policy-nav-list">
                      <li class="policy-nav-item active"><a href="#coverage"><i data-lucide="map-pin"></i> 1. Shipping Coverage</a></li>
                      <li class="policy-nav-item"><a href="#charges"><i data-lucide="truck"></i> 2. Shipping Charges</a></li>
                      <li class="policy-nav-item"><a href="#delivery-time"><i data-lucide="clock"></i> 3. Delivery Time</a></li>
                      <li class="policy-nav-item"><a href="#processing"><i data-lucide="package"></i> 4. Order Processing</a></li>
                      <li class="policy-nav-item"><a href="#tracking"><i data-lucide="search"></i> 5. Order Tracking</a></li>
                      <li class="policy-nav-item"><a href="#returns-eligibility"><i data-lucide="shield-check"></i> 6. Returns Eligibility</a></li>
                      <li class="policy-nav-item"><a href="#return-process"><i data-lucide="refresh-cw"></i> 7. Return Process</a></li>
                      <li class="policy-nav-item"><a href="#exchanges"><i data-lucide="repeat"></i> 8. Exchanges</a></li>
                      <li class="policy-nav-item"><a href="#damaged-items"><i data-lucide="alert-circle"></i> 9. Damaged / Incorrect</a></li>
                      <li class="policy-nav-item"><a href="#refunds"><i data-lucide="credit-card"></i> 10. Refunds</a></li>
                      <li class="policy-nav-item"><a href="#non-returnable"><i data-lucide="x-circle"></i> 11. Non-Returnable</a></li>
                      <li class="policy-nav-item"><a href="#cancellation"><i data-lucide="slash"></i> 12. Order Cancellation</a></li>
                      <li class="policy-nav-item"><a href="#contact-us"><i data-lucide="mail"></i> 13. Contact Us</a></li>
                  </ul>
              </div>
          </div>

          <!-- Main policy content card -->
          <div class="policy-content-card">
              
              <!-- 1. Shipping Coverage -->
              <div class="policy-content-section" id="coverage">
                  <span class="policy-section-num">Section 01</span>
                  <h2 class="policy-section-heading">1. Shipping Coverage</h2>
                  <p class="policy-section-p">Ghousia Traders provides reliable delivery across supported cities, towns, and regions throughout Pakistan.</p>
                  <p class="policy-section-p">Delivery availability, shipping fees, and lead times may vary based on your city, local address, and courier service coverage in your area. During checkout, entering your delivery city and address will confirm logistics availability.</p>
              </div>

              <!-- 2. Shipping Charges -->
              <div class="policy-content-section" id="charges">
                  <span class="policy-section-num">Section 02</span>
                  <h2 class="policy-section-heading">2. Shipping Charges</h2>
                  <p class="policy-section-p">Shipping rates are determined based on destination location, package dimensions, and courier pricing tariffs:</p>
                  <ul class="policy-section-list">
                      <li><strong>Standard Delivery Charges:</strong> Displayed transparently at checkout prior to final order confirmation.</li>
                      <li><strong>Free Shipping Offers:</strong> Promotional free shipping may apply on qualified orders meeting minimum order value thresholds.</li>
                      <li><strong>No Hidden Costs:</strong> All delivery charges, cash-on-delivery fees, and tax calculations are shown before you place your order.</li>
                  </ul>
              </div>

              <!-- 3. Delivery Time -->
              <div class="policy-content-section" id="delivery-time">
                  <span class="policy-section-num">Section 03</span>
                  <h2 class="policy-section-heading">3. Delivery Time</h2>
                  <p class="policy-section-p">We strive to dispatch and deliver your orders as quickly as possible. Estimated delivery timelines across Pakistan are as follows:</p>
                  <ul class="policy-section-list">
                      <li><strong>Major Metros (Lahore, Karachi, Islamabad, Rawalpindi, Faisalabad):</strong> Estimated 2 to 4 business days.</li>
                      <li><strong>Other Cities & Towns:</strong> Estimated 4 to 7 business days.</li>
                      <li><strong>Remote & Rural Areas:</strong> Estimated 5 to 8 business days depending on local courier connectivity.</li>
                  </ul>
                  <p class="policy-section-p"><em>Please Note:</em> Estimated delivery timeframes are courier targets and may occasionally experience slight delays during public holidays, severe weather conditions, or peak shopping seasons.</p>
              </div>

              <!-- 4. Order Processing -->
              <div class="policy-content-section" id="processing">
                  <span class="policy-section-num">Section 04</span>
                  <h2 class="policy-section-heading">4. Order Processing</h2>
                  <p class="policy-section-p">Our fulfillment workflow guarantees that every product undergoes quality verification prior to dispatch:</p>
                  <ul class="policy-section-list">
                      <li><strong>Verification & Packing:</strong> Orders placed before 3:00 PM are processed the same working day. Items undergo physical inspection and safe packaging.</li>
                      <li><strong>Dispatch Notification:</strong> Once your package is handed to our courier partner, you will receive an automated notification with tracking credentials.</li>
                      <li><strong>Unforeseen Delays:</strong> If a specific product requires extended processing or temporary restock, our support team will notify you promptly.</li>
                  </ul>
              </div>

              <!-- 5. Order Tracking -->
              <div class="policy-content-section" id="tracking">
                  <span class="policy-section-num">Section 05</span>
                  <h2 class="policy-section-heading">5. Order Tracking</h2>
                  <p class="policy-section-p">You can track the live progress of your delivery at any time using your Order ID and registered phone number or email address.</p>
                  <div class="policy-action-btn-group">
                      <a href="{{ route('polani.track-order') }}" class="policy-btn-primary">
                          <i data-lucide="search"></i>
                          <span>Track Your Order</span>
                      </a>
                  </div>
              </div>

              <!-- 6. Returns Eligibility -->
              <div class="policy-content-section" id="returns-eligibility">
                  <span class="policy-section-num">Section 06</span>
                  <h2 class="policy-section-heading">6. Returns Eligibility</h2>
                  <p class="policy-section-p">Ghousia Traders provides a customer-friendly <strong>7-Day Return Policy</strong>. Items qualify for return or exchange if they meet the following criteria:</p>
                  <ul class="policy-section-list">
                      <li>The return request is initiated within <strong>7 days</strong> of delivery.</li>
                      <li>The product is completely unused, unwashed, and in its original pristine condition.</li>
                      <li>The item includes all original packaging, tags, seals, user manuals, and accessories.</li>
                      <li>Proof of purchase (Order ID or receipt) is provided.</li>
                  </ul>
              </div>

              <!-- 7. Return Process -->
              <div class="policy-content-section" id="return-process">
                  <span class="policy-section-num">Section 07</span>
                  <h2 class="policy-section-heading">7. Return Process</h2>
                  <p class="policy-section-p">To initiate a return, follow these step-by-step instructions:</p>
                  <ul class="policy-section-list">
                      <li><strong>Step 1:</strong> Contact Ghousia Traders Support via phone or email.</li>
                      <li><strong>Step 2:</strong> Share your Order ID, product details, and return reason (attach unboxing photos or video if applicable).</li>
                      <li><strong>Step 3:</strong> Once approved, pack the item securely in its original box.</li>
                      <li><strong>Step 4:</strong> Hand over the parcel to our designated courier or ship it to our return hub as advised by support.</li>
                  </ul>
                  <div class="policy-action-btn-group">
                      <a href="{{ route('polani.contact') }}" class="policy-btn-secondary">
                          <i data-lucide="headphones"></i>
                          <span>Contact Support</span>
                      </a>
                  </div>
              </div>

              <!-- 8. Exchanges -->
              <div class="policy-content-section" id="exchanges">
                  <span class="policy-section-num">Section 08</span>
                  <h2 class="policy-section-heading">8. Exchanges</h2>
                  <p class="policy-section-p">If you require a product exchange (e.g. for a different variant, color, or item), exchanges are processed subject to inventory availability:</p>
                  <p class="policy-section-p">Any price difference between items will be settled accordingly. Standard shipping charges may apply for exchange dispatches unless the exchange is caused by a Ghousia Traders error.</p>
              </div>

              <!-- 9. Damaged or Incorrect Items -->
              <div class="policy-content-section" id="damaged-items">
                  <span class="policy-section-num">Section 09</span>
                  <h2 class="policy-section-heading">9. Damaged or Incorrect Items</h2>
                  <p class="policy-section-p">If your package arrives damaged, missing parts, or with an incorrect item delivered:</p>
                  <p class="policy-section-p">Please notify our support team within <strong>24 to 48 hours</strong> of delivery with clear photographs or an unboxing video. We will arrange a free replacement dispatch or priority pickup at zero extra cost to you.</p>
              </div>

              <!-- 10. Refunds -->
              <div class="policy-content-section" id="refunds">
                  <span class="policy-section-num">Section 10</span>
                  <h2 class="policy-section-heading">10. Refunds</h2>
                  <p class="policy-section-p">Once your returned package is received and inspected at our warehouse, refunds are processed within <strong>5 to 7 business days</strong>:</p>
                  <ul class="policy-section-list">
                      <li><strong>Online Card Payments:</strong> Refunded directly to your originating bank card.</li>
                      <li><strong>Cash on Delivery (COD):</strong> Refunded securely via Easypaisa, JazzCash, or online bank transfer.</li>
                      <li><strong>Shipping Fees:</strong> Original shipping fees are non-refundable unless the return is due to a damaged or incorrect dispatch.</li>
                  </ul>
              </div>

              <!-- 11. Non-Returnable Items -->
              <div class="policy-content-section" id="non-returnable">
                  <span class="policy-section-num">Section 11</span>
                  <h2 class="policy-section-heading">11. Non-Returnable Items</h2>
                  <p class="policy-section-p">To uphold strict safety and hygiene standards for baby-care items, the following products cannot be accepted for return once opened or unsealed:</p>
                  <ul class="policy-section-list">
                      <li>Opened baby hygiene items (e.g. bottles, pacifiers, lotions, wipes, or oral hygiene products).</li>
                      <li>Items with signs of customer usage, missing accessories, broken tags, or customer-induced damage.</li>
                      <li>Clearance, final-sale, or customized products.</li>
                  </ul>
              </div>

              <!-- 12. Order Cancellation -->
              <div class="policy-content-section" id="cancellation">
                  <span class="policy-section-num">Section 12</span>
                  <h2 class="policy-section-heading">12. Order Cancellation</h2>
                  <p class="policy-section-p">Orders can be canceled free of charge before they are handed over to the courier for dispatch. Once dispatched, orders cannot be canceled in transit and must be received and processed under our standard return procedure.</p>
              </div>

              <!-- 13. Contact Us -->
              <div class="policy-content-section" id="contact-us">
                  <span class="policy-section-num">Section 13</span>
                  <h2 class="policy-section-heading">13. Contact Us</h2>
                  <p class="policy-section-p">For questions, shipping inquiries, or return assistance, reach out to Ghousia Traders Customer Care:</p>
                  <ul class="policy-section-list">
                      <li><strong>Phone / WhatsApp:</strong> {{ store_setting('footer_phone', store_setting('primary_phone', store_setting('store_phone', '0321-1234567'))) }}</li>
                      <li><strong>Email:</strong> {{ store_setting('footer_email', store_setting('support_email', store_setting('store_email', 'info@ghousiatraders.com'))) }}</li>
                      <li><strong>Store Address:</strong> {{ store_setting('footer_address', store_setting('address_line_1', 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan')) }}</li>
                      <li><strong>Support Hours:</strong> {!! nl2br(e(store_setting('footer_business_hours', store_setting('business_hours_custom_text', "Monday - Saturday: 10:00 AM - 8:00 PM (Sunday Closed)")))) !!}</li>
                  </ul>
              </div>

              <!-- Last Updated Date Box -->
              <div class="last-updated-box">
                  <i data-lucide="calendar"></i>
                  <span>Last Updated: {{ $lastUpdatedDate }}</span>
              </div>

          </div>
      </div>

    </div>
  </section>

  <!-- Bottom Horizontal Benefits Bar -->
  <section class="feature-bar-section">
      <div class="section-container feature-bar-container">
          <div class="feature-bar-grid">
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="award"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>100% Genuine Products</h4>
                      <p>Original and high quality</p>
                  </div>
              </div>
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="truck"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>Fast Delivery</h4>
                      <p>Across Pakistan</p>
                  </div>
              </div>
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="refresh-cw"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>Easy Returns</h4>
                      <p>Within 7 Days</p>
                  </div>
              </div>
              <div class="f-bar-item">
                  <div class="f-bar-icon-box">
                      <i data-lucide="shield-check"></i>
                  </div>
                  <div class="f-bar-content">
                      <h4>Secure Payments</h4>
                      <p>Safe & reliable</p>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <!-- Newsletter Pre-Footer Section -->
  <section class="pre-footer-cta-section homepage-newsletter">
      <div class="section-container">
          <div class="newsletter-fullwidth-card">
              <div class="cta-icon-container">
                  <i data-lucide="mail" class="cta-icon"></i>
              </div>
              <div class="cta-content">
                  <h3 class="cta-title">Stay Updated with Ghousia Traders</h3>
                  <p class="cta-desc">
                      Subscribe to our newsletter for exclusive offers, new arrivals, and parenting tips.
                  </p>
                  <form class="newsletter-form" id="newsletterForm" onsubmit="event.preventDefault(); alert('Thank you for subscribing to our newsletter!');">
                      <input type="email" placeholder="Enter your email address" required id="newsletterEmail">
                      <button type="submit" class="btn btn-primary">Subscribe</button>
                  </form>
                  <div class="newsletter-msg" id="newsletterMsg"></div>
              </div>
          </div>
      </div>
  </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.policy-nav-item');
    const sections = document.querySelectorAll('.policy-content-section');
    const navLinks = document.querySelectorAll('.policy-nav-item a');

    // Smooth scroll offset adjustment
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            if (targetSection) {
                const offsetTop = targetSection.getBoundingClientRect().top + window.pageYOffset - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Scroll spy updates
    function updateActiveSection() {
        let currentSectionId = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (window.pageYOffset >= sectionTop - 120) {
                currentSectionId = '#' + section.getAttribute('id');
            }
        });

        navItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && link.getAttribute('href') === currentSectionId) {
                item.classList.add('active');
                
                // Keep mobile horiz-scrollbar tab focused/scrolled in center
                if (window.innerWidth <= 991) {
                    item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            } else {
                item.classList.remove('active');
            }
        });
    }

    window.addEventListener('scroll', updateActiveSection);
    window.addEventListener('resize', updateActiveSection);
    updateActiveSection();
});
</script>
@endpush
