<?php


include 'functions.php';
include 'game-traitment.php';

session_start();

if (!isset($_SESSION['gameId'])) {
  $_SESSION['gameId'] = 9;
}
if (!isset($_SESSION['currentStep'])) {
  $_SESSION['currentStep'] = 1;
}

$gameData = handleGameStep($_POST);
if (!$gameData['stepData'] && isset($_SESSION['currentStep']) && $_SESSION['currentStep'] > 1) {
  header("Location: end-game.php"); 
  exit();
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Étape <?php echo $_SESSION['currentStep']; ?></title>
  <script type="module" src="http://localhost:5173/@vite/client"></script>
  <script type="module" src="http://localhost:5173/js/main.js"></script>
</head>

<body>
  <header>
    <img class="game1-img" src="./assets/img/guillaume-le-conquerant_1024x.webp" alt="guillaume" />
    <img class="game1-logo" src="./assets/img//logo geocaen.png" alt="logo" />
  </header>
  <main>
    <?php if ($gameData['stepData']): ?>
      <p class="box-content box__style" id="game1__txt">
        <?php echo htmlspecialchars($gameData['stepData']['question'] ?? ''); ?>
        <img class="question-img" src="./assets/img/detective_interroge-removebg-preview.webp" alt="interrogation" />
      </p>

      <p class="wrong__txt" id="txt__wrong"><?php echo isset($gameData['message']) ? htmlspecialchars($gameData['message']) : ''; ?></p>
      <div class="game__form">
        <form method="post" action="">
          <input class="game__form-txt" type="text" name="answer" placeholder="Réponse" id="answer" required />
          <button class="game__form-btn btn" type="submit" id="submitButton">valider</button>
        </form>
      </div>

      <div id="correctPopup" class="popup" style="display: none;">
        <p class="popup__txt">Bonne réponse bravo !</p>
        <img class="site__img pop__img" src="./assets/img/bravo-sm.webp" alt="" />
        <a class="link" href="middlestep.php">
          <button class="pop__btn btn" id="closeCorrectPopup">suivant</button>
        </a>
      </div>

      <div id="incorrectPopup" class="popup" style="display: none;">
        <img class="site__img pop__img" src="./assets/img/detective_interroge-removebg-preview.webp" alt="" />
        <button class="pop__btn btn" id="closeIncorrectPopup">Fermer</button>
      </div>
      <?php else: ?>
            <p>Erreur : Impossible de charger les données de l'étape. Veuillez contacter l'administrateur.</p>
        <?php endif; ?>
    
  </main>
  <footer class="game__footer">
    <button class="game__btn" id="home-button" onclick="displayPopup('popupk')">accueil</button>
    <div class="popup box__style" id="popupk">
      <p class="popupk__txt">Souhaitez vous vraiment quitter ?</p>
      <img class="popupk__img" src="./assets/img/detective_interroge-removebg-preview.webp" alt="" />
      <div class="popupk__container">
        <a class="link" href="#" onclick="closePopup('popupk')">
          <button class="btn popupk__btn">continuer</button>
        </a>
        <a class="link" href="index.php" onclick="closePopup('popupk')">
          <button class="btn popupk__btn">quitter</button>
        </a>
      </div>
    </div>
    <button class="game__btn" id="showClue" onclick="displayPopup('popup')">indice</button>
    <div class="popup box__style" id="popup">
      <p class="popup__txt" id="clue"><?php echo htmlspecialchars($gameData['stepData']['clue'] ?? ''); ?></p>
      <img class="pop__img site__img" src="./assets/img/detective_interroge-removebg-preview.webp" alt="" />
      <a class="link" href="#" onclick="closePopup('popup')">
        <button class="btn pop__btn">fermer</button>
      </a>
    </div>
  </footer>
  <script src="./js/main.js"></script>
  <script src="./js/game.js"></script>

</body>

</html>