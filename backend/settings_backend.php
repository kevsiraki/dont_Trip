<?php
ini_set('allow_url_fopen', 'On');
require_once "config.php";
require_once 'vendor/autoload.php';
require_once 'geolocation.php';
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

$response = '';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$salt = $_ENV['2fa_salt']; //2FA base key.

$sql = "SELECT * FROM users WHERE username = ? ;"; //Get user information.
if ($stmt = mysqli_prepare($link, $sql))
{
	// Bind variables to the prepared statement as parameters
	mysqli_stmt_bind_param($stmt, "s", $param_username);
	// Set parameters
	$param_username = $_SESSION['username'];
	// Attempt to execute the prepared statement
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$userResults = mysqli_fetch_assoc($result);
	mysqli_stmt_close($stmt);
}
//AJAX request to enable/disable 2FA
if (isset($_POST['two_factor']))
{
	ob_start();
	if($_POST['two_factor']=="true"&& $userResults["tfaen"] == 0) {
		ob_end_clean(); 
		$g = new \Google\Authenticator\GoogleAuthenticator();
		$secret = str_shuffle($salt);
		$sql = "UPDATE users SET tfaen = 1 WHERE username = ? ;";
		if ($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			// Set parameters
			$param_username = $userResults["username"];
			// Attempt to execute the prepared statement
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		$sql = "UPDATE users SET tfa = ? WHERE username = ? ;";
		if ($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ss", $param_secret, $param_username);
			// Set parameters
			$param_secret = $secret;
			$param_username = $userResults["username"];
			// Attempt to execute the prepared statement
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}		
		$url =  \Google\Authenticator\GoogleQrUrl::generate(urlencode($userResults["username"]), urlencode($secret),urlencode("Don't-Trip"));
		$response = "
						<script>
							function copySecret() {
								var copyText = document.getElementById(\"copy\").innerText;
								var elem = document.createElement(\"textarea\");
								document.body.appendChild(elem);
								elem.value = copyText;
								elem.select();
								elem.setSelectionRange(0, 99999); /* For mobile devices */
								navigator.clipboard.writeText(elem.value);
								alert(\"Copied Secret: \" + copyText+\"\\nPaste into your authenticator app.\");
								document.body.removeChild(elem);
							}
						</script>
						<br>2FA On. Secret: <b id=\"copy\">{$secret}</b>
						<button class = \"btn btn-outline-info btn-sm\" onclick=\"copySecret();\">📋</button>
						<br><br>
						<img class=\"center\" src = \"{$url}\" alt = \"QR Code\" />
					";
	}
	else if ($_POST['two_factor']=="false"&&$userResults["tfaen"] == 1 ) {
		ob_end_clean(); 
		$response = "<br>2FA Disabled.";
		$sql = "UPDATE users SET tfaen = 0 WHERE username = ? ;";
		if ($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			// Set parameters
			$param_username = $userResults["username"];
			// Attempt to execute the prepared statement
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		$sql = "UPDATE users SET tfa = 0 WHERE username = ? ;";
		if ($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			// Set parameters
			$param_username = $userResults["username"];
			// Attempt to execute the prepared statement
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
	}
	echo $response;
	die;
}
//AJAX request to clear search history.
else if (isset($_POST['delete_searches'])) {
	$sql = "DELETE FROM searches WHERE username = ? ;";
	if ($stmt = mysqli_prepare($link, $sql))
	{
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $param_username);
		// Set parameters
		$param_username = $_SESSION['username'];
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	die;
}
?>