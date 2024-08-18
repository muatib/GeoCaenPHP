<?php 

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