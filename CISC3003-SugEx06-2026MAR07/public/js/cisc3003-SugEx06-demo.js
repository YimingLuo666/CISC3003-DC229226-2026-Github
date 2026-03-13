// JavaScript for Suggested Exercise 06 e-commerce pages

document.addEventListener("DOMContentLoaded", function () {
    // Mobile menu toggle shared by the e-commerce pages.
    const mobileMenuBtn = document.getElementById("mobileMenuBtn");
    const menuItems = document.getElementById("MenuItems");

    function closeMobileMenu() {
        if (menuItems) {
            menuItems.style.maxHeight = "0px";
        }
    }

    function toggleMobileMenu() {
        if (!menuItems) {
            return;
        }

        if (menuItems.style.maxHeight === "0px" || menuItems.style.maxHeight === "") {
            menuItems.style.maxHeight = "240px";
        } else {
            closeMobileMenu();
        }
    }

    if (menuItems) {
        menuItems.style.maxHeight = "0px";
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener("click", toggleMobileMenu);
    }

    document.querySelectorAll("#MenuItems a").forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 800) {
                closeMobileMenu();
            }
        });
    });

    // Smooth scrolling for same-page anchor links.
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener("click", function (event) {
            const targetId = this.getAttribute("href");

            if (!targetId || targetId === "#") {
                return;
            }

            const targetElement = document.querySelector(targetId);
            if (!targetElement) {
                return;
            }

            event.preventDefault();
            targetElement.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        });
    });

    // Product gallery behavior on the product details page.
    const productImg = document.getElementById("ProductImg");
    const galleryImages = document.querySelectorAll(".small-img");

    if (productImg && galleryImages.length > 0) {
        galleryImages.forEach(function (image) {
            image.addEventListener("click", function () {
                productImg.src = image.src;
                productImg.alt = image.alt;
            });
        });
    }

    // Login / register tab switching on the account page.
    const loginForm = document.getElementById("LoginForm");
    const regForm = document.getElementById("RegForm");
    const indicator = document.getElementById("Indicator");
    const loginTab = document.getElementById("loginTab");
    const registerTab = document.getElementById("registerTab");

    function showLoginForm() {
        if (!loginForm || !regForm || !indicator) {
            return;
        }

        regForm.style.transform = "translateX(300px)";
        loginForm.style.transform = "translateX(300px)";
        indicator.style.transform = "translateX(0px)";
    }

    function showRegisterForm() {
        if (!loginForm || !regForm || !indicator) {
            return;
        }

        regForm.style.transform = "translateX(0px)";
        loginForm.style.transform = "translateX(0px)";
        indicator.style.transform = "translateX(100px)";
    }

    if (loginForm && regForm && indicator) {
        showLoginForm();
    }

    if (loginTab) {
        loginTab.addEventListener("click", showLoginForm);
    }

    if (registerTab) {
        registerTab.addEventListener("click", showRegisterForm);
    }

    // Demo-only form handlers for the account page.
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            alert("This is a demonstration login form. No live account system is connected in this exercise.");
        });
    }

    if (regForm) {
        regForm.addEventListener("submit", function (event) {
            event.preventDefault();
            alert("This is a demonstration sign-up form. The interface works, but no real registration backend is connected.");
        });
    }

    window.addEventListener("resize", function () {
        if (window.innerWidth > 800) {
            closeMobileMenu();
        }
    });
});
