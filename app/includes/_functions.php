<?php


/**
 * Generates a random CSRF token and stores it in the session.
 *
 * @return string The generated CSRF token.

 */
/**
 * Generate a unique token and add it to the user session. 
 *
 * @return void
 */
function generateCSRFToken()
{
    if (
        !isset($_SESSION['token'])
        || !isset($_SESSION['tokenExpire'])
        || $_SESSION['tokenExpire'] < time()
    ) {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
        $_SESSION['tokenExpire'] = time() + 60 * 15;
    }
}

/**
 * Redirect to the given URL.
 *
 * @param string $url
 * @return void
 */
function redirectTo(string $url): void
{
    // var_dump('REDIRECT ' . $url);
    header('Location: ' . $url);
    exit;
}


/**
 * Check fo referer
 *
 * @return boolean Is the current referer valid ?
 */
function isRefererOk(): bool
{
    global $globalUrl;
    return isset($_SERVER['HTTP_REFERER'])
        && str_contains($_SERVER['HTTP_REFERER'], $globalUrl);
}


/**
 * Check for CSRF token
 *
 * @param array|null $data Input data
 * @return boolean Is there a valid toekn in user session ?
 */
function isTokenOk(?array $data = null): bool
{
    if (!is_array($data)) $data = $_REQUEST;

    return isset($_SESSION['token'])
        && isset($data['token'])
        && $_SESSION['token'] === $data['token'];
}

/**
 * Verifies HTTP referer and CSRF token.
 *
 * @param array|null $data Input data (optional, defaults to $_REQUEST)
 * @return bool True if both checks pass, false otherwise
 */
function preventCSRF(?array $data = null): bool
{
    if (!isRefererOk() || !isTokenOk($data)) {
        return false; // Indicate that checks failed
    }

    return true; // Indicate that checks passed
}





