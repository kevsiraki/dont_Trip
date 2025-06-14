<?php
//Initialize Discord O-Auth API
require_once "../backend/config.php";
if(!empty($_SESSION)) {
	session_destroy();
}
$sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
$handler = new \ByJG\Session\JwtSession($sessionConfig);
header("Location: ". $_ENV["discord_url"]);
die;
?>
