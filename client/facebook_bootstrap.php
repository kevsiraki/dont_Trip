<?php
if(!isset($_SESSION)) 
{ 
	session_start(); 
}
require_once "../backend/config.php";
$fb = new Facebook\Facebook(['app_id' => $_ENV['app_id'], 'app_secret' => $_ENV['app_secret'], 'default_graph_version' => 'v2.5', ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
$loginUrl = $helper->getLoginUrl('https://donttrip.technologists.cloud/donttrip/backend/facebook_callback', $permissions);
header("location: " . $loginUrl);
?>
