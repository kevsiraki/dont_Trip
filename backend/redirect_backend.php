<?php
//Initializes the Google O-Auth API

require_once "config.php";

$clientID = $_ENV['client_id'];
$clientSecret = $_ENV['client_secret'];
$redirectUri = $_ENV['redirect_uri'];

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
?>