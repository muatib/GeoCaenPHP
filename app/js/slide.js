document.addEventListener('DOMContentLoaded', function() {
    let slides = document.querySelectorAll('.slide-container');
    let currentSlide = 0;
  
    function showNextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }
  
    slides[0].classList.add('active');
  
    setInterval(showNextSlide, 5000);
  });
  