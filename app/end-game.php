<?php
 include 'header.php';


?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<a href="#" id="reset-button" onclick="resetSession()"><button>Accueil</button></a>
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
</body>
<script src="./js/game.js"> </script>
<script src="./js/burger.js"> </script>
</html>