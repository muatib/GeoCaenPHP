<?php
require __DIR__ . '/vendor/autoload.php'; 

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__); 
$dotenv->load();

session_start();
require __DIR__ . '/vendor/autoload.php';
include './includes/_database.php';
include './includes/_functions.php';
include './includes/_login-traitment.php';


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription / Connexion</title>

    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/js/main.js"></script>
</head>

<body>
    <header class="header-container login-header">
        <img
            class="header__img"
            src="./assets/img/Yellow_Simple_Depop_Profile_Picture-removebg-preview.webp"
            alt="enqueteur" />
        <a class="header__logo site__img" href="index.php"><img src="./assets/img/logo geocaen.png" alt="logo GeoCaen" /></a>
        <a class="header-user-img" href="login.php"><img src="./assets/img//icons8-compte-48.webp" alt="logo compte"></a>
        <div class="nav__lg">
            <ul class="nav__lg-lst">
                <li><a class="nav__lnk" href=""></a>Acceuil</li>
                <li><a class="nav__lnk" href=""></a>Jeux</li>
                <li><a class="nav__lnk" href=""></a>A propos de GeoCaen</li>
                <li><a class="nav__lnk" href=""></a> Nous contacter</li>
            </ul>
        </div>

        <div class="menu__toggle" id="burger__menu">
            <span class="menu__toggle-bar"></span>
        </div>
        <nav id="menu">
            <ul class="menu__container">
                <li class="menu__container-itm">
                    <a class="menu__container-lnk" href="index.php">Accueil</a>
                </li>
                <li class="menu__container-itm">
                    <a class="menu__container-lnk" href="game.php">Présentation des jeux</a>
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
        <section id="login" class="box__style form-box">
            <h2 class="users-ttl">Identifiez vous</h2>
            <?php if (!empty($loginErrors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($loginErrors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="login.php#login" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="logemail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input class="form-inp" type="password" id="logpassword" name="password" required>
                </div>
                <button type="submit" name="login_submit" class="btn ">S'identifier</button>
            </form>
        </section>
        <p class="login-txt">Vous n'avez pas de compte ? :</p>
        <a class="acc-lnk" href="create-acc.php">Créer un compte</a>
    </main>
   <?php  include './includes/_footer.php' ?>
    <script src="./js/burger.js"></script>
    <script src="./js/main.js"></script>

</body>

</html>