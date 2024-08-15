<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $to = "vincdubois14@gmail.com"; // Remplacez par votre adresse e-mail
    $subject = "Nouveau message de votre site web";
    $body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "Message envoyé avec succès !";
    } else {
        echo "Erreur lors de l'envoi du message.";
    }
}
?>

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
        <img class="header__img" src="./assets/img/Yellow_Simple_Depop_Profile_Picture-removebg-preview.webp" alt="enqueteur" />
        <img class="header__logo site__img" src="./assets/img/logo geocaen.png" alt="logo GeoCaen" />
        <a class="header-user-img" href="login.php"><img src="./assets/img//icons8-compte-48.webp" alt="logo compte"></a>
        <div class="nav__lg">
            <ul class="nav__lg-lst">
                <li><a class="nav__lnk" href="index.php">Accueil</a></li>
                <li><a class="nav__lnk" href="game.php">Jeux</a></li>
                <li><a class="nav__lnk" href="#">A propos de GeoCaen</a></li>

            </ul>
        </div>

        <div class="menu__toggle" id="burger__menu">
            <span class="menu__toggle-bar"></span>
        </div>
        <nav id="menu">
            <ul class="menu__container">

                <li class="menu__container-itm">
                    <a class="menu__container-lnk" href="index.php">accueil</a>
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

    <h1>Formulaire de contact</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nom">Nom:</label><br>
        <input type="text" id="nom" name="nom" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" 
 required><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" required></textarea><br><br>

        <input type="submit" value="Envoyer"> 

    </form>

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
    <script src="./js/burger.js"></script>
    <script src="./js/main.js"></script>



</body>

</html>