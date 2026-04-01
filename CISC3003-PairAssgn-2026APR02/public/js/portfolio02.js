const portfolio02Toggle = document.querySelector(".nav-toggle");
const portfolio02Links = document.querySelector(".nav-links");

function closePortfolio02Menu() {
  if (!portfolio02Toggle || !portfolio02Links) {
    return;
  }

  portfolio02Toggle.classList.remove("open");
  portfolio02Links.classList.remove("open");
  portfolio02Toggle.setAttribute("aria-expanded", "false");
}

if (portfolio02Toggle && portfolio02Links) {
  portfolio02Toggle.addEventListener("click", () => {
    const isOpen = portfolio02Links.classList.toggle("open");
    portfolio02Toggle.classList.toggle("open", isOpen);
    portfolio02Toggle.setAttribute("aria-expanded", String(isOpen));
  });

  portfolio02Links.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", closePortfolio02Menu);
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 980) {
      closePortfolio02Menu();
    }
  });
}
