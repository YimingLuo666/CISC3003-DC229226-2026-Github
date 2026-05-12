/*
 * script.js
 * CISC3003 Final Exam Paper 02 - Scenario A
 * Adds a small visual interaction to the SignUp / SignIn buttons on index.php.
 */

document.addEventListener('DOMContentLoaded', function () {
    var signupBtn = document.getElementById('btn-signup');
    var signinBtn = document.getElementById('btn-signin');
    var hint = document.getElementById('button-hint');

    function announce(text) {
        if (hint) {
            hint.textContent = text;
        }
    }

    function highlight(active, other) {
        if (!active || !other) return;
        active.classList.add('is-active');
        other.classList.remove('is-active');
    }

    if (signupBtn) {
        signupBtn.addEventListener('mouseenter', function () {
            highlight(signupBtn, signinBtn);
            announce('Sign Up: create a new account.');
        });
        signupBtn.addEventListener('focus', function () {
            highlight(signupBtn, signinBtn);
            announce('Sign Up: create a new account.');
        });
    }

    if (signinBtn) {
        signinBtn.addEventListener('mouseenter', function () {
            highlight(signinBtn, signupBtn);
            announce('Sign In: log into an existing account.');
        });
        signinBtn.addEventListener('focus', function () {
            highlight(signinBtn, signupBtn);
            announce('Sign In: log into an existing account.');
        });
    }
});
