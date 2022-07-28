<?php
require_once '../backend/redirect_backend.php'; 
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
$google_oauth = new Google_Service_Oauth2($client);
if (isset($_GET['code'])&&isset($_GET['scope'])) {
	$client->authenticate($_GET['code']);
	$access_token = $client->getAccessToken();
	$_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var($redirectUri,     FILTER_SANITIZE_URL)); 
}

if (isset($_SESSION['access_token']) &&   $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
    $_SESSION['access_token'] = $client->getAccessToken();
	$google_account_info = $google_oauth->userinfo->get();
	$email = $google_account_info->email;
	$name = $google_account_info->name;
	$_SESSION['loggedin'] = true;
	$_SESSION['username'] = $name." (Google)[".$google_account_info->id."]";
	$_SESSION['googleAvatar'] = $google_account_info->picture;
}
?>
