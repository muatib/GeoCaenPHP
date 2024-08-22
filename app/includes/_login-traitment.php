<?php 
/**
 * Attempts to log in a user with the provided email and password.
 * 
 * @param string $email The user's email address
 * @param string $password The user's password
 * 
 * @return true|string|false 
 *   - `true` if login is successful
 *   - `"Mot de passe incorrect."` if the password is incorrect
 *   - `false` if the user is not found
 
 */
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


/**
 * Handles the login process after receiving user credentials.
 * 
 * @param string $email The user's email address
 * @param string $password The user's password
 * 
 * @return void This function doesn't return a value, but it can modify the `$loginErrors` global variable and redirect the user upon successful login.
 
 */
function handleLogin($email, $password)
{
    global $loginErrors;

    $loginResult = loginUser($email, $password);

    if ($loginResult === true) {
        header("Location: index.php");
        exit();
    } else {
        $loginErrors[] = ($loginResult === false) ? "No user found with this email." : "Incorrect password.";
    }
}

// Initialize variables for user data and error handling
$firstname = $pseudo = $lastname = $email = $password = $avatar = $description = "";
$loginErrors = [];
$registerErrors = [];
$registerSuccess = false;

// Generate a CSRF token if one doesn't exist in the session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCSRFToken();
}
$csrfToken = $_SESSION['csrf_token'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verify CSRF token to prevent cross-site request forgery attacks
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF error detected.");
    }

    // Handle registration form submission
    if (isset($_POST['register_submit'])) {
        $firstname = $_POST['firstname'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $description = $_POST['description'] ?? '';
        $avatar = $_FILES['avatar'] ?? null;

        handleRegistration($firstname, $pseudo, $lastname, $email, $password, $avatar, $description); 
    } 
    // Handle login form submission
    elseif (isset($_POST['login_submit'])) {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        handleLogin($email, $password);
    }
}