<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ghousia Traders | Little Essentials, Big Joy')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lora:ital,wght@0,400..700;1,400..700&family=Pinyon+Script&family=Playball&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Storefront Stylesheet -->
    <link rel="stylesheet" href="{{ asset('ghousiatraders/style.css') }}">
    @livewireStyles
    @stack('head')
</head>
<body class="ghousia-storefront">

    @include('ghousiatraders.partials.header')

    <main id="main-content">
        @include('ghousiatraders.partials.alerts')
        @yield('content')
    </main>

    @include('ghousiatraders.partials.footer')

    <!-- Storefront Toast Container -->
    <div id="storefront-toast-container" aria-live="polite"></div>

    <!-- Theme JS -->
    <script src="{{ asset('ghousiatraders/script.js') }}?v={{ filemtime(public_path('ghousiatraders/script.js')) }}"></script>
    <script>
        // Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Web Audio API Notification Sound Synthesizer
        let audioCtx = null;
        let soundEnabled = localStorage.getItem('gt_storefront_sound_enabled') !== 'false';

        function initAudioContext() {
            if (!audioCtx && (window.AudioContext || window.webkitAudioContext)) {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                audioCtx = new AudioContextClass();
            }
            if (audioCtx && audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        }

        // Initialize on user interactions to satisfy browser autoplay policy
        document.addEventListener('click', initAudioContext);
        document.addEventListener('touchstart', initAudioContext);

        function playToastSound(soundType, heading = '') {
            if (!soundEnabled) return;
            try {
                initAudioContext();
                if (!audioCtx) return;

                const now = audioCtx.currentTime;
                const isRemoval = soundType === 'amber' || soundType === 'remove' || (heading && (heading.toLowerCase().includes('remove') || heading.toLowerCase().includes('clear')));

                if (isRemoval) {
                    // Distinct Soft Downward Removal Tone (G4 -> E4 -> C4)
                    [392.00, 329.63, 261.63].forEach((freq, index) => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, now + index * 0.07);

                        gain.gain.setValueAtTime(0.001, now + index * 0.07);
                        gain.gain.linearRampToValueAtTime(0.12, now + index * 0.07 + 0.02);
                        gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.07 + 0.25);

                        osc.connect(gain);
                        gain.connect(audioCtx.destination);

                        osc.start(now + index * 0.07);
                        osc.stop(now + index * 0.07 + 0.28);
                    });
                } else if (soundType === 'success' || soundType === 'cart') {
                    // Soft Success Triad Chime (C5 -> E5 -> G5)
                    [523.25, 659.25, 783.99].forEach((freq, index) => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, now + index * 0.06);
                        
                        gain.gain.setValueAtTime(0.001, now + index * 0.06);
                        gain.gain.linearRampToValueAtTime(0.1, now + index * 0.06 + 0.02);
                        gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.06 + 0.28);
                        
                        osc.connect(gain);
                        gain.connect(audioCtx.destination);
                        
                        osc.start(now + index * 0.06);
                        osc.stop(now + index * 0.06 + 0.3);
                    });
                } else if (soundType === 'wishlist') {
                    // Light Heart Warm Pop (F#4 -> A#4)
                    [369.99, 466.16].forEach((freq, index) => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, now + index * 0.05);

                        gain.gain.setValueAtTime(0.001, now + index * 0.05);
                        gain.gain.linearRampToValueAtTime(0.09, now + index * 0.05 + 0.015);
                        gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.05 + 0.2);

                        osc.connect(gain);
                        gain.connect(audioCtx.destination);

                        osc.start(now + index * 0.05);
                        osc.stop(now + index * 0.05 + 0.22);
                    });
                } else if (soundType === 'error') {
                    // Gentle Low Warning Dual Pulse
                    [220, 196].forEach((freq, index) => {
                        const osc = audioCtx.createOscillator();
                        const gain = audioCtx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(freq, now + index * 0.08);

                        gain.gain.setValueAtTime(0.001, now + index * 0.08);
                        gain.gain.linearRampToValueAtTime(0.08, now + index * 0.08 + 0.02);
                        gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.08 + 0.22);

                        osc.connect(gain);
                        gain.connect(audioCtx.destination);

                        osc.start(now + index * 0.08);
                        osc.stop(now + index * 0.08 + 0.25);
                    });
                }
            } catch (err) {
                // Silently ignore audio context errors
            }
        }

        function toggleSoundPreference() {
            soundEnabled = !soundEnabled;
            localStorage.setItem('gt_storefront_sound_enabled', soundEnabled ? 'true' : 'false');
            updateSoundToggleButton();
        }

        function updateSoundToggleButton() {
            const btn = document.getElementById('sf-toast-sound-toggle');
            if (btn) {
                btn.innerHTML = soundEnabled ? '<i class="fas fa-volume-up"></i>' : '<i class="fas fa-volume-mute"></i>';
                btn.title = soundEnabled ? 'Mute notification sounds' : 'Unmute notification sounds';
            }
        }

        // Global Storefront Floating Product Action Card System
        window.showStorefrontToast = function(options) {
            const type = options.type || 'cart'; // 'cart', 'wishlist', 'amber', 'error', 'info'
            const heading = options.heading || (type === 'cart' || type === 'success' ? 'Added to Cart' : (type === 'wishlist' ? 'Saved to Wishlist' : (type === 'amber' ? 'Removed from Cart' : (type === 'error' ? 'Notice' : 'Store Notification'))));
            const productName = options.name || options.message || 'Product';
            const price = options.price || null;
            const quantity = options.quantity || 1;
            const subtitle = options.subtitle || (price ? `PKR ${typeof price === 'number' ? price.toLocaleString() : price} · Quantity ${quantity}` : (type === 'wishlist' ? 'You can purchase it later' : 'Action completed'));
            const actionText = options.actionText || (type === 'cart' || type === 'success' ? 'View Cart →' : (type === 'wishlist' ? 'View Wishlist →' : null));
            const actionUrl = options.actionUrl || (type === 'cart' || type === 'success' ? '/shopping-cart' : (type === 'wishlist' ? '/wishlist' : null));
            const productUrl = options.productUrl || '#';
            const image = options.image || '/ghousiatraders/assets/baby_products.png';
            const duration = options.duration || 4000;

            let container = document.getElementById('storefront-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'storefront-toast-container';
                container.setAttribute('aria-live', 'polite');
                document.body.appendChild(container);
            }

            // Create sound toggle button if not exists
            if (!document.getElementById('sf-toast-sound-toggle')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.id = 'sf-toast-sound-toggle';
                toggleBtn.className = 'sf-toast-sound-toggle-btn';
                toggleBtn.onclick = toggleSoundPreference;
                container.appendChild(toggleBtn);
                updateSoundToggleButton();
            }

            // Limit to max 3 visible cards at a time
            const activeCards = container.querySelectorAll('.sf-action-card');
            if (activeCards.length >= 3) {
                activeCards[0].remove();
            }

            // Duplicate prevention
            for (let el of activeCards) {
                const existingName = el.querySelector('.sf-card-product-name')?.textContent || '';
                const existingHeading = el.querySelector('.sf-card-heading')?.textContent || '';
                if (existingName.trim() === productName.trim() && existingHeading.trim() === heading.toUpperCase().trim()) {
                    return; // Skip duplicate card
                }
            }

            // Play sound effect
            playToastSound(type, heading);

            // Determine overlay badge icon
            let badgeIconClass = 'fa-check';
            if (type === 'wishlist') badgeIconClass = 'fa-heart';
            else if (type === 'amber') badgeIconClass = 'fa-minus';
            else if (type === 'error') badgeIconClass = 'fa-exclamation';

            // Determine card variant class
            let cardVariantClass = 'sf-card-cart';
            if (type === 'wishlist') cardVariantClass = 'sf-card-wishlist';
            else if (type === 'amber') cardVariantClass = 'sf-card-amber';
            else if (type === 'error') cardVariantClass = 'sf-card-error';

            const card = document.createElement('div');
            card.className = `sf-action-card ${cardVariantClass}`;

            let actionBtnHtml = '';
            if (actionText && actionUrl) {
                actionBtnHtml = `<a href="${actionUrl}" class="sf-card-btn-action">${actionText}</a>`;
            }

            let nameHtml = productUrl && productUrl !== '#' 
                ? `<a href="${productUrl}" class="sf-card-product-name">${productName}</a>`
                : `<div class="sf-card-product-name">${productName}</div>`;

            card.innerHTML = `
                <button type="button" class="sf-card-close-btn" aria-label="Close notification" title="Close" onclick="this.closest('.sf-action-card').remove()">&times;</button>

                <div class="sf-card-main-row">
                    <div class="sf-card-thumb-container">
                        <img src="${image}" alt="${productName}" class="sf-card-thumb" onerror="this.src='/ghousiatraders/assets/baby_products.png'">
                        <div class="sf-card-badge-overlay"><i class="fas ${badgeIconClass}"></i></div>
                    </div>
                    <div class="sf-card-info-box">
                        <div class="sf-card-heading">${heading}</div>
                        ${nameHtml}
                        <div class="sf-card-product-meta">${subtitle}</div>
                    </div>
                </div>

                ${actionBtnHtml ? `<div class="sf-card-action-row">${actionBtnHtml}</div>` : ''}

                <div class="sf-card-progress" style="animation-duration: ${duration}ms;"></div>
            `;

            container.appendChild(card);

            // Auto dismiss
            setTimeout(() => {
                if (card && card.parentNode) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(24px) scale(0.92)';
                    setTimeout(() => {
                        if (card && card.parentNode) card.remove();
                    }, 350);
                }
            }, duration);
        };

        // Universal single listener for 'show-toast' events (prevents duplicate triggers)
        let lastToastTime = 0;
        let lastToastName = '';

        function handleToastEvent(data) {
            const payload = Array.isArray(data) ? data[0] : (data?.detail ? (Array.isArray(data.detail) ? data.detail[0] : data.detail) : data);
            if (!payload) return;

            const now = Date.now();
            const name = payload.name || payload.message || payload.heading || '';
            if (name && name === lastToastName && (now - lastToastTime) < 500) {
                return; // Guard against duplicate invocation within 500ms
            }
            lastToastTime = now;
            lastToastName = name;

            window.showStorefrontToast(payload);
        }

        window.addEventListener('show-toast', handleToastEvent);

        document.addEventListener('livewire:initialized', () => {
            if (window.Livewire) {
                Livewire.hook('morph.updated', () => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                });
            }
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
