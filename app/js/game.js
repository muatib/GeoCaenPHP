

/**
 * Displays a popup by adding the "show" CSS class.
 *
 * @param {string} popupId - The HTML id of the popup element to display.
 *
 * @returns {void}
 */
function displayPopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.classList.add("show");
  }
}

/**
 * Closes a popup by removing the "show" CSS class.
 *
 * @param {string} popupId - The HTML id of the popup element to close.
 * @returns {void}
 */
function closePopup(popupId) {
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.classList.remove("show");
  }
}


/**
 * Applies visual feedback for a wrong answer.
 * 
 * @returns {void}
 */
function handleWrongAnswer() {
  const answerInput = document.getElementById("answer");
  answerInput.classList.add("game__form-wrong", "shake");
  
  setTimeout(function() {
    answerInput.classList.remove("shake");
  }, 500);
}

/**
 * Checks for an error message and triggers wrong answer handling if present.
 */
const errorMessage = document.getElementById("txt__wrong").textContent.trim();
if (errorMessage !== "") {
  handleWrongAnswer();
  document.getElementById("txt__wrong").classList.add("wrong__txt--disp");
}



/**
 * Resets the user session and redirects to the home page.
 * 
 * @returns {void}
 * 
 * This function performs the following actions:
 * 1. Clears all data stored in sessionStorage and localStorage.
 * 2. Sends a POST request to 'reset_session.php' to reset the PHP session.
 * 3. If the request is successful, redirects the user to 'index.php'.
 * 4. If an error occurs, logs it to the console.
 */
function resetSession() {
  // Clear browser-stored session data
  sessionStorage.clear();
  localStorage.clear();

  // Send request to reset PHP session
  fetch('reset_session.php', {
      method: 'POST',
  }).then(response => {
      if (response.ok) {
          // Redirect to home page
          window.location.href = 'index.php';
      } else {
          console.error('Error resetting session');
      }
  });
}

document.getElementById('fun-fact').textContent = "<?php echo $funFact; ?>";