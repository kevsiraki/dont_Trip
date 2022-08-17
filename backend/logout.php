<?php
require "redirect_backend.php";
// Initialize the session
session_start();
// Unset all of the session variables
if (isset($client) && isset($_SESSION["access_token"]))
{
    unset($_SESSION['access_token']);
    $client->revokeToken();
}
session_regenerate_id(true);
$_SESSION = array();
// Destroy the session.
session_destroy();
// Redirect to login page
header("location: https://donttrip.org/donttrip/");
die;
?>