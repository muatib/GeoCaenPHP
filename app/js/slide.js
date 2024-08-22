document.addEventListener("DOMContentLoaded", function () {
  //An array of all elements with the class 'slide-container', representing the individual slides.
  let slides = document.querySelectorAll(".slide-container");

  //The index of the currently active slide.
  let currentSlide = 0;

  /**
   * Transitions to the next slide in the sequence.
   */
  function showNextSlide() {
    slides[currentSlide].classList.remove("active");
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add("active");
  }

  // Set the first slide as active initially
  slides[0].classList.add("active");

  // Automatically transition to the next slide every 5 seconds
  setInterval(showNextSlide, 5000);
});
