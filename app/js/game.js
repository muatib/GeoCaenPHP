let gameData = {};
let currentStep = 1; // Commencez à l'étape 1



function init() {
  currentIndex = 0; // Assurez-vous que currentIndex est initialisé
  
  if (localStorage.getItem("currentIndex")) {
    currentIndex = parseInt(localStorage.getItem("currentIndex"));
  }
  updateContent();
  const popups = document.querySelectorAll(".popup");
  popups.forEach((popup) => {
    popup.classList.remove("show");
  });
}

document.addEventListener("DOMContentLoaded", init);

function updateContent() {
  if (gameData) {
    document.getElementById('game1__txt').textContent = gameData.question;
    document.getElementById('clue').textContent = gameData.clue;

    // Réinitialiser l'affichage en cas de nouvelle question
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

function nextQuestion() {
  currentStep++;
  fetchData(1, currentStep); // Remplacez 1 par l'ID de votre jeu

  // Fermer le popup "Bonne réponse" après avoir chargé la nouvelle question
  closePopup('correctPopup'); 
}

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

// ... (Gestionnaires d'événements pour les boutons) ...

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

window.onbeforeunload = () => {
  localStorage.setItem("currentIndex", currentIndex);
};

window.onload = () => {
  if (localStorage.getItem("currentIndex")) {
    currentIndex = parseInt(localStorage.getItem("currentIndex"));
  }
  updateContent();
};