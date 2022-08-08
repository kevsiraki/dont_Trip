<?php
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$base_backend = '/donttrip/backend/';
$base_client = '/donttrip/client/';

if ($uri === '/donttrip/' || $uri === '/donttrip/login')
{
    killSessionUsername();
    startSession();
}
else if ($uri === $base_client . 'two_factor_auth' || $uri === $base_backend . 'two_factor_auth_backend')
{
    startSession();
    checkPreLoginUsername();
    checkExpiryUpdateSSID();
}
else if ($uri === $base_client . 'register')
{
    startSession();
    checkExpiryUpdateSSID();
}
else if ($uri === $base_client . 'settings' || $uri === $base_backend . 'settings_backend')
{
    startSession();
    checkAuthorized();
    checkExpiryUpdateSSID();
}
else if ($uri === $base_client . 'searches' || $uri === $base_client . 'state' || $uri === $base_backend . 'state_backend' || $uri === $base_backend . 'searches_backend' || $uri === $base_backend . 'delete_search')
{
    startSession();
    checkLoggedIn();
    checkAuthorized();
    checkExpiryUpdateSSID();
}
else if ($uri === $base_client . 'reset-password' || $uri === $base_backend . 'reset-password_backend')
{
    startSession();
    checkLoggedIn();
    checkExpiryUpdateSSID();
}
else if ($uri === $base_client . 'locked' || $uri === $base_backend . 'recovery_backend')
{
    startSession();
    checkLocked();
}
else if ($uri === $base_client . 'dt' || $uri === $base_backend . 'dt_backend')
{
    startSession();
    checkAuthorized();
    checkExpiryUpdateSSID();
}
else if ($uri === $base_client . 'delete_confirmation' || $uri === $base_backend . 'delete_confirmation_backend')
{
    startSession();
    checkLoggedIn();
    checkAuthorized();
    checkExpiryUpdateSSID();
}
function checkExpiryUpdateSSID()
{
    if (isset($_SESSION['loginTime']) && $_SESSION['loginTime'] + $_ENV["expire"] < time())
    {
        $_SESSION = array();
        session_destroy();
        header('location: https://donttrip.technologists.cloud/donttrip/client/session_expired.php');
        die;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET")
    {
        if (isset($_SESSION['loginTime']))
        {
            if ($_SESSION['loginTime'] + ($_ENV["expire"] / 3) < time())
            {
                session_regenerate_id(true);
            }
            $_SESSION['loginTime'] = time();
        }
    }
}
function checkAuthorized()
{
    if (isset($_SESSION["authorized"]) && $_SESSION["authorized"] === false)
    {
        header("location: ../backend/logout.php");
        die;
    }
}
function checkLoggedIn()
{
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: ../login.php");
        die;
    }
}
function checkLocked()
{
    if (!isset($_SESSION["locked"]) || $_SESSION["locked"] !== true)
    {
        header("location: ../login.php");
        die;
    }
}
function checkPreLoginUsername()
{
    if (empty($_SESSION["username"]))
    {
        header("location: ../login.php");
        die;
    }
}
function startSession()
{
    if (!isset($_SESSION))
    {
        session_start();
    }
}
function killSessionUsername()
{
    if (isset($_SESSION["username"]))
    {
        unset($_SESSION["username"]);
    }
}
function csrf()
{

    if (!isset($_SESSION["key"]))
    {
        die(json_encode(["message" => "Invalid API Access."]));
    }

    $csrf = hash_hmac('sha256', $_ENV["recovery_key"], $_SESSION['key']);
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($csrf) || empty($_POST['csrf']) || !hash_equals($csrf, $_POST['csrf']))
        {
            die(json_encode(["message" => "Token Expired, Refresh the Page."]));
        }
    }
}
?>