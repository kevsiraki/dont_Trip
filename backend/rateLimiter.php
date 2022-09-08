<?php
require_once 'helpers.php';
require_once "config.php";
if (!isset($_SESSION))
{
    if (!isset($_SESSION))
    {
        $sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
        $handler = new \ByJG\Session\JwtSession($sessionConfig);
    }
}
if (isset($_SESSION['LAST_CALL']))
{
    $last = $_SESSION['LAST_CALL'];
    $curr = date("Y-m-d h:i:s.u");
    if (compareMilliseconds($last, $curr, 250))
    {
        die(json_encode(["message" => "Wait..."]));
    }
}
$_SESSION['LAST_CALL'] = date('Y-m-d h:i:s.u');
?>