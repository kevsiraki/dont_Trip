<?php
header("Content-Type: text/html");

require_once 'rateLimiter.php';
require_once "config.php";
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 


define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

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
function decrypt($data) {
    $key = key;
    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher = encryption_method);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac))
    {
        return $original_plaintext;
    }
}
?>
