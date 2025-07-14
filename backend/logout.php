<?php
require "redirect_backend.php";
$sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
$handler = new \ByJG\Session\JwtSession($sessionConfig);
if (isset($client) && isset($_SESSION["access_token"]))
{
    unset($_SESSION['access_token']);
    $client->revokeToken();
}
unset($_SESSION['userid']);
unset($_SESSION['userData']);
unset($_SESSION['googleAvatar']);
$_SESSION = array();
session_destroy();
header("location: /");
die;