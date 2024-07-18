<?php
session_start(); 
require __DIR__ . '/vendor/autoload.php';
include 'functions.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$firstname = $pseudo = $lastname = $email = $password = $avatar = $description = "";
$loginErrors = [];
$registerErrors = [];
$registerSuccess = false;

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCSRFToken();
}
$csrfToken = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur CSRF détectée.");
    }

    if (isset($_POST['register_submit'])) {
        $firstname = $_POST['firstname'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $description = $_POST['description'] ?? '';
        $avatar = $_FILES['avatar'] ?? null;

        handleRegistration($firstname, $pseudo, $lastname, $email, $password, $avatar, $description);
    } elseif (isset($_POST['login_submit'])) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        handleLogin($email, $password);
    }
}
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
<header class="header-container">
        <img
          class="header__img"
          src="./assets/img/logovillecaenndie_2016-removebg-preview.webp"
          alt="enqueteur"
        />
        <img
          class="header__logo site__img"
          src="./assets/img/Geo-removebg-preview.webp"
          alt="logo GeoCaen"
        />
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
              <a class="menu__container-lnk" href="index.php"
                >Accueil</a
              >
            </li>
            <li class="menu__container-itm">
              <a class="menu__container-lnk" href="game.php"
                >Présentation des jeux</a
              >
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
        <section id="login" class="box__style form-box">
            <h2 class="users-ttl">Vous avez un compte ?<br>Identifiez vous</h2>
            <?php if (!empty($loginErrors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($loginErrors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li> 
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="users.php#login" method="post">
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

        <section id="register" class="box__style form-box">
            <h2 class="users-ttl">Créer un compte</h2>
            <?php if (!empty($registerErrors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($registerErrors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li> 
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($registerSuccess): ?>
                <div class="success-message">Inscription réussie ! Vous pouvez maintenant vous connecter.</div>
            <?php endif; ?>
            <form action="users.php#register" method="post" enctype="multipart/form-data"> 
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <div class="form-group">
                    <label for="firstname">Nom:</label>
                    <input type="text" id="firstname" name="firstname" required value="<?php echo htmlspecialchars($firstname); ?>">
                </div>
                <div class="form-group">
                    <label for="lastname">Prénom:</label>
                    <input type="text" id="lastname" name="lastname" required value="<?php echo htmlspecialchars($lastname); ?>">
                </div>
                <div class="form-group">
                    <label for="pseudo">Pseudo:</label>
                    <input type="text" id="pseudo" name="pseudo" required value="<?php echo htmlspecialchars($pseudo); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="avatar">Avatar profil:</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="description">Présentez vous en quelques mots:</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                <button type="submit" name="register_submit" class="btn">Créer le compte</button>
            </form>
        </section>
    </main>
    <footer class="footer">
        <div class="footer__txt">
            <p>infos contact</p>
            <p>Suivez notre actualité :</p>
            <p>
                <span class="txt__blue">Geo</span><span class="txt__red">Caen</span> tout droits réservés
            </p>
        </div>
        <ul class="footer__icn">
            <li>
                <img class="footer__icn-img" src="./assets/img/facebook-square-svgrepo-com.svg" alt="facebook" />
            </li>
            <li>
                <img class="footer__icn-img" src="./assets/img/twitter-svgrepo-com.svg" alt="twitter" />
            </li>
            <li>
                <img class="footer__icn-img" src="./assets/img/instagram-1-svgrepo-com.svg" alt="instagram" />
            </li>
        </ul>
    </footer>
    <script  src="./js/burger.js"></script>
    <script  src="./js/main.js"></script>
    
</body>
</html>