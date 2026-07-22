@extends('ghousiatraders.layouts.app')

@section('title', 'About Us — Ghousia Traders')

@section('content')
    <main>
        <!-- 3. Hero Banner Section -->
        <section class="hero-section">
            <div class="hero-container">
                <!-- Hero Left: Text & CTAs -->
                <div class="hero-text-content">
                    @include('ghousiatraders.components.breadcrumb', [
                        'current' => 'About Us'
                    ])

                    <div class="subtitle-badge">
                        <span>ABOUT GHOUSIA TRADERS</span>
                        <span class="badge-decorator"></span>
                    </div>

                    <h1 class="hero-title">Caring for Little Moments, Creating Big Joy</h1>
                    
                    <p class="hero-description">
                        At Ghousia Traders, we believe childhood is made of little moments that deserve the very best. 
                        We curate premium baby care and ride-on toys that bring safety, comfort, and endless smiles to your family.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('polani.collection') }}" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                            Shop Our Collection <i data-lucide="arrow-right"></i>
                        </a>
                        <a href="#about-us" class="btn btn-outline" style="text-decoration: none;">Learn More About Us</a>
                    </div>
                </div>

                <!-- Hero Right: Image Collage -->
                <div class="hero-gallery">
                    <div class="gallery-item item-large">
                        <img src="{{ asset('ghousiatraders/assets/happy_family.png') }}" alt="Happy family playing with toy ride-on car">
                    </div>
                    <div class="gallery-item item-small-1">
                        <img src="{{ asset('ghousiatraders/assets/baby_products.png') }}" alt="Premium baby care products">
                    </div>
                    <div class="gallery-item item-small-2">
                        <img src="{{ asset('ghousiatraders/assets/ride_on_toys.png') }}" alt="Luxury ride-on toy car and bike">
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Our Story & Mission Section -->
        <section class="story-mission-section" id="about-us">
            <div class="section-container">
                <div class="story-mission-grid">
                    
                    <!-- Left: Our Story -->
                    <div class="story-block">
                        <div class="story-images">
                            <div class="story-img-main">
                                <img src="{{ asset('ghousiatraders/assets/baby_products.png') }}" alt="Baby care selection">
                            </div>
                            <div class="story-img-sub-container">
                                <div class="story-img-sub">
                                    <img src="{{ asset('ghousiatraders/assets/ride_on_toys.png') }}" alt="Toy car and bike close-up">
                                </div>
                                <div class="story-img-sub">
                                    <img src="{{ asset('ghousiatraders/assets/happy_family.png') }}" alt="Smiling toddler playing">
                                </div>
                            </div>
                        </div>

                        <div class="story-content">
                            <h2 class="section-title-serif">Our Story</h2>
                            <p class="story-text">
                                Founded with a passion for quality and a promise of care, Ghousia Traders was born to support 
                                modern parents with products they can truly trust.
                            </p>
                            <p class="story-text">
                                From our carefully selected baby care essentials to our exciting ride-on bikes and cars, 
                                every product is chosen with love, tested for safety, and designed to create beautiful memories.
                            </p>
                            <blockquote class="story-quote">
                                <p>"For every little giggle, every tiny step, and every big adventure."</p>
                                <cite>— The Ghousia Traders Team</cite>
                            </blockquote>
                        </div>
                    </div>

                    <!-- Right: Mission, Vision & Values -->
                    <div class="mission-block">
                        <div class="section-header-center">
                            <h2 class="section-title-serif" style="text-align: center; margin-bottom: 20px;">Our Mission, Vision & Values</h2>
                        </div>

                        <div class="values-cards-container">
                            <!-- Mission Card -->
                            <div class="value-card">
                                <div class="value-icon-wrapper">
                                    <i data-lucide="target" class="value-icon"></i>
                                </div>
                                <h3 class="value-card-title">Our Mission</h3>
                                <p class="value-card-desc">
                                    To provide premium, safe, and innovative products that support every stage of your child's growth and bring happiness to families.
                                </p>
                            </div>

                            <!-- Vision Card -->
                            <div class="value-card">
                                <div class="value-icon-wrapper">
                                    <i data-lucide="eye" class="value-icon"></i>
                                </div>
                                <h3 class="value-card-title">Our Vision</h3>
                                <p class="value-card-desc">
                                    To be the most trusted destination for baby care and ride-on toys across Pakistan, known for quality, care, and customer delight.
                                </p>
                            </div>

                            <!-- Values Card -->
                            <div class="value-card">
                                <div class="value-icon-wrapper">
                                    <i data-lucide="heart-handshake" class="value-icon"></i>
                                </div>
                                <h3 class="value-card-title">Our Values</h3>
                                <ul class="values-list">
                                    <li><i data-lucide="check" class="list-bullet"></i> Premium Quality</li>
                                    <li><i data-lucide="check" class="list-bullet"></i> Safety First</li>
                                    <li><i data-lucide="check" class="list-bullet"></i> Honest Pricing</li>
                                    <li><i data-lucide="check" class="list-bullet"></i> Customer Happiness</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 5. Product Worlds Section -->
        <section class="product-worlds-section" id="shop">
            <div class="section-container">
                <div class="section-divider-header">
                    <span class="divider-line"></span>
                    <h2 class="section-title-serif">Our Two Product Worlds</h2>
                    <span class="divider-line"></span>
                </div>

                <div class="product-worlds-grid">
                    <!-- World 1: Baby Care -->
                    <div class="world-card" id="baby-care">
                        <div class="world-image">
                            <img src="{{ asset('ghousiatraders/assets/baby_products.png') }}" alt="Baby Care Essentials">
                        </div>
                        <div class="world-info">
                            <h3 class="world-title">Baby Care Essentials</h3>
                            <p class="world-desc">Premium baby care products for daily comfort, hygiene, and gentle care.</p>
                            <a href="{{ route('polani.babycare') }}" class="btn btn-primary shop-world-btn" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center;">
                                Shop Baby Care <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- World 2: Bikes & Cars -->
                    <div class="world-card" id="ride-on-toys-section">
                        <div class="world-image">
                            <img src="{{ asset('ghousiatraders/assets/ride_on_toys.png') }}" alt="Ride-On Bikes and Cars">
                        </div>
                        <div class="world-info">
                            <h3 class="world-title">B/O Bikes & Cars</h3>
                            <p class="world-desc">Exciting ride-on toys that spark adventure, build confidence, and create memories.</p>
                            <a href="{{ route('polani.collection') }}" class="btn btn-primary shop-world-btn" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; justify-content: center;">
                                Explore Ride-On Toys <i data-lucide="arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. Why Parents Choose Section -->
        <section class="why-choose-section">
            <div class="section-container">
                <div class="section-divider-header">
                    <span class="divider-line"></span>
                    <h2 class="section-title-serif">Why Parents Choose Ghousia Traders</h2>
                    <span class="divider-line"></span>
                </div>

                <div class="features-grid">
                    <!-- Feature 1 -->
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i data-lucide="award"></i>
                        </div>
                        <h3 class="feature-title">Premium Quality</h3>
                        <p class="feature-desc">Carefully selected products for the best experience.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i data-lucide="tags"></i>
                        </div>
                        <h3 class="feature-title">Fair Prices</h3>
                        <p class="feature-desc">Quality products at honest, affordable prices.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i data-lucide="shield-check"></i>
                        </div>
                        <h3 class="feature-title">Secure Shopping</h3>
                        <p class="feature-desc">Safe payments and 100% secure checkout.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i data-lucide="headset"></i>
                        </div>
                        <h3 class="feature-title">Dedicated Support</h3>
                        <p class="feature-desc">We're here to help, before and after your purchase.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. Selection Process & Responsible Play -->
        <section class="process-play-section">
            <div class="section-container">
                <div class="process-play-grid">
                    
                    <!-- Left Column: Selection Process -->
                    <div class="process-block">
                        <div class="section-divider-header align-left">
                            <span class="divider-line"></span>
                            <h2 class="section-title-serif">Our Product Selection Process</h2>
                            <span class="divider-line"></span>
                        </div>

                        <div class="process-steps">
                            <!-- Step 1 -->
                            <div class="step-card">
                                <div class="step-number">01</div>
                                <div class="step-content">
                                    <h3 class="step-title">Careful Sourcing</h3>
                                    <p class="step-desc">We choose trusted brands and partners where quality matters.</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="step-card">
                                <div class="step-number">02</div>
                                <div class="step-content">
                                    <h3 class="step-title">Quality Testing</h3>
                                    <p class="step-desc">Products are tested for safety, durability, and performance.</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="step-card">
                                <div class="step-number">03</div>
                                <div class="step-content">
                                    <h3 class="step-title">Parent Approved</h3>
                                    <p class="step-desc">We select what we would confidently use for our own kids.</p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="step-card">
                                <div class="step-number">04</div>
                                <div class="step-content">
                                    <h3 class="step-title">Delivered with Care</h3>
                                    <p class="step-desc">Quick, reliable delivery packed with love and care.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Responsible Play -->
                    <div class="responsible-play-card">
                        <div class="play-image">
                            <img src="{{ asset('ghousiatraders/assets/happy_family.png') }}" alt="Father and toddler playing with toy car">
                        </div>
                        <div class="play-content">
                            <h3 class="play-title">Responsible Play, Happy Childhood</h3>
                            <p class="play-desc">
                                We promote safe and responsible play through easy guidelines, supervising playtime, and enjoying worry-free moments together.
                            </p>
                            <ul class="play-checklist">
                                <li>
                                    <i data-lucide="check-circle" class="check-icon"></i>
                                    <span>Age-appropriate products</span>
                                </li>
                                <li>
                                    <i data-lucide="check-circle" class="check-icon"></i>
                                    <span>BPA-free & non-toxic materials</span>
                                </li>
                                <li>
                                    <i data-lucide="check-circle" class="check-icon"></i>
                                    <span>Sturdy & durable design</span>
                                </li>
                                <li>
                                    <i data-lucide="check-circle" class="check-icon"></i>
                                    <span>Safety tested & parent approved</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 8. Testimonials Section -->
        <section class="testimonials-section">
            <div class="section-container">
                <div class="section-divider-header">
                    <span class="divider-line"></span>
                    <h2 class="section-title-serif">Loved by Parents, Trusted by Families</h2>
                    <span class="divider-line"></span>
                </div>

                <div class="testimonials-slider-container">
                    <button class="slider-arrow prev-btn" id="prevTestimonial" aria-label="Previous Testimonial">
                        <i data-lucide="chevron-left"></i>
                    </button>

                    <div class="testimonials-wrapper">
                        <!-- Card 1 -->
                        <div class="testimonial-card active" data-index="0">
                            <div class="testimonial-rating">
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "Amazing quality and on-time delivery! My baby loves the products and I love the peace of mind."
                            </p>
                            <div class="testimonial-user">
                                <div class="user-avatar">
                                    <i data-lucide="user"></i>
                                </div>
                                <div class="user-details">
                                    <h4 class="user-name">Ayesha Khan</h4>
                                    <span class="user-status">Verified Parent</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="testimonial-card" data-index="1">
                            <div class="testimonial-rating">
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "The ride-on car is a big hit at home. Sturdy, stylish and perfect for my little explorer!"
                            </p>
                            <div class="testimonial-user">
                                <div class="user-avatar">
                                    <i data-lucide="user"></i>
                                </div>
                                <div class="user-details">
                                    <h4 class="user-name">Usman Ahmed</h4>
                                    <span class="user-status">Verified Parent</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="testimonial-card" data-index="2">
                            <div class="testimonial-rating">
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                                <i data-lucide="star" class="star-fill"></i>
                            </div>
                            <p class="testimonial-text">
                                "Finally found a store I can trust for both baby care and toys. Highly recommended!"
                            </p>
                            <div class="testimonial-user">
                                <div class="user-avatar">
                                    <i data-lucide="user"></i>
                                </div>
                                <div class="user-details">
                                    <h4 class="user-name">Fiza Fatima</h4>
                                    <span class="user-status">Verified Parent</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="slider-arrow next-btn" id="nextTestimonial" aria-label="Next Testimonial">
                        <i data-lucide="chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>
    </main>
@endsection
