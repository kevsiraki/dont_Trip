<?php
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$base = '/donttrip/client/';
$base_backend ='/donttrip/backend/';
if ($uri ===  '/donttrip/' || $uri ===  '/donttrip/login')
{
	killSessionUsername();
	startSession();
}
else if ($uri === $base . 'two_factor_auth')
{
	startSession();
	checkPreLoginUsername();
    checkExpiryUpdateSSID(); 
}
else if ($uri === $base . 'register')
{
	startSession();
    checkExpiryUpdateSSID(); 
}
else if ($uri === $base . 'settings' || $uri === $base_backend . 'settings_backend')
{
    startSession();
    checkAuthorized(); 
    checkExpiryUpdateSSID(); 
}
else if ($uri === $base . 'searches' || $uri === $base . 'state')
{
    startSession();
    checkLoggedIn();
    checkAuthorized();
    checkExpiryUpdateSSID(); 
}
else if ($uri === $base . 'reset-password')
{
	startSession();
    checkLoggedIn();
    checkExpiryUpdateSSID(); 
}
else if ($uri === $base . 'locked')
{
	startSession();
	checkLocked();
}
else if ($uri === $base . 'dt')
{
    startSession();
    checkAuthorized();
    checkExpiryUpdateSSID();
}
else if ($uri === $base . 'delete_confirmation')
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
?>