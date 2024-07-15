<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8" />
    <link rel="icon" type="image" href="/img/enqueteur caen.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GeoCaen</title>

    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/js/main.js"></script>

</head>

<body>
    <header class="header-container">
        <img class="header__img" src="/img/logovillecaenndie_2016-removebg-preview.webp" alt="enqueteur" />
        <img class="header__logo site__img" src="/img/Geo-removebg-preview.webp" alt="logo GeoCaen" />
        <div class="nav__lg">
            <ul class="nav__lg-lst">
                <li><a class="nav__lnk" href="index.php">Accueil</a></li>
                <li><a class="nav__lnk" href="game.php">Jeux</a></li>
                <li><a class="nav__lnk" href="#">A propos de GeoCaen</a></li>
                <li><a class="nav__lnk" href="#">Nous contacter</a></li>
            </ul>
        </div>

        <div class="menu__toggle" id="burger__menu">
            <span class="menu__toggle-bar"></span>
        </div>
        <nav id="menu">
            <ul class="menu__container">
                <li class="menu__container-itm">
                    <a class="menu__container-lnk" href="users.php">Se connecter / S'inscrire</a>
                </li>
                <li class="menu__container-itm">
                    <a class="menu__container-lnk" href="jeux.php">Présentation des jeux</a>
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