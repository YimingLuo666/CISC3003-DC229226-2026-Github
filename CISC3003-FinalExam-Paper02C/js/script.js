// ============================================================
// CISC3003 Final Exam Paper 02 - Scenario C
// C.05 Browser-side validation with JustValidate.
// C.06 Ajax email uniqueness check against php/check-email.php.
// Falls back to native HTML5 checkValidity() if JustValidate
// did not load (e.g. CDN blocked).
// ============================================================

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // SignUp / SignIn buttons on the landing page.
        document.querySelectorAll('[data-go]').forEach(function (button) {
            button.addEventListener('click', function () {
                window.location.href = button.dataset.go;
            });
        });

        if (typeof JustValidate === 'undefined') {
            attachNativeFallback('registerForm', 'registerClientMessage');
            attachNativeFallback('loginForm', 'loginClientMessage');
            attachNativeFallback('forgotForm', 'forgotClientMessage');
            attachNativeFallback('resetForm', 'resetClientMessage');
            return;
        }

        initRegisterForm();
        initLoginForm();
        initForgotForm();
        initResetForm();
    });

    // ---------- Register form (C.05 + C.06) ----------
    function initRegisterForm() {
        var form = document.getElementById('registerForm');
        if (!form) {
            return;
        }

        var validator = new JustValidate('#registerForm', {
            errorFieldCssClass: 'just-validate-error-field',
            errorLabelCssClass: 'just-validate-error-label',
            focusInvalidField: true,
        });

        validator
            .addField('#full_name', [
                { rule: 'required', errorMessage: 'Please enter your full name.' },
                { rule: 'minLength', value: 2, errorMessage: 'Name must be at least 2 characters.' },
                { rule: 'maxLength', value: 80, errorMessage: 'Name must be at most 80 characters.' },
            ])
            .addField('#email', [
                { rule: 'required', errorMessage: 'Email is required.' },
                { rule: 'email', errorMessage: 'Please enter a valid email address.' },
                {
                    validator: function (value) {
                        // C.06 Ajax: ask the server if the email is already taken.
                        return function () {
                            return checkEmailAvailable(value)
                                .then(function (result) {
                                    setAjaxMessage(result);
                                    return result.available === true;
                                })
                                .catch(function () {
                                    setAjaxMessage({ available: false, message: 'Unable to verify email.' });
                                    return false;
                                });
                        };
                    },
                    errorMessage: 'This email is already registered or could not be verified.',
                },
            ])
            .addField('#password', [
                { rule: 'required', errorMessage: 'Password is required.' },
                { rule: 'minLength', value: 8, errorMessage: 'Password must be at least 8 characters.' },
                {
                    validator: function (value) {
                        return /[A-Za-z]/.test(value) && /\d/.test(value);
                    },
                    errorMessage: 'Password must contain both letters and digits.',
                },
            ])
            .addField('#confirm_password', [
                { rule: 'required', errorMessage: 'Please confirm your password.' },
                {
                    validator: function (value) {
                        var pw = document.getElementById('password');
                        return !!pw && value === pw.value;
                    },
                    errorMessage: 'The two passwords do not match.',
                },
            ])
            .onSuccess(function () {
                form.submit();
            });

        // Live Ajax check while typing (debounced).
        var emailInput = document.getElementById('email');
        var debounceTimer = null;
        if (emailInput) {
            emailInput.addEventListener('input', function () {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(function () {
                    var value = emailInput.value.trim();
                    if (!value) {
                        setAjaxMessage(null);
                        return;
                    }
                    setAjaxMessage({ loading: true, message: 'Checking email...' });
                    checkEmailAvailable(value).then(setAjaxMessage).catch(function () {
                        setAjaxMessage({ available: false, message: 'Unable to verify email.' });
                    });
                }, 300);
            });
        }
    }

    // ---------- Login form (C.05) ----------
    function initLoginForm() {
        var form = document.getElementById('loginForm');
        if (!form) {
            return;
        }

        var validator = new JustValidate('#loginForm', {
            errorFieldCssClass: 'just-validate-error-field',
            errorLabelCssClass: 'just-validate-error-label',
        });

        validator
            .addField('#email', [
                { rule: 'required', errorMessage: 'Email is required.' },
                { rule: 'email', errorMessage: 'Please enter a valid email address.' },
            ])
            .addField('#password', [
                { rule: 'required', errorMessage: 'Password is required.' },
            ])
            .onSuccess(function () {
                form.submit();
            });
    }

    // ---------- Forgot Password form ----------
    function initForgotForm() {
        var form = document.getElementById('forgotForm');
        if (!form) {
            return;
        }

        var validator = new JustValidate('#forgotForm', {
            errorFieldCssClass: 'just-validate-error-field',
            errorLabelCssClass: 'just-validate-error-label',
        });

        validator
            .addField('#email', [
                { rule: 'required', errorMessage: 'Email is required.' },
                { rule: 'email', errorMessage: 'Please enter a valid email address.' },
            ])
            .onSuccess(function () {
                form.submit();
            });
    }

    // ---------- Reset Password form ----------
    function initResetForm() {
        var form = document.getElementById('resetForm');
        if (!form) {
            return;
        }

        var validator = new JustValidate('#resetForm', {
            errorFieldCssClass: 'just-validate-error-field',
            errorLabelCssClass: 'just-validate-error-label',
        });

        validator
            .addField('#password', [
                { rule: 'required', errorMessage: 'Password is required.' },
                { rule: 'minLength', value: 8, errorMessage: 'Password must be at least 8 characters.' },
                {
                    validator: function (value) {
                        return /[A-Za-z]/.test(value) && /\d/.test(value);
                    },
                    errorMessage: 'Password must contain both letters and digits.',
                },
            ])
            .addField('#confirm_password', [
                { rule: 'required', errorMessage: 'Please confirm your password.' },
                {
                    validator: function (value) {
                        var pw = document.getElementById('password');
                        return !!pw && value === pw.value;
                    },
                    errorMessage: 'The two passwords do not match.',
                },
            ])
            .onSuccess(function () {
                form.submit();
            });
    }

    // ---------- Ajax helper ----------
    function checkEmailAvailable(email) {
        var url = 'php/check-email.php?email=' + encodeURIComponent(email);
        return fetch(url, { credentials: 'same-origin' })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            });
    }

    function setAjaxMessage(state) {
        var target = document.getElementById('emailAjaxMessage');
        if (!target) {
            return;
        }

        target.classList.remove('is-ok', 'is-bad', 'is-loading');

        if (!state) {
            target.textContent = '';
            return;
        }

        if (state.loading) {
            target.textContent = state.message || 'Checking...';
            target.classList.add('is-loading');
            return;
        }

        target.textContent = state.message || (state.available ? 'Email is available.' : 'Email cannot be used.');
        target.classList.add(state.available ? 'is-ok' : 'is-bad');
    }

    // ---------- Fallback when JustValidate is missing ----------
    function attachNativeFallback(formId, messageId) {
        var form = document.getElementById(formId);
        var message = document.getElementById(messageId);

        if (!form) {
            return;
        }

        form.addEventListener('submit', function (event) {
            if (message) {
                message.textContent = '';
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                if (message) {
                    message.textContent = 'Please complete all required fields correctly before submitting.';
                }
                form.reportValidity();
                return;
            }

            if (formId === 'registerForm' || formId === 'resetForm') {
                var pw = document.getElementById('password');
                var confirmPw = document.getElementById('confirm_password');

                if (pw && confirmPw && pw.value !== confirmPw.value) {
                    event.preventDefault();
                    if (message) {
                        message.textContent = 'The two passwords do not match.';
                    }
                    confirmPw.focus();
                }
            }
        });
    }
})();
