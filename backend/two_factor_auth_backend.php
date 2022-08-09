<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'helpers.php';
require_once 'config.php';
require_once 'middleware.php';
require_once 'rateLimiter.php';
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

$data = json_decode(file_get_contents("php://input"));

csrf();

if(!isset($data->tfa))
{
    die(json_encode(["message" => "Please enter 2FA OTP."]));
}

//Retrieve 2FA user info
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

//Authenticate OTP input
$g = new \Google\Authenticator\GoogleAuthenticator();
$secret = decrypt($userResults["tfa"]);
$code = trim($data->tfa);
if ($g->checkCode($secret, $code))
{
    $_SESSION["loggedin"] = true;
    die(json_encode(["message" => 1]));
    session_regenerate_id(true);
}
else if (!($g->checkCode($secret, $code)))
{
    if (empty($code))
    {
        die(json_encode(["message" => "Please enter 2FA OTP."]));
    }
    else
    {
        die(json_encode(["message" => "Incorrect/Expired."]));
    }
}
?>