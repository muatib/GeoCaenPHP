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
    <img
      class="header__logo main__img"
      src="./assets/img/logo geocaen.png"
      alt="logo GeoCaen" />
    <a class="header-user-img" href="login.php"><img src="./assets/img//icons8-compte-48.webp" alt="logo compte"></a>
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
          <a class="menu__container-lnk" href="#">Nous contacter</a>
        </li>
      </ul>
    </nav>
  </header>
  <main>
    <h1 class="game1-ttl">
      Les trésors cachés de <br />Guillaume le Conquérant
    </h1>
    <div class="game1-txt">
      <p class="box-content box__style">
        Une ancienne légende persiste depuis des siècles parmi les habitants de Caen.... <br /><br />
        On raconte que Guillaume aurait dissimulé des trésors inestimables dans les monuments qu’il avait fait construire. <br /><br />
        Ces trésors ne pourraient être découverts qu'en suivant un parcours précis à travers la ville et en résolvant des énigmes liées à la vie et aux réalisations du roi Normand. Au fil des siècles nombreux furent ceux qui tentèrent de percer ces mystères. <br /><br />
        Certains prétendent avoir découvert de précieux indices et de mystérieux passages secrets, d’autres affirment avoir résolu certaines de ces grandes énigmes. <br /><br />
        Mais en réalité à ce jour personne n’a réussi à dévoiler les secrets enfouis depuis des siècles dans les rues et les monuments de Caen. <br /><br />
        Qui sait ? viendra peut-être le jour ou de courageux aventuriers lèveront le voile sur le mystère des trésors cachés de Guillaume le Conquérant. <br /><br />
        Et si ce jour était venu ?....
      </p>
      <img class="game1-img1 site__img" src="./assets/img/boy-sm.webp" alt="garçon" />
      <img class="game1__img2 site__img" src="./assets/img/filette-sm.webp" alt="fille" />
    </div>
    <a class="link" href="game-step.php"><button class="btn game-btn">Lancez l'enquête !</button></a>
  </main>

  <?php include 'footer.php'; ?>