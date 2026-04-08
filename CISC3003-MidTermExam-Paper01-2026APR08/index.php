<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DC229226 Yiming Luo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <main class="page-shell">
        <section class="auth-card" data-mode="signin">
            <div class="auth-panel auth-panel-form">
                <div class="form-view form-view-signin" aria-hidden="false">
                    <h1>Log In</h1>

                    <div class="social-links">
                        <a href="#" aria-label="Facebook login">f</a>
                        <a href="#" aria-label="Google login">G+</a>
                        <a href="#" aria-label="LinkedIn login">in</a>
                    </div>

                    <p class="form-intro">Use your account to sign in</p>

                    <form id="signinForm" class="account-form" action="#" method="post">
                        <input id="signin-email" name="signin-email" type="email" placeholder="Email" autocomplete="email" required>
                        <input
                            id="signin-password"
                            name="signin-password"
                            type="password"
                            placeholder="Password"
                            autocomplete="current-password"
                            required
                        >
                        <a class="form-link" href="#">Forgot Password?</a>
                        <button class="submit-button" type="submit">SIGN IN</button>
                    </form>
                </div>

                <div class="form-view form-view-signup" aria-hidden="true">
                    <h1>Join Us</h1>

                    <div class="social-links">
                        <a href="#" aria-label="Facebook signup">f</a>
                        <a href="#" aria-label="Google signup">G+</a>
                        <a href="#" aria-label="LinkedIn signup">in</a>
                    </div>

                    <p class="form-intro">Use your email to sign up</p>

                    <form id="signupForm" class="account-form" action="#" method="post">
                        <input id="signup-name" name="signup-name" type="text" placeholder="Full Name" autocomplete="name" required>
                        <input id="signup-email" name="signup-email" type="email" placeholder="Email" autocomplete="email" required>
                        <input
                            id="signup-password"
                            name="signup-password"
                            type="password"
                            placeholder="Create Password"
                            autocomplete="new-password"
                            required
                        >
                        <button class="submit-button" type="submit">REGISTER</button>
                    </form>
                </div>
            </div>

            <div class="auth-panel auth-panel-promo">
                <div class="promo-view promo-view-signin" aria-hidden="false">
                    <h2>Welcome!</h2>
                    <img src="images/unsecure_10399884.png" alt="Envelope and lock illustration">
                    <p>Enter your details to start your journey</p>
                    <button id="showSignup" class="switch-button" type="button">SIGN UP</button>
                </div>

                <div class="promo-view promo-view-signup" aria-hidden="true">
                    <h2>Hello , Again!</h2>
                    <img src="images/website_7376495.png" alt="User profile illustration">
                    <p>Log in to stay connected with us</p>
                    <button id="showSignin" class="switch-button" type="button">SIGN IN</button>
                </div>
            </div>
        </section>

        <p id="formStatus" class="visually-hidden" aria-live="polite">
            Sign In form active. Required fields: Email and Password.
        </p>
    </main>

    <footer class="page-footer">
        <p>CISC3003 Web Programming: DC229226 Yiming Luo 2026</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
