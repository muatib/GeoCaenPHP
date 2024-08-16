<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GeoCean jeux</title>
  <script type="module" src="http://localhost:5173/@vite/client"></script>
  <script type="module" src="http://localhost:5173/js/main.js"></script>
</head>

<body>
  <header class="header-container">
    <img
      class="header__img"
      src="./assets/img/Yellow_Simple_Depop_Profile_Picture-removebg-preview.webp"
      alt="enqueteur" />
      <a class="header__logo site__img" href="index.php"><img  src="./assets/img/logo geocaen.png" alt="logo GeoCaen" /></a>
      <a class="header-user-img" href="login.php"><img  src="./assets/img//icons8-compte-48.webp" alt="logo compte"></a>

    <div class="menu__toggle" id="burger__menu">
      <span class="menu__toggle-bar"></span>
    </div>
    <nav id="menu">
      <ul class="menu__container">
        <li class="menu__container-itm">
          <a class="menu__container-lnk link" href="index.php">Accueil</a>
        </li>

        <li class="menu__container-itm">
          <a class="menu__container-lnk" href="#">A propos de GeoCaen</a>
        </li>
        <li class="menu__container-itm">
          <a class="menu__container-lnk" href="contact.php">Nous contacter</a>
        </li>
      </ul>
    </nav>
  </header>
  <main>
    <h2 class="game__ttl">
      <span class="txt__red">Bienvenue</span> enquêteur ! <br />
      Choisissez le mystère à <span class="txt__blue">percer</span>
    </h2>
    <p class="game__txt"><span class="txt__red">(</span> Cliquez sur l'image pour accéder au jeu <span class="txt__red">)</span></p>
    <div class="game">
      <h3>Les trésors cachés de <br> Guillaume le <span class="txt__gold">Conquérant</span></h3>
      <a href="game-pres.php"><img
          class="game__img1 game__img"
          src="./assets/img/guillaume-le-conquerant2.webp"
          alt="guillaume le conquérant" /></a>
      <h3>Les pouvoirs de la reine <span class="txt__purple">Mathilde</span></h3>
      <a href="#"><img
          class="game__img2 game__img"
          src="./assets/img/mathilde2.webp"
          alt="reine Mathilde" /></a>
      <h3>L'héritage des <span class="txt__red">vikings</span></h3>
      <a href="#"><img class="game__img3 game__img" src="./assets/img/viking.webp" alt="viking" /></a>
    </div>

    <img
      class="game__img4"
      src="./assets/img/famille detective-sm.webp"
      alt="famille detective" />
  </main>

 <?php include 'footer.php'; ?>

  <script src="./js/main.js"></script>
  <script src="./js/burger.js"></script>
</body>

</html>