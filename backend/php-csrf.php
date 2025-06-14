<?php
require_once "config.php";
if (isset($_SESSION) && isset($_SESSION['key']))
{
    unset($_SESSION['key']);
}
else if (!isset($_SESSION))
{
    $sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
    $handler = new \ByJG\Session\JwtSession($sessionConfig);
}
if (empty($_SESSION['key']))
{
    $_SESSION['key'] = bin2hex(random_bytes(32));
}

//create CSRF token
$csrf = hash_hmac('sha256', $_ENV["recovery_key"], $_SESSION['key']);
?>