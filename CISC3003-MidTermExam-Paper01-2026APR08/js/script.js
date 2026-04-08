document.addEventListener("DOMContentLoaded", function () {
    const authCard = document.querySelector(".auth-card");
    const showSigninButton = document.getElementById("showSignin");
    const showSignupButton = document.getElementById("showSignup");
    const signinForm = document.getElementById("signinForm");
    const signupForm = document.getElementById("signupForm");
    const formStatus = document.getElementById("formStatus");
    const signinView = document.querySelector(".form-view-signin");
    const signupView = document.querySelector(".form-view-signup");
    const signinPromo = document.querySelector(".promo-view-signin");
    const signupPromo = document.querySelector(".promo-view-signup");

    function setMode(mode) {
        const isSignin = mode === "signin";

        authCard.dataset.mode = isSignin ? "signin" : "signup";
        signinView.setAttribute("aria-hidden", String(!isSignin));
        signupView.setAttribute("aria-hidden", String(isSignin));
        signinPromo.setAttribute("aria-hidden", String(!isSignin));
        signupPromo.setAttribute("aria-hidden", String(isSignin));

        if (isSignin) {
            formStatus.textContent = "Sign In form active. Required fields: Email and Password.";
        } else {
            formStatus.textContent = "Sign Up form active. Required fields: Full Name, Email, and Create Password.";
        }
    }

    function handleSubmit(event) {
        event.preventDefault();

        if (!event.currentTarget.reportValidity()) {
            return;
        }

        if (event.currentTarget.id === "signinForm") {
            formStatus.textContent = "Sign In form validation passed. This is a front-end demo without server-side processing.";
        } else {
            formStatus.textContent = "Sign Up form validation passed. This is a front-end demo without server-side processing.";
        }
    }

    showSigninButton.addEventListener("click", function () {
        setMode("signin");
        document.getElementById("signin-email").focus();
    });

    showSignupButton.addEventListener("click", function () {
        setMode("signup");
        document.getElementById("signup-name").focus();
    });

    signinForm.addEventListener("submit", handleSubmit);
    signupForm.addEventListener("submit", handleSubmit);

    setMode("signin");
});
