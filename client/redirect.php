<?php
require_once '../backend/redirect_backend.php'; 

if(!isset($_SESSION)) 
{ 
	$sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
    $handler = new \ByJG\Session\JwtSession($sessionConfig);
}

$google_oauth = new Google_Service_Oauth2($client);

//Worked on 7/13/2025 to improve security. Catch fatal errors from displaying to the client.

if (isset($_GET['code']) && isset($_GET['scope'])) {
    try {
        $client->authenticate($_GET['code']);
        $access_token = $client->getAccessToken();
        $_SESSION['access_token'] = $access_token;
        header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL));
    } catch (Google_Auth_Exception $e) {exit;} 
	catch (Exception $e) {exit;}
}

/*
if (isset($_GET['code'])&&isset($_GET['scope'])) {
	$client->authenticate($_GET['code']);
	$access_token = $client->getAccessToken();
	$_SESSION['access_token'] = $client->getAccessToken();
	header('Location: ' . filter_var($redirectUri, FILTER_SANITIZE_URL)); 
}
*/

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
    $_SESSION['access_token'] = $client->getAccessToken();
	$google_account_info = $google_oauth->userinfo->get();
	$email = $google_account_info->email;
	$name = $google_account_info->name;
	if(isset($_SESSION))
	{
		session_destroy();
		$sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
		$handler = new \ByJG\Session\JwtSession($sessionConfig);
	}
	unset($_SESSION['userData']);
	unset($_SESSION['googleAvatar']);
	$_SESSION['loggedin'] = true;
	$_SESSION['username'] = $name." (Google)[".$google_account_info->id."]";
	$_SESSION['googleAvatar'] = $google_account_info->picture;
	$_SESSION['loginTime'] = time();

	$redirect_url = "../client/dt";
	header("Location: $redirect_url");
}
?>
