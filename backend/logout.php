<?php
require "redirect_backend.php";
// Initialize the session
$sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
$handler = new \ByJG\Session\JwtSession($sessionConfig); // Unset all of the session variables
if (isset($client) && isset($_SESSION["access_token"]))
{
    unset($_SESSION['access_token']);
    $client->revokeToken();
}

$_SESSION = array();
// Destroy the session.
session_destroy();
// Redirect to login page
header("location: https://donttrip.org/donttrip/");
die;
?>