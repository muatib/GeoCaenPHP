

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


function handleWrongAnswer() {
  const answerInput = document.getElementById("answer");
  answerInput.classList.add("game__form-wrong", "shake");

  setTimeout(function() {
      answerInput.classList.remove("shake");
  }, 500); 
}

const errorMessage = document.getElementById("txt__wrong").textContent.trim();
if (errorMessage !== "") {
  handleWrongAnswer();
  document.getElementById("txt__wrong").classList.add("wrong__txt--disp");
}



function resetSession() {
  // Effacer les données de session stockées dans le navigateur
  sessionStorage.clear();
  localStorage.clear();

  // Envoyer une requête au serveur pour réinitialiser la session PHP
  fetch('reset_session.php', {
      method: 'POST',
  }).then(response => {
      if (response.ok) {
          // Rediriger vers la page d'accueil
          window.location.href = 'index.php';
      } else {
          console.error('Erreur lors de la réinitialisation de la session');
      }
  });
}

document.getElementById('fun-fact').textContent = "<?php echo $funFact; ?>";