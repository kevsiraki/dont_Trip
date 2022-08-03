<?php
header("Content-Type: text/html");

include('php-csrf.php');
require_once 'helpers.php';
require_once "config.php";
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';

if(isset($_SESSION['loginTime'])&&$_SESSION['loginTime']+$_ENV["expire"] < time()) { 
	$_SESSION = array();
	// Destroy the session.
	session_destroy();
	header('location: https://donttrip.technologists.cloud/donttrip/client/session_expired.php');
	die;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_SESSION['loginTime'])) {
		if($_SESSION['loginTime']+($_ENV["expire"]/3) < time()) {
			session_regenerate_id(true); 
		}
		$_SESSION['loginTime'] = time();
	}
}

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

require_once 'rateLimiter.php';

//Query 2FA user secret
$sql = "SELECT * FROM users WHERE username = ? ;";
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

//Authenticate OTP input

$g = new \Google\Authenticator\GoogleAuthenticator();
$secret = decrypt($userResults["tfa"]);
$code = trim($_POST["tfa"]);
if ($g->checkCode($secret, $code))
{
    $_SESSION["loggedin"] = true;
    echo 1;
	session_regenerate_id(true);
}
else if (!($g->checkCode($secret, $code)))
{
    if (empty($code))
    {
        die("Please enter 2FA OTP.");
    }
    else
    {
        die("Incorrect/Expired.");
    }
}
}
?>