<?php

function registerUser($firstname, $pseudo, $lastname, $email, $password, $avatarFile, $description)
{
    try {
        $conn = connectDb();

        
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
function handleRegistration($firstname, $pseudo, $lastname, $email, $password, $avatar, $description)
{
    global $registerErrors, $registerSuccess;

   
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