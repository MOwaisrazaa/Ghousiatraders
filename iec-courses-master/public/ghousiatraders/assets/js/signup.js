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
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('emailAddress');
    const countrySelect = document.getElementById('country');
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('termsAgreement');
    const signupSubmitBtn = document.getElementById('signupSubmitBtn');

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

    // State trackers to avoid showing validation errors until field has been interacted with
    const touchedFields = {
        name: false,
        email: false,
        country: false,
        phone: false,
        password: false,
        confirmPassword: false,
        terms: false
    };

    // Validation patterns and checks
    function checkName() {
        const val = nameInput ? nameInput.value.trim() : '';
        if (!val) return 'Full Name is required.';
        if (val.length < 4) return 'Full Name must be at least 4 characters.';
        if (!/^[A-Za-z\s\.\-]+$/.test(val)) return 'Full Name may only contain letters, spaces, dots and hyphens.';
        return null; // Valid
    }

    function checkEmail() {
        const val = emailInput ? emailInput.value.trim() : '';
        if (!val) return 'Email Address is required.';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) return 'Please enter a valid email address.';
        return null;
    }

    function checkCountry() {
        const val = countrySelect ? countrySelect.value : '';
        if (!val) return 'Country is required.';
        return null;
    }

    function checkPhone() {
        const val = phoneInput ? phoneInput.value.trim() : '';
        const country = countrySelect ? countrySelect.value : 'PK';
        if (!val) return 'Phone number is required.';
        if (country === 'PK') {
            if (!/^((\+92)?(0092)?(92)?(0)?)(3\d{2})(-?|\s?)(\d{7})$/.test(val)) {
                return 'Please enter a valid Pakistani phone number (e.g. 0300-1234567).';
            }
        } else {
            const cleaned = val.replace(/[^0-9+]/g, '');
            if (cleaned.length < 7 || cleaned.length > 15) {
                return 'Please enter a valid phone number (7 to 15 digits).';
            }
        }
        return null;
    }

    function checkPassword() {
        const val = passwordInput ? passwordInput.value : '';
        if (!val) return 'Password is required.';
        if (val.length < 6 || val.length > 15) return 'Password must be between 6 and 15 characters.';
        if (!/[A-Z]/.test(val)) return 'Password must contain at least one uppercase letter.';
        if (!/[a-z]/.test(val)) return 'Password must contain at least one lowercase letter.';
        if (!/[0-9]/.test(val)) return 'Password must contain at least one number.';
        if (!/[@$!%*?&#+=~]/.test(val)) return 'Password must contain at least one special character (@$!%*?&#+=~).';
        return null;
    }

    function checkConfirmPassword() {
        const val = confirmPasswordInput ? confirmPasswordInput.value : '';
        const passVal = passwordInput ? passwordInput.value : '';
        if (!val) return 'Please confirm your password.';
        if (val !== passVal) return 'Passwords do not match.';
        return null;
    }

    function checkTerms() {
        const isChecked = termsCheckbox ? termsCheckbox.checked : false;
        if (!isChecked) return 'You must agree to the Terms & Conditions and Privacy Policy.';
        return null;
    }

    // Helper functions to show/clear error messages
    function displayFieldStatus(inputElement, getErrorMsg, fieldKey) {
        if (!inputElement) return;

        const error = getErrorMsg();
        const hasError = error !== null;

        // Only display errors if the field has been touched/interacted with
        if (hasError && touchedFields[fieldKey]) {
            inputElement.classList.add('input-error');
            const parent = inputElement.closest('.input-group');
            if (parent) {
                // Find all error divs to populate them (both the persistent placeholder and dynamic ones)
                const errorMsgElements = parent.querySelectorAll('.error-msg');
                errorMsgElements.forEach(elem => {
                    elem.innerHTML = `<i data-lucide="alert-circle" style="width: 14px; height: 14px; flex-shrink:0; vertical-align: middle; margin-right: 4px;"></i> ${error}`;
                    elem.style.display = 'flex';
                });
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        } else {
            inputElement.classList.remove('input-error');
            const parent = inputElement.closest('.input-group');
            if (parent) {
                const errorMsgElements = parent.querySelectorAll('.error-msg');
                errorMsgElements.forEach(elem => {
                    elem.textContent = '';
                    elem.style.display = 'none';
                });
            }
        }
    }

    function validateField(fieldKey) {
        if (fieldKey === 'name') displayFieldStatus(nameInput, checkName, 'name');
        if (fieldKey === 'email') displayFieldStatus(emailInput, checkEmail, 'email');
        if (fieldKey === 'country') displayFieldStatus(countrySelect, checkCountry, 'country');
        if (fieldKey === 'phone') displayFieldStatus(phoneInput, checkPhone, 'phone');
        if (fieldKey === 'password') {
            displayFieldStatus(passwordInput, checkPassword, 'password');
            if (touchedFields.confirmPassword) {
                displayFieldStatus(confirmPasswordInput, checkConfirmPassword, 'confirmPassword');
            }
        }
        if (fieldKey === 'confirmPassword') displayFieldStatus(confirmPasswordInput, checkConfirmPassword, 'confirmPassword');
        if (fieldKey === 'terms') displayFieldStatus(termsCheckbox, checkTerms, 'terms');
    }

    // Revalidate the complete form state and toggle submit button
    function validateFormState() {
        const isNameValid = checkName() === null;
        const isEmailValid = checkEmail() === null;
        const isCountryValid = checkCountry() === null;
        const isPhoneValid = checkPhone() === null;
        const isPasswordValid = checkPassword() === null;
        const isConfirmPasswordValid = checkConfirmPassword() === null;
        const isTermsValid = checkTerms() === null;

        const canSubmit = isNameValid && 
                          isEmailValid && 
                          isCountryValid && 
                          isPhoneValid && 
                          isPasswordValid && 
                          isConfirmPasswordValid && 
                          isTermsValid;

        if (signupSubmitBtn) {
            signupSubmitBtn.disabled = !canSubmit;
        }
    }

    // Bind event listeners to input elements
    const setupInputListeners = (input, fieldKey) => {
        if (!input) return;

        // input event - update validation state and clear inline errors immediately when they type correct values
        input.addEventListener('input', () => {
            validateField(fieldKey);
            validateFormState();
        });

        // keyup event - catch any keyboard input immediately
        input.addEventListener('keyup', () => {
            validateField(fieldKey);
            validateFormState();
        });

        // change event - handles checkboxes, select tags, and password managers
        input.addEventListener('change', () => {
            touchedFields[fieldKey] = true;
            validateField(fieldKey);
            validateFormState();
        });

        // blur event - mark field as touched and run full validation error displays
        input.addEventListener('blur', () => {
            touchedFields[fieldKey] = true;
            validateField(fieldKey);
            validateFormState();
        });

        // focus event - run validation
        input.addEventListener('focus', () => {
            validateFormState();
        });
    };

    setupInputListeners(nameInput, 'name');
    setupInputListeners(emailInput, 'email');
    setupInputListeners(countrySelect, 'country');
    setupInputListeners(phoneInput, 'phone');
    setupInputListeners(passwordInput, 'password');
    setupInputListeners(confirmPasswordInput, 'confirmPassword');
    setupInputListeners(termsCheckbox, 'terms');

    // Run form state check periodically and on load to support browser auto-fills
    validateFormState();
    setTimeout(validateFormState, 200);
    setTimeout(validateFormState, 500);
    setTimeout(validateFormState, 1000);
    setInterval(validateFormState, 1000);

    // 5. Form Validation on Submit
    if (signupForm) {
        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Mark all fields as touched to display any validation errors
            Object.keys(touchedFields).forEach(key => {
                touchedFields[key] = true;
            });

            // Trigger validations for all fields
            validateField('name');
            validateField('email');
            validateField('country');
            validateField('phone');
            validateField('password');
            validateField('confirmPassword');
            validateField('terms');

            // Validate form state
            validateFormState();

            const isFormValid = checkName() === null &&
                               checkEmail() === null &&
                               checkCountry() === null &&
                               checkPhone() === null &&
                               checkPassword() === null &&
                               checkConfirmPassword() === null &&
                               checkTerms() === null;

            if (isFormValid) {
                // Prevent duplicate submissions by disabling button & showing loader
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

                // Submit to the backend
                signupForm.submit();
            }
        });
    }

    // Header Sticky Scroll Listener
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

    // Mobile Menu Toggle Logic
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

    // Keyframe CSS Spinner Injection
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
