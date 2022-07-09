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

$salt = $_ENV['2fa_salt'];
$sql3 = "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "' ";
$result3 = mysqli_query($link, $sql3);
$basics = mysqli_fetch_assoc($result3);


if (isset($_POST['two_factor']))
{
	ob_start();
	if($_POST['two_factor']=="true"&& $basics["tfaen"] == 0) {
		ob_end_clean(); 
		$g = new \Google\Authenticator\GoogleAuthenticator();
		$secret = str_shuffle($salt);
		mysqli_query($link,"UPDATE users SET tfaen=1 WHERE username = '".$basics["username"]."';");
		mysqli_query($link,"UPDATE users SET tfa='".$secret ."' WHERE username = '".$basics["username"]."';");   
		$url =  \Google\Authenticator\GoogleQrUrl::generate(urlencode($basics["username"]), urlencode($secret),urlencode("Don't-Trip"));
	$response = "<br>2FA Enabled.  Secret: <b>{$secret}
</b><br><br><img class=\"center\" src = \"{$url}\" alt = \"QR Code\" />";
	}
	else if ($_POST['two_factor']=="false"&&$basics["tfaen"] == 1 ) {
		ob_end_clean(); 
		$response = "<br>2FA Disabled.";
		mysqli_query($link, "UPDATE users SET tfaen=0 WHERE username = '" . $basics["username"] . "';");
		mysqli_query($link, "UPDATE users SET tfa='0' WHERE username = '" . $basics["username"] . "';");
	}
	echo $response;
	die;
}

else if (isset($_POST['delete_searches'])) {
    mysqli_query($link, "DELETE FROM searches WHERE username = '" . trim($_SESSION["username"]) . "';");
	die;
}

?>