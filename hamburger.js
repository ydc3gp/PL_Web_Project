const hamburger = document.querySelector(".hamburger");
const navLinks = document.querySelector(".nav-links");
const rightSection = document.querySelector(".right-section");

// Ensure nav links are hidden by default
navLinks.classList.remove("active");

hamburger.addEventListener("click", () => {
  navLinks.classList.toggle("active");
  rightSection.classList.toggle("active");
});
