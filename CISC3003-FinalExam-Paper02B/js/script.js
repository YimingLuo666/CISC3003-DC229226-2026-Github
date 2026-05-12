// Simple client-side validation and SignUp / SignIn button behavior.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-go]').forEach(function (button) {
        button.addEventListener('click', function () {
            window.location.href = button.dataset.go;
        });
    });

    attachValidation('contactForm', 'contactClientMessage');
    attachValidation('registerForm', 'registerClientMessage');
    attachValidation('loginForm', 'loginClientMessage');
});

function attachValidation(formId, messageId) {
    const form = document.getElementById(formId);
    const message = document.getElementById(messageId);

    if (!form || !message) {
        return;
    }

    form.addEventListener('submit', function (event) {
        message.textContent = '';

        if (!form.checkValidity()) {
            event.preventDefault();
            message.textContent = 'Please complete all required fields correctly before submitting.';
            form.reportValidity();
            return;
        }

        if (formId === 'registerForm') {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                event.preventDefault();
                message.textContent = 'The two passwords do not match.';
                confirmPassword.focus();
            }
        }
    });
}
