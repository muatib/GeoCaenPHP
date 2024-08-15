

function displayPopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.classList.add("show");
  }
}

function closePopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.classList.remove("show");
  }
}



document.addEventListener("click", (event) => {
  if (event.target.id === "submitButton") {
    checkAnswer();
  } else if (event.target.id === "closeCorrectPopup") {
    nextQuestion(); 
  } else if (event.target.id === "closeIncorrectPopup") {
    closePopup("incorrectPopup");
  } else if (event.target.id === "showClue") {
    displayPopup("popup");
  } else if (event.target.closest("#popup .link")) {
    closePopup("popup");
  } else if (event.target.closest("#popupk .link:first-child")) {
    closePopup("popupk");
  }
});

function init() {
  currentIndex = 0; 
  
  if (localStorage.getItem("currentIndex")) {
    currentIndex = parseInt(localStorage.getItem("currentIndex"));
  }
  updateContent();
  const popups = document.querySelectorAll(".popup");
  popups.forEach((popup) => {
    popup.classList.remove("show");
  });
}


function updateContent() {
  if (gameData) {
    document.getElementById('game1__txt').textContent = gameData.question;
    document.getElementById('clue').textContent = gameData.clue;

    document.getElementById('answer').value = '';
    document.getElementById('txt__wrong').classList.remove('wrong__txt--disp');
    document.getElementById('answer').classList.remove('game__form-wrong', 'shake');
  }
}

function checkAnswer() {
  const userAnswer = document.getElementById('answer').value.toLowerCase();
  const answerInput = document.getElementById('answer');

  if (userAnswer === gameData.answer.toLowerCase()) {
    displayPopup('correctPopup');
  } else {
    answerInput.value = "";
    answerInput.classList.add("game__form-wrong", "shake");
    const changeTextState = document.querySelector("#txt__wrong");
    changeTextState.classList.add("wrong__txt--disp");
    changeTextState.classList.remove("wrong__txt");
  }
}

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