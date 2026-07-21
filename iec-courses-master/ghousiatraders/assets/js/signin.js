/* ==========================================================================
   Ghousia Traders - Sign In Page Script
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // 1. Password Visibility Toggle
    const passwordInput = document.getElementById('signinPassword');
    const passwordToggle = document.getElementById('passwordToggle');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const icon = passwordToggle.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (icon) {
                    icon.setAttribute('data-lucide', 'eye-off');
                }
            } else {
                passwordInput.type = 'password';
                if (icon) {
                    icon.setAttribute('data-lucide', 'eye');
                }
            }
            
            // Re-initialize icons so the data-lucide change renders
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    }

    // 2. Remember Me Cache Email functionality
    const emailInput = document.getElementById('signinEmail');
    const rememberMeCheckbox = document.getElementById('rememberMe');

    if (emailInput && rememberMeCheckbox) {
        // Load cached email if exists
        const cachedEmail = localStorage.getItem('ghousia_remembered_email');
        if (cachedEmail) {
            emailInput.value = cachedEmail;
            rememberMeCheckbox.checked = true;
        }
    }

    // 3. Form Validation
    const signinForm = document.getElementById('signinForm');
    
    if (signinForm) {
        signinForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            let isValid = true;
            const emailVal = emailInput.value.trim();
            const passwordVal = passwordInput.value;

            // Clear previous errors
            clearErrors();

            // Validate Email
            if (!emailVal) {
                showError(emailInput, 'Email Address is required.');
                isValid = false;
            } else if (!validateEmailPattern(emailVal)) {
                showError(emailInput, 'Please enter a valid email address.');
                isValid = false;
            }

            // Validate Password
            if (!passwordVal) {
                showError(passwordInput, 'Password is required.');
                isValid = false;
            } else if (passwordVal.length < 6) {
                showError(passwordInput, 'Password must be at least 6 characters long.');
                isValid = false;
            }

            if (isValid) {
                // Handle "Remember Me"
                if (rememberMeCheckbox && rememberMeCheckbox.checked) {
                    localStorage.setItem('ghousia_remembered_email', emailVal);
                } else {
                    localStorage.removeItem('ghousia_remembered_email');
                }

                // Simulate successful sign-in
                const submitBtn = signinForm.querySelector('.btn-signin');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Signing in...';

                setTimeout(() => {
                    alert('🎉 Successfully signed in!');
                    window.location.href = 'index.html';
                }, 1200);
            }
        });
    }

    // Helper functions for validation
    function validateEmailPattern(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function showError(inputElement, message) {
        inputElement.classList.add('input-error');
        const errorMsgElement = inputElement.closest('.input-group').querySelector('.error-msg');
        if (errorMsgElement) {
            errorMsgElement.textContent = message;
            errorMsgElement.style.display = 'block';
        }
    }

    function clearErrors() {
        const errorInputs = document.querySelectorAll('.form-input.input-error');
        errorInputs.forEach(input => input.classList.remove('input-error'));

        const errorMsgs = document.querySelectorAll('.error-msg');
        errorMsgs.forEach(msg => {
            msg.textContent = '';
            msg.style.display = 'none';
        });
    }

    // 4. Mock Social Login Buttons Click feedback
    const socialButtons = document.querySelectorAll('.btn-social');
    socialButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const provider = btn.textContent.trim();
            alert(`🔗 Redirecting to ${provider} authentication...`);
        });
    });

    // 5. Promo/Newsletter Strip Form Submission
    const promoForm = document.getElementById('promoNewsletterForm');
    const promoMsg = document.getElementById('promoMsg');

    if (promoForm && promoMsg) {
        promoForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const emailInput = document.getElementById('promoEmail');
            const emailValue = emailInput.value.trim();

            if (!emailValue || !validateEmailPattern(emailValue)) {
                promoMsg.className = 'promo-msg error';
                promoMsg.textContent = 'Please enter a valid email address.';
                return;
            }

            // Submitting state
            promoMsg.className = 'promo-msg';
            promoMsg.textContent = 'Subscribing...';

            setTimeout(() => {
                promoMsg.className = 'promo-msg success';
                promoMsg.textContent = '🎉 Thank you! You have successfully subscribed to our newsletter.';
                emailInput.value = '';

                // Clear message after 4 seconds
                setTimeout(() => {
                    promoMsg.textContent = '';
                    promoMsg.className = 'promo-msg';
                }, 4000);
            }, 1000);
        });
    }

    // 6. Header sticky behavior on scroll (to match global scripts)
    const mainHeader = document.querySelector('.main-header');
    if (mainHeader) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 30) {
                mainHeader.classList.add('scrolled');
            } else {
                mainHeader.classList.remove('scrolled');
            }
        });
    }

    // 7. Mobile menu toggle logic
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (icon) {
                if (navMenu.classList.contains('active')) {
                    icon.setAttribute('data-lucide', 'x');
                } else {
                    icon.setAttribute('data-lucide', 'menu');
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });
    }

    // 8. Account button navigation
    const headerAccountBtns = document.querySelectorAll('button[aria-label="Account"]');
    headerAccountBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            window.location.href = 'signin.html';
        });
    });
});
