<?php
include 'config.php';

function connectDb() {
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

function generateCSRFToken() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return bin2hex(random_bytes(32));
}

function registerUser($firstname, $pseudo, $lastname, $email, $password, $avatarFile, $description) {
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
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        $result = $stmt->execute();
        
        if ($result) {
            error_log("User registration successful for email: " . $email);
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("User registration failed: " . implode(", ", $errorInfo));
            return "Erreur lors de l'insertion : " . $errorInfo[2];
        }
    } catch (PDOException $e) {
        error_log("Database error during user registration: " . $e->getMessage());
        return "Erreur de base de données : " . $e->getMessage();
    } catch (Exception $e) {
        error_log("Unexpected error during user registration: " . $e->getMessage());
        return "Erreur inattendue : " . $e->getMessage();
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

    // Si pas d'erreurs, tenter l'enregistrement
    if (empty($registerErrors)) {
        $result = registerUser($firstname, $pseudo, $lastname, $email, $password, $avatar, $description);
        if ($result === true) {
            $registerSuccess = true;
            // Redirection vers la page de connexion
            header("Location: users.php#login");
            exit();
        } else {
            // Afficher l'erreur spécifique retournée par registerUser
            $registerErrors[] = "L'enregistrement a échoué : " . $result;
        }
    }
}