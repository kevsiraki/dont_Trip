<?php
//Initializes the Google O-Auth API
require_once "config.php";

$isAuth = "";

$clientID = $_ENV['client_id'];
$clientSecret = $_ENV['client_secret'];
$redirectUri = $_ENV['redirect_uri'];

//Creates Client Request to access Google Login API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (!isset($_GET['code']))
{
    $isAuth = "yes";
}
?>