<?php
require_once "config.php";
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$base_backend = '/donttrip/backend/';
$base_client = '/donttrip/client/';

if ($uri === '/donttrip/' || $uri === '/donttrip/login' || $uri === $base_client . 'fp' || $uri === $base_backend . 'fp_backend')
{
    startSession();
}
else if ($uri === $base_client . 'two_factor_auth' || $uri === $base_backend . 'two_factor_auth_backend')
{
    startSession();
    checkPreLoginUsername();
    checkExpiry();
}
else if ($uri === $base_client . 'register')
{
    startSession();
    checkExpiry();
}
else if ($uri === $base_client . 'settings' || $uri === $base_backend . 'settings_backend' || $uri === $base_client . 'dt' || $uri === $base_backend . 'dt_backend')
{
    startSession();
    checkLoggedIn();
    checkAuthorized();
    checkExpiry();
}
else if ($uri === $base_client . 'searches' || $uri === $base_client . 'state' || $uri === $base_backend . 'state_backend' || $uri === $base_backend . 'searches_backend' || $uri === $base_backend . 'delete_search' || $uri === $base_client . 'delete_confirmation' || $uri === $base_backend . 'delete_confirmation_backend')
{
    startSession();
    checkLoggedIn();
    checkAuthorized();
    checkExpiry();
}
else if ($uri === $base_client . 'reset-password' || $uri === $base_backend . 'reset-password_backend')
{
    startSession();
    checkLoggedIn();
    checkExpiry();
}
else if ($uri === $base_client . 'locked' || $uri === $base_backend . 'recovery_backend')
{
    startSession();
    checkLocked();
}

/**
 * Check if session is expired and update the SSID if it is not expired.
 */

function checkExpiry()
{
    if (isset($_SESSION['loginTime']) && $_SESSION['loginTime'] + $_ENV["expire"] < time())
    {
        $_SESSION = array();
        session_destroy();
        header('location: https://www.donttrip.org/donttrip/client/session_expired');
        die;
    }
}

/**
 * Check if the user is authorized to access this route.
 */

function checkAuthorized()
{
    
    if (isset($_SESSION["authorized"]) && $_SESSION["authorized"] === false)
    {
        header("location: ../backend/logout.php");
        die;
    }
}

/**
 * Check if the user is authorized AND authenticated.
*/

//Worked on 7/13/2025 to allow Google OAuth handshake whilst blocking the route unless logged in.

function checkLoggedIn()
{
    if (isset($_GET['code'])&&isset($_GET['scope'])) {
        return;
    }

    if ((!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)&&(!isset($_SESSION['access_token'])))
    {
        header("location: ../client/session_expired");
        die;
    }
}


/**
 * Check if the user account is locked.
 */

function checkLocked()
{
    if (!isset($_SESSION["locked"]) || $_SESSION["locked"] !== true)
    {
        header("location: ../login.php");
        die;
    }
}

/**
 * Check if the user account is valid and has 2FA enabled before allowing them to enter their OTP.
 */

function checkPreLoginUsername()
{
    if (empty($_SESSION["username"]))
    {
        header("location: ../login.php");
        die;
    }
}

/**
 * Gracefully start a new session.
 */

function startSession()
{
    if (!isset($_SESSION))
    {
        $sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
        $handler = new \ByJG\Session\JwtSession($sessionConfig);
    }
}

/**
 * Check if access to our API is authorized via HMAC/SHA256 CSRF tokens.
 */

function csrf()
{
    if (!isset($_SESSION["key"]))
    {
        die(json_encode(["message" => "Expired Session. Refreshing..."]));
    }
    $csrf = hash_hmac('sha256', $_ENV["recovery_key"], $_SESSION['key']);
    $client_csrf = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "DELETE")
    {
        if (isset(json_decode(file_get_contents("php://input"))->csrf) && json_decode(file_get_contents("php://input")) !== null)
        {
            $client_csrf = json_decode(file_get_contents("php://input"))->csrf;
        }
        else
        {
            die(json_encode(["message" => "Expired Session. Refreshing..."]));
        }
        if (empty($csrf) || empty($client_csrf) || !hash_equals($csrf, $client_csrf))
        {
            die(json_encode(["message" => "Expired Session. Refreshing..."]));
        }
    }
}
?>