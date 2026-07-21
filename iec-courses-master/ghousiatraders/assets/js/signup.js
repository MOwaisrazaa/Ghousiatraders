/* ==========================================================================
   Ghousia Traders - Sign Up Page Script
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Form elements
    const signupForm = document.getElementById('signupForm');
    const firstNameInput = document.getElementById('firstName');
    const lastNameInput = document.getElementById('lastName');
    const emailInput = document.getElementById('emailAddress');
    const phoneInput = document.getElementById('phoneNumber');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('termsAgreement');
    const signupSubmitBtn = document.getElementById('signupSubmitBtn');

    // Password requirements elements
    const reqLength = document.getElementById('reqLength');
    const reqUpper = document.getElementById('reqUpper');
    const reqNumber = document.getElementById('reqNumber');
    const reqSpecial = document.getElementById('reqSpecial');

    // 1. Password Visibility Toggles
    setupPasswordToggle('passwordToggle', 'password');
    setupPasswordToggle('confirmPasswordToggle', 'confirmPassword');

    function setupPasswordToggle(toggleId, inputId) {
        const toggleBtn = document.getElementById(toggleId);
        const inputField = document.getElementById(inputId);

        if (toggleBtn && inputField) {
            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const icon = toggleBtn.querySelector('i');
                
                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    if (icon) {
                        icon.setAttribute('data-lucide', 'eye-off');
                    }
                } else {
                    inputField.type = 'password';
                    if (icon) {
                        icon.setAttribute('data-lucide', 'eye');
                    }
                }
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        }
    }

    // 2. Real-Time Password Requirements Verification
    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            
            // Length check (at least 8 characters)
            const isLengthValid = val.length >= 8;
            updateReqState(reqLength, isLengthValid);

            // Uppercase check
            const isUpperValid = /[A-Z]/.test(val);
            updateReqState(reqUpper, isUpperValid);

            // Number check
            const isNumberValid = /[0-9]/.test(val);
            updateReqState(reqNumber, isNumberValid);

            // Special character check
            const isSpecialValid = /[^A-Za-z0-9]/.test(val);
            updateReqState(reqSpecial, isSpecialValid);

            // Validate form state
            validateFormState();
        });
    }

    function updateReqState(element, isValid) {
        if (!element) return;
        const icon = element.querySelector('i');
        if (isValid) {
            element.classList.add('valid');
            if (icon) {
                icon.setAttribute('data-lucide', 'check-circle-2');
            }
        } else {
            element.classList.remove('valid');
            if (icon) {
                icon.setAttribute('data-lucide', 'circle');
            }
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // 3. Listen to input changes to update submit button state (Disabled/Enabled)
    const inputsToWatch = [
        firstNameInput,
        lastNameInput,
        emailInput,
        phoneInput,
        passwordInput,
        confirmPasswordInput,
        termsCheckbox
    ];

    inputsToWatch.forEach(input => {
        if (input) {
            input.addEventListener('input', validateFormState);
            if (input.type === 'checkbox') {
                input.addEventListener('change', validateFormState);
            }
        }
    });

    function validateFormState() {
        if (!signupSubmitBtn) return;

        const firstNameVal = firstNameInput ? firstNameInput.value.trim() : '';
        const lastNameVal = lastNameInput ? lastNameInput.value.trim() : '';
        const emailVal = emailInput ? emailInput.value.trim() : '';
        const phoneVal = phoneInput ? phoneInput.value.trim() : '';
        const passwordVal = passwordInput ? passwordInput.value : '';
        const confirmPasswordVal = confirmPasswordInput ? confirmPasswordInput.value : '';
        const isTermsChecked = termsCheckbox ? termsCheckbox.checked : false;

        // Password requirements status
        const isPassLength = passwordVal.length >= 8;
        const isPassUpper = /[A-Z]/.test(passwordVal);
        const isPassNumber = /[0-9]/.test(passwordVal);
        const isPassSpecial = /[^A-Za-z0-9]/.test(passwordVal);
        const isPasswordRulesMet = isPassLength && isPassUpper && isPassNumber && isPassSpecial;

        // Passwords match
        const isPasswordMatch = passwordVal === confirmPasswordVal && passwordVal !== '';

        // Form eligibility criteria
        const canSubmit = firstNameVal !== '' && 
                            lastNameVal !== '' && 
                            emailVal !== '' && 
                            phoneVal !== '' && 
                            isPasswordRulesMet && 
                            isPasswordMatch && 
                            isTermsChecked;

        signupSubmitBtn.disabled = !canSubmit;
    }

    // 4. Form Validation on Submit
    if (signupForm) {
        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            let isValid = true;
            clearErrors();

            const firstNameVal = firstNameInput.value.trim();
            const lastNameVal = lastNameInput.value.trim();
            const emailVal = emailInput.value.trim();
            const phoneVal = phoneInput.value.trim();
            const passwordVal = passwordInput.value;
            const confirmPasswordVal = confirmPasswordInput.value;
            const isTermsChecked = termsCheckbox.checked;

            // Validate First Name
            if (!firstNameVal) {
                showError(firstNameInput, 'First Name is required.');
                isValid = false;
            }

            // Validate Last Name
            if (!lastNameVal) {
                showError(lastNameInput, 'Last Name is required.');
                isValid = false;
            }

            // Validate Email
            if (!emailVal) {
                showError(emailInput, 'Email Address is required.');
                isValid = false;
            } else if (!validateEmailPattern(emailVal)) {
                showError(emailInput, 'Please enter a valid email address.');
                isValid = false;
            }

            // Validate Phone (Pakistani phone number format)
            if (!phoneVal) {
                showError(phoneInput, 'Phone number is required.');
                isValid = false;
            } else if (!validatePakistaniPhone(phoneVal)) {
                showError(phoneInput, 'Please enter a valid phone number (e.g. 0300-1234567).');
                isValid = false;
            }

            // Validate Password
            if (!passwordVal) {
                showError(passwordInput, 'Password is required.');
                isValid = false;
            } else {
                const isPassLength = passwordVal.length >= 8;
                const isPassUpper = /[A-Z]/.test(passwordVal);
                const isPassNumber = /[0-9]/.test(passwordVal);
                const isPassSpecial = /[^A-Za-z0-9]/.test(passwordVal);
                if (!isPassLength || !isPassUpper || !isPassNumber || !isPassSpecial) {
                    showError(passwordInput, 'Password must meet all 4 safety requirements.');
                    isValid = false;
                }
            }

            // Validate Confirm Password Match
            if (!confirmPasswordVal) {
                showError(confirmPasswordInput, 'Please confirm your password.');
                isValid = false;
            } else if (passwordVal !== confirmPasswordVal) {
                showError(confirmPasswordInput, 'Passwords do not match.');
                isValid = false;
            }

            // Validate Terms Checkbox
            if (!isTermsChecked) {
                const errorContainer = document.getElementById('termsErrorMsg');
                if (errorContainer) {
                    errorContainer.textContent = 'You must agree to the Terms & Conditions and Privacy Policy.';
                    errorContainer.style.display = 'flex';
                }
                isValid = false;
            }

            if (isValid) {
                // Submit Simulation
                const originalBtnContent = signupSubmitBtn.innerHTML;
                signupSubmitBtn.disabled = true;
                signupSubmitBtn.innerHTML = '<i data-lucide="loader-2" class="animate-spin"></i> Creating Account...';
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Add spinner animation styles
                const spinner = signupSubmitBtn.querySelector('i');
                if (spinner) {
                    spinner.style.animation = 'spin 1s linear infinite';
                }

                setTimeout(() => {
                    // Show success toast
                    showSuccessToast(firstNameVal);

                    // Reset form
                    signupForm.reset();
                    
                    // Reset requirement checks
                    updateReqState(reqLength, false);
                    updateReqState(reqUpper, false);
                    updateReqState(reqNumber, false);
                    updateReqState(reqSpecial, false);

                    // Restore button
                    signupSubmitBtn.innerHTML = originalBtnContent;
                    signupSubmitBtn.disabled = true;
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }, 1500);
            }
        });
    }

    // Helper validation functions
    function validateEmailPattern(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function validatePakistaniPhone(phone) {
        // Formats: 03XX-XXXXXXX, 03XXXXXXXXX, +923XXXXXXXXX, 00923XXXXXXXXX
        const regex = /^((\+92)?(0092)?(92)?(0)?)(3\d{2})(-?|\s?)(\d{7})$/;
        return regex.test(phone.trim());
    }

    function showError(inputElement, message) {
        if (!inputElement) return;
        inputElement.classList.add('input-error');
        const parent = inputElement.closest('.input-group');
        if (parent) {
            const errorMsgElement = parent.querySelector('.error-msg');
            if (errorMsgElement) {
                errorMsgElement.innerHTML = `<i data-lucide="alert-circle" style="width: 14px; height: 14px; flex-shrink:0;"></i> ${message}`;
                errorMsgElement.style.display = 'flex';
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
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

    // 5. Success Toast Trigger
    function showSuccessToast(userName) {
        const toast = document.getElementById('signupSuccessToast');
        if (!toast) return;

        const desc = toast.querySelector('.success-toast-desc');
        if (desc) {
            desc.textContent = `Welcome aboard, ${userName}! Your account was created successfully.`;
        }

        toast.classList.add('show');

        // Hide after 4 seconds and redirect to Sign In
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                window.location.href = 'signin.html';
            }, 500);
        }, 4000);
    }

    // 6. Mock Social Login Alert
    const socialButtons = document.querySelectorAll('.btn-social-signup');
    socialButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const provider = btn.querySelector('span') ? btn.querySelector('span').textContent.trim() : btn.textContent.trim();
            alert(`🔗 Redirecting to ${provider} authentication...`);
        });
    });

    // 7. General Header Sticky Scroll Listener
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

    // 8. Mobile Menu Toggle Logic
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

    // 9. Account buttons redirection (to point to signin.html)
    const headerAccountBtns = document.querySelectorAll('button[aria-label="Account"]');
    headerAccountBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            window.location.href = 'signin.html';
        });
    });

    // 10. Keyframe CSS Spinner Injection
    if (!document.getElementById('spinStyle')) {
        const style = document.createElement('style');
        style.id = 'spinStyle';
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .animate-spin {
                animation: spin 1s linear infinite;
            }
        `;
        document.head.appendChild(style);
    }
});
