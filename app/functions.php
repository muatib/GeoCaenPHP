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
            
            header("Location: login.php#login");
            exit();
        } else {
           
            $registerErrors[] = "L'enregistrement a échoué : " . $result;
        }
    }
}

function getCurrentStepData($gameId, $stepOrder) {
    try {
        $db = connectDb();
        $query = "SELECT * FROM game_step WHERE Id_game = :gameId AND step_order = :stepOrder";
        $stmt = $db->prepare($query);
        $stmt->execute(['gameId' => $gameId, 'stepOrder' => $stepOrder]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            error_log("Aucune donnée trouvée pour gameId=$gameId et stepOrder=$stepOrder");
            return null;
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Erreur de base de données : " . $e->getMessage());
        return null;
    }
}

function getCorrectAnswer($stepId) {
    try {
        $db = connectDb();
        $query = "SELECT answer FROM answer WHERE Id_game_step = :stepId AND GoodFalse = true";
        $stmt = $db->prepare($query);
        $stmt->execute(['stepId' => $stepId]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur de base de données : " . $e->getMessage());
        return null;
    }
}

function handleGameStep($requestData) {
    // Initialiser les variables
    $message = '';

    // Récupérer les données de l'étape actuelle
    $stepData = getCurrentStepData($_SESSION['gameId'], $_SESSION['currentStep']);

    // Traiter la soumission de réponse
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($requestData['answer'])) {
        $userAnswer = $requestData['answer'];
        if ($stepData) {
            $correctAnswer = getCorrectAnswer($stepData['Id_game_step']);
            if ($correctAnswer !== null) {
                if (strtolower($userAnswer) === strtolower($correctAnswer)) {
                    $message = "Bonne réponse !";
                    $_SESSION['currentStep']++;
                    header("Location: middlestep.php");
                    exit();
                } else {
                    $message = "Presque ! N'abandonnez pas !";
                }
            } else {
                $message = "Erreur : impossible de vérifier la réponse.";
            }
        } else {
            $message = "Erreur : données de l'étape non disponibles.";
        }
    }

    return [
        'stepData' => $stepData,
        'message' => $message
    ];
}
