<?php
require_once "../backend/config.php";
if(!isset($_SESSION)) 
{ 
	$sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
    $handler = new \ByJG\Session\JwtSession($sessionConfig);
}
$fb = new Facebook\Facebook(['app_id' => $_ENV['app_id'], 'app_secret' => $_ENV['app_secret'], 'default_graph_version' => 'v2.5', ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
$loginUrl = $helper->getLoginUrl('https://www.donttrip.org/donttrip/facebook_callback', $permissions);
header("location: " . $loginUrl);
?>
