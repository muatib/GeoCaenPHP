<?php
include 'config.php';



/**
 * Établit une connexion à la base de données en utilisant les informations de configuration.
 * @return PDO|null L'objet PDO représentant la connexion à la base de données, ou null en cas d'erreur.
 */
function connectDb()
{
    global $dbConfig;
    try {
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
        return new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }
}
/**
 * Génère un jeton CSRF aléatoire et le stocke dans la session.
 * @return string Le jeton CSRF généré.
 */
function generateCSRFToken()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return bin2hex(random_bytes(32));
}

function registerUser($firstname, $pseudo, $lastname, $email, $password, $avatarFile, $description)
{
    try {
        $conn = connectDb();

        // Vérification de l'existence de l'utilisateur
        $checkSql = "SELECT COUNT(*) FROM Register_user WHERE pseudo = :pseudo OR email = :email";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindValue(':pseudo', $pseudo);
        $checkStmt->bindValue(':email', $email);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            error_log("User registration failed: User already exists");
            return "L'utilisateur existe déjà";
        }

        // Insertion de l'utilisateur sans avatar
        $sql = "INSERT INTO Register_user (firstname, pseudo, lastname, email, password, description)
        VALUES (:firstname, :pseudo, :lastname, :email, :password, :description)";
        $stmt = $conn->prepare($sql);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([
            ':firstname' => $firstname,
            ':pseudo' => $pseudo,
            ':lastname' => $lastname,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':description' => $description
        ]);

        error_log("User registration successful for email: " . $email);
        return true;
    } catch (Exception $e) { // Capture toutes les exceptions
        error_log("Error during user registration: " . $e->getMessage());
        return "Une erreur est survenue lors de l'enregistrement. Veuillez réessayer plus tard.";
    }
}
function loginUser($email, $password)
{
    $conn = connectDb();

    $sql = "SELECT * FROM Register_user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();


    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch();
        if (password_verify($password, $row["password"])) {

            return true;
        } else {
            return "Mot de passe incorrect.";
        }
    } else {
        return false;
    }
}


function handleLogin($email, $password)
{
    global $loginErrors;

    $loginResult = loginUser($email, $password);

    if ($loginResult === true) {
        header("Location: index.php");
        exit();
    } else {
        $loginErrors[] = ($loginResult === false) ? "Aucun utilisateur trouvé avec cet email." : "Mot de passe incorrect.";
    }
}

function handleRegistration($firstname, $pseudo, $lastname, $email, $password, $avatar, $description)
{
    global $registerErrors, $registerSuccess;

    // Validation
    if (empty($firstname) || empty($pseudo) || empty($lastname) || empty($email) || empty($password)) {
        $registerErrors[] = "Tous les champs sont obligatoires.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerErrors[] = "L'adresse email n'est pas valide.";
    }
    if ($password !== $_POST['password-check']) {
        $registerErrors[] = "Les mots de passe ne correspondent pas.";
    }
    if (!isset($_POST['accept-terms'])) {
        $registerErrors[] = "Vous devez accepter les conditions d'utilisation et le traitement des données.";
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $registerErrors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
    }
    // Si pas d'erreurs, tenter l'enregistrement
    if (empty($registerErrors)) {
        $result = registerUser($firstname, $pseudo, $lastname, $email, $password, $avatar, $description);
        if ($result === true) {
            $registerSuccess = true;
            // Redirection vers la page de connexion
            header("Location: login.php#login");
            exit();
        } else {
            // Afficher l'erreur spécifique retournée par registerUser
            $registerErrors[] = "L'enregistrement a échoué : " . $result;
        }
    }
}

