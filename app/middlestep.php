<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bravo !</title>
    <link rel="stylesheet" href="/css/style.css" />
  </head>
  <body>
    <header>
      <img
        class="game1__img"
        src="/img/guillaume-le-conquerant_1024x.webp"
        alt=""
      />
      <img class="game1__logo" src="/img/Geo-removebg-preview.webp" alt="" />
    </header>
    <main>
      <div>
        <p class="box-content box__style" id="middle__txt">
          
        </p>
        <img
          class="middle__img1 site__img"
          src="/img/bravo-sm.webp"
          alt="detective content"
        />
      </div>
    </main>
    <footer class="middle__game-footer">
      <button class="game__btn" id="home-button" href="#" onclick="displayPopup('popupk')">accueil</button>
      <div class="popup box__style" id="popupk">
        <p class="popupk__txt">Souhaitez vous vraiment quitter ?</p>
        <img class="popupk__img" src="/img/detective_interroge-removebg-preview.webp" alt="" />
        <div class="popupk__container">
          <a class="link" href="#" onclick="closePopup('popupk')">
            <button class="btn popupk__btn">continuer</button>
          </a>
          <a class="link" href="index.html" onclick="closePopup('popupk')">
            <button class="btn popupk__btn">quitter</button>
          </a>
        </div>
      </div>
      <a class="link" href="guillaumeStep1.html"
      ><button class="pop__btn game__btn" id="closeCorrectPopup">
        suivant
      </button></a
    >
    </footer>
    <script src="/js/game.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/lore.js"></script>
    <script src="/js/burger.js"></script>
  </body>
</html>
