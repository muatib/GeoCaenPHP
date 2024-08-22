<?php

/**
 * Registers a new user in the database.
 *
 * @param string $firstname User's first name
 * @param string $pseudo User's pseudonym
 * @param string $lastname User's last name
 * @param string $email User's email address
 * @param string $password User's password (will be hashed)
 * @param mixed $avatarFile User's avatar file (unused in current implementation)
 * @param string $description User's description
 *
 * @return bool|string True if registration successful, error message string otherwise
 */
function registerUser($firstname, $pseudo, $lastname, $email, $password, $avatarFile, $description)
{
    try {
        $conn = connectDb();
        
        // Check if user already exists
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
        
        // Insert new user
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
    } catch (Exception $e) {
        error_log("Error during user registration: " . $e->getMessage());
        return "Une erreur est survenue lors de l'enregistrement. Veuillez réessayer plus tard.";
    }
}

/**
 * Handles the registration process, including input validation.
 *
 * @param string $firstname User's first name
 * @param string $pseudo User's pseudonym
 * @param string $lastname User's last name
 * @param string $email User's email address
 * @param string $password User's password
 * @param mixed $avatar User's avatar (unused in current implementation)
 * @param string $description User's description
 *
 * @global array $registerErrors Array to store registration errors
 * @global bool $registerSuccess Flag indicating successful registration
 */
function handleRegistration($firstname, $pseudo, $lastname, $email, $password, $avatar, $description)
{
    global $registerErrors, $registerSuccess;

    // Input validation
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
    
    if (empty($registerErrors)) {
        $result = registerUser($firstname, $pseudo, $lastname, $email, $password, $avatar, $description);
        if ($result === true) {
            $registerSuccess = true;
            
            header("Location: login.php");
            exit();
        } else {
            $registerErrors[] = "L'enregistrement a échoué : " . $result;
        }
    }
}

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