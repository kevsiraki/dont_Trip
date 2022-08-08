<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "config.php";
require_once 'middleware.php';
require_once 'helpers.php';
require_once 'geolocation.php';
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';

$response = array();

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

$data = json_decode(file_get_contents("php://input"));

if (isset($_SESSION['username']))
{
    $sql = "SELECT * FROM users WHERE username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $_SESSION['username'];
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

if (isset($data->two_factor) && !empty($userResults))
{
    if ($data->two_factor == 1 && ($userResults["tfaen"] == 0 || empty($userResults["tfaen"])))
    {
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $secret = $g->generateSecret();
        $sql = "UPDATE users SET tfaen = 1 WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $userResults["username"];
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $sql = "UPDATE users SET tfa = ? WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ss", $param_secret, $param_username);
            $param_secret = encrypt($secret);
            $param_username = $userResults["username"];
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $url = \Google\Authenticator\GoogleQrUrl::generate(urlencode($userResults["username"]) , urlencode($secret) , urlencode("Don't-Trip"));
		$response = array(
			"secret" => $secret,
			"qr" => $url
		);
    }
    else if ($data->two_factor == 0 && $userResults["tfaen"] == 1)
    {
		$response = array("message" => "2FA Disabled.");
        $sql = "UPDATE users SET tfaen = 0 WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $userResults["username"];
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $sql = "UPDATE users SET tfa = 0 WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $userResults["username"];
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
		
    }
	die(json_encode($response));
}
?>