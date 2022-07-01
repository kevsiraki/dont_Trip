<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$clientID = $_ENV['client_id'];
$clientSecret = $_ENV['client_secret'];
$redirectUri = $_ENV['redirect_uri'];
?>