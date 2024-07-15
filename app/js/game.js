let currentIndex = 0;
let data;

function init() {
  fetch("/json/dat.json")
    .then((response) => response.json())
    .then((jsonData) => {
      data = jsonData;
      if (localStorage.getItem("currentIndex")) {
        currentIndex = parseInt(localStorage.getItem("currentIndex"));
      }
      updateContent();
      const popups = document.querySelectorAll(".popup");
      popups.forEach((popup) => {
        popup.classList.remove("show");
      });
    });
};

document.addEventListener("DOMContentLoaded", init);

function updateContent() {
  if (data) {
    const gameContent = data[currentIndex];
    const game1Txt = document.querySelector("#game1__txt");
    game1Txt.textContent = gameContent.text;
    document.querySelector("#showClue").addEventListener("click", () => {
      const clue = document.querySelector("#clue");
      clue.textContent = gameContent.clue;
    });
  }
};

function checkAnswer() {
  const userAnswer = document.querySelector("#answer").value.toLowerCase();
  const correctAnswer = data[currentIndex].answer.toLowerCase();
  const answerInput = document.querySelector("#answer");
  if (userAnswer === correctAnswer) {
    displayPopup("correctPopup");
  } else {
    
    answerInput.value = "";
    answerInput.classList.add("game__form-wrong", "shake");
    const changeTextState = document.querySelector("#txt__wrong");
    changeTextState.classList.add("wrong__txt--disp");
    changeTextState.classList.remove("wrong__txt");
  }
};


function nextQuestion() {
  currentIndex++;
  if (currentIndex >= data.length) {
    currentIndex = 0;
  }
  updateContent();
};

function displayPopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.classList.add("show");
  }
};

function closePopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.classList.remove("show");
  }
};



document.addEventListener("click", (event) => {
  if (event.target.id === "submitButton") {
    checkAnswer();
  } else if (event.target.id === "closeCorrectPopup") {
    closePopup("correctPopup");
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