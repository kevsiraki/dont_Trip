<?php 
require_once '../backend/redirect_backend.php'; 
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
if (isset($_GET['code'])) {
	header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL)); 
	$client->authenticate($_GET['code']);
	$access_token = $client->getAccessToken();
	$google_oauth = new Google_Service_Oauth2($client);
	$google_account_info = $google_oauth->userinfo->get();
	$email = $google_account_info->email;
	$name = $google_account_info->name;
	$_SESSION['loggedin'] = true;
	$_SESSION['username'] = $name."(G)";
}
?>
