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

    
    if (isset($_POST['login_submit'])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $loginResult = loginUser($email, $password);

        if ($loginResult === true) {
            header("Location: index.php"); 
            exit();
        } else {
            $loginErrors[] = ($loginResult === false) ? "Aucun utilisateur trouvé avec cet email." : "Mot de passe incorrect.";
        }
    }
     }
    
    
    if (isset($_POST['register_submit'])) {
        $firstname = isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '';
        $pseudo = isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : '';
        $lastname = isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;
        $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';

    if (empty($firstname) || empty($pseudo) || empty($lastname) || empty($email) || empty($password)) {
        $registerErrors[] = "Tous les champs sont obligatoires.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerErrors[] = "L'adresse email n'est pas valide.";
    }

    if (empty($registerErrors)) {
        if (registerUser($firstname, $pseudo, $lastname, $email, $password, $avatar, $description)) {
            $registerSuccess = true;
            $_SESSION['csrf_token'] = generateCSRFToken(); 
            header("Location: users.php#login"); 
            exit();
        } else {
            $registerErrors[] = "Ce pseudo ou cet email est déjà utilisé. Veuillez en choisir un autre.";
        }
        if ($registerSuccess) {
          $_SESSION['csrf_token'] = generateCSRFToken(); 
      }
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
          src="/img/logovillecaenndie_2016-removebg-preview.webp"
          alt="enqueteur"
        />
        <img
          class="header__logo site__img"
          src="/img/Geo-removebg-preview.webp"
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
        <section id="login" class="form-container">
            <h2 class="users-ttl">Login</h2>
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
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login_submit" class="btn btn-primary">Login</button> 
            </form>
        </section>

        <section id="register" class="form-container">
            <h2>Register</h2>
            <?php if (!empty($registerErrors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($registerErrors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li> 
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($registerSuccess): ?>
                <div class="success-message">Registration successful! Please log in.</div>
            <?php endif; ?>
            <form action="users.php#register" method="post" enctype="multipart/form-data"> 
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="pseudo">Username:</label>
                    <input type="text" id="pseudo" name="pseudo" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="avatar">Avatar:</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <button type="submit" name="register_submit" class="btn btn-danger">Register</button>
            </form>
        </section>
    </main>
    <?php include 'footer.php'; ?> 