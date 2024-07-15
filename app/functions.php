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

function registerUser($firstname, $pseudo, $lastname, $email, $password, $avatar, $description) {
    $conn = connectDb();

    $checkSql = "SELECT COUNT(*) FROM Register_user WHERE pseudo = :pseudo OR email = :email";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindValue(':pseudo', $pseudo);
    $checkStmt->bindValue(':email', $email);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        return false; 
    }

    if ($avatar && $avatar['error'] === UPLOAD_ERR_OK) {
        $avatarData = file_get_contents($avatar['tmp_name']);
    } else {
        $avatarData = null; 
    }

    $sql = "INSERT INTO Register_user (firstname, pseudo, lastname, email, password, avatar, description)
            VALUES (:firstname, :pseudo, :lastname, :email, :password, :avatar, :description)";
    $stmt = $conn->prepare($sql);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':avatar', $avatarData, PDO::PARAM_LOB);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);

    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false; 
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


    if (empty($firstname) || empty($pseudo) || empty($lastname) || empty($email) || empty($password)) {
        $registerErrors[] = "Tous les champs sont obligatoires.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerErrors[] = "L'adresse email n'est pas valide.";
    }


    if (empty($registerErrors)) {
        if (registerUser($firstname, $pseudo, $lastname, $email, $password, $avatar, $description)) {
            $registerSuccess = true;
            header("Location: users.php#login");
            exit();
        } else {
            $registerErrors[] = "Ce pseudo ou cet email est déjà utilisé. Veuillez en choisir un autre.";
        }
    }
}
