<?php
session_start();
require_once "config.php";
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
require_once 'vendor/autoload.php';
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
$secret = $userResults["tfa"];
$code = trim($_POST["tfa"]);
if ($g->checkCode($secret, $code))
{
	$_SESSION["loggedin"] = true;
	echo 1;
}
else if (!($g->checkCode($secret, $code)))
{
    if (empty($code))
    {
        echo "Please enter 2FA OTP.";
    }
    else
    {
        echo "Incorrect/Expired.";
    }
}
?>
