@extends('ghousiatraders.layouts.app')

@section('title', 'Privacy Policy | Ghousia Traders')

@push('head')
<style>
    /* Privacy Policy Page Styles */
    .privacy-page {
        background-color: #fffcf8;
        padding: 40px 0 80px 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #351b0d;
    }

    .privacy-container {
        max-width: var(--container-width, 1200px);
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Breadcrumb */
    .privacy-breadcrumb {
        font-size: 0.88rem;
        color: #8a7355;
        margin-bottom: 24px;
        font-weight: 500;
    }

    .privacy-breadcrumb a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .privacy-breadcrumb a:hover {
        color: #d7a64a;
    }

    .privacy-breadcrumb span {
        margin: 0 8px;
        color: #d1c1ad;
    }

    /* Hero Section Banner */
    .privacy-hero-card {
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

    .privacy-hero-left {
        display: flex;
        flex-direction: column;
    }

    .privacy-hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #351b0d;
        margin: 0;
        line-height: 1.2;
    }

    .privacy-hero-line {
        width: 60px;
        height: 3px;
        background-color: #8a7355;
        margin: 16px 0;
        border-radius: 2px;
    }

    .privacy-hero-desc {
        color: #654c38;
        font-size: 1rem;
        line-height: 1.6;
        margin: 0;
    }

    .privacy-hero-right {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .privacy-hero-img {
        width: 100%;
        max-width: 360px;
        height: auto;
        object-fit: contain;
        border-radius: 12px;
    }

    /* Two-column layout */
    .privacy-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.28fr) minmax(0, 0.72fr);
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
        gap: 8px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .policy-nav-item a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #654c38;
        text-decoration: none;
        font-size: 0.88rem;
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
        .privacy-hero-card {
            grid-template-columns: 1fr;
            padding: 30px;
            gap: 30px;
        }

        .privacy-hero-right {
            min-height: auto;
        }

        .privacy-layout-grid {
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
    }
</style>
@endpush

@section('content')
  @php
    // Easy configuration of the last updated date in a single place
    $lastUpdatedDate = 'May 17, 2024';
  @endphp

  <section class="privacy-page">
    <div class="privacy-container">
      
      <!-- Breadcrumbs -->
      <div class="privacy-breadcrumb">
          <a href="{{ route('home') }}">Home</a>
          <span>&gt;</span>
          Privacy Policy
      </div>

      <!-- Hero Banner Section -->
      <div class="privacy-hero-card">
          <div class="privacy-hero-left">
              <h1 class="privacy-hero-title">Privacy Policy</h1>
              <div class="privacy-hero-line"></div>
              <p class="privacy-hero-desc">Your privacy is important to us. This Policy explains how Ghousia Traders collects, uses, and protects your personal information when you visit our website.</p>
          </div>
          <div class="privacy-hero-right">
              <img src="{{ asset('ghousiatraders/privacy-hero.png') }}" alt="Privacy & Security Logo Composition" class="privacy-hero-img">
          </div>
      </div>

      <!-- Main Layout Grid -->
      <div class="privacy-layout-grid">
          
          <!-- Sticky Sidebar Sections -->
          <div class="sidebar-sticky-wrapper">
              <div class="sections-card">
                  <h3 class="sections-card-title">Policy Sections</h3>
                  <ul class="policy-nav-list">
                      <li class="policy-nav-item active"><a href="#collect"><i data-lucide="file-text"></i> 1. Information We Collect</a></li>
                      <li class="policy-nav-item"><a href="#use"><i data-lucide="user-check"></i> 2. How We Use Info</a></li>
                      <li class="policy-nav-item"><a href="#sharing"><i data-lucide="share-2"></i> 3. Information Sharing</a></li>
                      <li class="policy-nav-item"><a href="#cookies"><i data-lucide="cookie"></i> 4. Cookies & Tracking</a></li>
                      <li class="policy-nav-item"><a href="#security"><i data-lucide="shield-check"></i> 5. Data Security</a></li>
                      <li class="policy-nav-item"><a href="#rights"><i data-lucide="user-cog"></i> 6. Your Rights</a></li>
                      <li class="policy-nav-item"><a href="#children"><i data-lucide="baby"></i> 7. Children's Privacy</a></li>
                      <li class="policy-nav-item"><a href="#changes"><i data-lucide="edit-3"></i> 8. Policy Changes</a></li>
                      <li class="policy-nav-item"><a href="#contact"><i data-lucide="mail"></i> 9. Contact Us</a></li>
                  </ul>
              </div>
          </div>

          <!-- Main policy content card -->
          <div class="policy-content-card">
              
              <!-- 1. Information We Collect -->
              <div class="policy-content-section" id="collect">
                  <span class="policy-section-num">Section 01</span>
                  <h2 class="policy-section-heading">1. Information We Collect</h2>
                  <p class="policy-section-p">At Ghousia Traders, we gather specific personal data from you when you navigate our site, establish an account, make purchases, or reach out to us. This information is key to delivering a secure shopping environment and includes:</p>
                  <ul class="policy-section-list">
                      <li><strong>Personal Identifiers:</strong> Your full name, email address, phone number, and account credentials.</li>
                      <li><strong>Billing & Shipping Data:</strong> Precise shipping and billing addresses to handle delivery and taxation.</li>
                      <li><strong>Order Information:</strong> Records of items purchased, prices, dates, delivery progress, and payment references.</li>
                      <li><strong>Payment-Related details:</strong> Payment methods and secure transaction tokens (credit cards are processed via secure gateways).</li>
                  </ul>
              </div>

              <!-- 2. How We Use Information -->
              <div class="policy-content-section" id="use">
                  <span class="policy-section-num">Section 02</span>
                  <h2 class="policy-section-heading">2. How We Use Information</h2>
                  <p class="policy-section-p">We process your personal information to ensure premium operations, customer care, and order validation. Specifically, we use it for:</p>
                  <ul class="policy-section-list">
                      <li><strong>Order Processing & Logistics:</strong> Verifying checkouts, dispatching products, and coordinating shipping methods.</li>
                      <li><strong>Customer Support:</strong> Resolving issues, responding to requests, and answering product queries.</li>
                      <li><strong>Account Management:</strong> Secure sign-in processes, wishlist retention, and profiles settings.</li>
                      <li><strong>Service Improvement & Analytics:</strong> Optimizing website design, page performance, and visual elements.</li>
                      <li><strong>Marketing Communication:</strong> Sending exclusive discounts and alerts with your consent.</li>
                  </ul>
              </div>

              <!-- 3. Information Sharing and Disclosure -->
              <div class="policy-content-section" id="sharing">
                  <span class="policy-section-num">Section 03</span>
                  <h2 class="policy-section-heading">3. Information Sharing and Disclosure</h2>
                  <p class="policy-section-p">Ghousia Traders holds your trust in the highest regard. We enforce a strict non-selling rule on your data:</p>
                  <p class="policy-section-p">We do not sell, rent, or trade your personal information with third parties. Your details are only shared with trusted courier services (such as TCS) and payment gateway providers solely to fulfill orders, process payments, and ensure safe delivery.</p>
              </div>

              <!-- 4. Cookies and Tracking Technologies -->
              <div class="policy-content-section" id="cookies">
                  <span class="policy-section-num">Section 04</span>
                  <h2 class="policy-section-heading">4. Cookies and Tracking Technologies</h2>
                  <p class="policy-section-p">We utilize cookies and tracking technologies to enhance user interaction, maintain cart contents, and analyze server performance:</p>
                  <p class="policy-section-p">Cookies store small browser markers to recognize active user sessions, secure cart items, and monitor traffic analytics. You can adjust your browser properties to restrict cookies, though it may disable cart persistence and specific page layouts.</p>
              </div>

              <!-- 5. Data Security -->
              <div class="policy-content-section" id="security">
                  <span class="policy-section-num">Section 05</span>
                  <h2 class="policy-section-heading">5. Data Security</h2>
                  <p class="policy-section-p">We employ rigorous structural, technical, and organizational measures to guarantee that customer records are completely secure:</p>
                  <p class="policy-section-p">All checkout transactions utilize industry-standard Secure Socket Layer (SSL) encryption. Passwords stored in our database are securely hashed using bcrypt encryption to prevent unauthorized access or system vulnerabilities.</p>
              </div>

              <!-- 6. Your Rights and Choices -->
              <div class="policy-content-section" id="rights">
                  <span class="policy-section-num">Section 06</span>
                  <h2 class="policy-section-heading">6. Your Rights and Choices</h2>
                  <p class="policy-section-p">You possess full authority over the personal records you disclose to Ghousia Traders:</p>
                  <p class="policy-section-p">You hold the right to access your stored profiles, request edits to inaccurate fields, delete your registered account, or opt-out of marketing circulars. Contact us directly to exercise these rights immediately.</p>
              </div>

              <!-- 7. Children’s Privacy -->
              <div class="policy-content-section" id="children">
                  <span class="policy-section-num">Section 07</span>
                  <h2 class="policy-section-heading">7. Children’s Privacy</h2>
                  <p class="policy-section-p">Although we sell premium baby-care items and children's ride-on toys, our website is intended only for parent or guardian accounts:</p>
                  <p class="policy-section-p">The website is not intended for children under 13, and personal information is not knowingly collected from them. If a parent discovers a child has registered, contact us to remove their account data.</p>
              </div>

              <!-- 8. Changes to This Policy -->
              <div class="policy-content-section" id="changes">
                  <span class="policy-section-num">Section 08</span>
                  <h2 class="policy-section-heading">8. Changes to This Policy</h2>
                  <p class="policy-section-p">To adapt to legal revisions or platform updates, we reserve the right to revise this policy statement:</p>
                  <p class="policy-section-p">The policy may be updated periodically, and any modifications will be instantly posted on this page with the corresponding updated date below.</p>
              </div>

              <!-- 9. Contact Us -->
              <div class="policy-content-section" id="contact">
                  <span class="policy-section-num">Section 09</span>
                  <h2 class="policy-section-heading">9. Contact Us</h2>
                  <p class="policy-section-p">If you have any questions or feedback regarding our Privacy Policy or data security measures, please contact us:</p>
                  <p class="policy-section-p">Email our support desk at <strong>info@ghousiatraders.com</strong> or call our hotline at <strong>0321-1234567</strong>.</p>
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
