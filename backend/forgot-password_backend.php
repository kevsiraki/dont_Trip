<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'config.php';
require_once 'middleware.php';
require_once 'rateLimiter.php';
require_once 'helpers.php';
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';

date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");

$email = $new_password = $confirm_password = $code = $tfa_err = "";
$new_password_err = $confirm_password_err = "";
$expired = 0;

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

$data = json_decode(file_get_contents("php://input"));

csrf();

if (isset($data->email))
{
	$email = $data->email;
}
else
{
	die(json_encode(["error" => "Expired Link."]));
}
	
$sql = "SELECT * FROM users WHERE email = ? ;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_email);
    $param_email = $email;
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userResults = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
$key = $data->keyTO;
$curDate = $date;
$sql = "SELECT * FROM password_reset_temp WHERE keyTO = ? AND email = ? ;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "ss", $param_key, $param_email);
    $param_key = $key;
    $param_email = $email;
    if (mysqli_stmt_execute($stmt))
    {
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_assoc($result);
        $row = mysqli_num_rows($result);
        if ($row == "")
        {
            $expired = 1;
        }
        else
        {
            $row = $array;
            $expDate = $row['expD'];
            if ($expDate < $curDate)
            {
                $innerSql = "DELETE FROM password_reset_temp WHERE email = ?";
                if ($innerStmt = mysqli_prepare($link, $innerSql))
                {
                    mysqli_stmt_bind_param($innerStmt, "s", $param_email);
                    $param_email = $email;
                    mysqli_stmt_execute($innerStmt);
                    mysqli_stmt_close($innerStmt);
                }
                $expired = 1;
            }
        }
    }
    mysqli_stmt_close($stmt);
}
// Validate new password
if (empty(trim($data->new_password)))
{
    $new_password_err = "Please fill in all fields.";
    die(json_encode(["error" => $new_password_err]));
}
else if (password_verify(trim($data->new_password) , trim($userResults['password'])))
{
    $new_password_err = 'Password used recently.';
    die(json_encode(["error" => $new_password_err]));
}
else if ((strlen(trim($data->new_password)) < 8 || strlen(trim($data->new_password)) > 25) || !(preg_match('/[A-Za-z]/', trim($data->new_password)) && preg_match('/[0-9]/', trim($data->new_password)) && preg_match('/[A-Z]/', trim($data->new_password)) && preg_match('/[a-z]/', trim($data->new_password))))
{
    $new_password_err = "Weak password.";
    die(json_encode(["error" => $new_password_err]));
}
else
{
    $new_password = trim($data->new_password);
}
// Validate confirm new password
if (empty(trim($data->confirm_password)))
{
    $confirm_password_err = "Please fill in all fields.";
    die(json_encode(["error" => $confirm_password_err]));
}
else
{
    if (empty($new_password_err) && $new_password != trim($data->confirm_password))
    {
        $confirm_password_err = "Passwords not matching.";
        die(json_encode(["error" => $confirm_password_err]));
    }
    else if (empty($new_password_err))
    {
        $confirm_password = trim($data->confirm_password);
    }
}
if ($userResults["tfaen"] == 1)
{
    $g = new \Google\Authenticator\GoogleAuthenticator();
    $secret = decrypt($userResults["tfa"]);
    $code = trim($data->tfa);
    if ($g->checkCode($secret, $code))
    {
    }
    else if (!($g->checkCode($secret, $code)))
    {
        if (!empty(trim($data->new_password)) && !empty(trim($data->confirm_password)))
        {
            if (empty($code))
            {
                $tfa_err = "Please enter OTP.";
                die(json_encode(["error" => $tfa_err]));
            }
            else
            {
                $tfa_err = "Incorrect or Expired OTP.";
                die(json_encode(["error" => $tfa_err]));
            }
        }
    }
}
if (empty(trim($data->new_password)) || empty(trim($data->confirm_password)))
{
    die(json_encode(["error" => 'Please fill in all fields.']));
}
if (empty($new_password_err) && empty($tfa_err) && empty($confirm_password_err))
{
    $sql = "UPDATE users SET email_verified_at = ? WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_date, $param_email);
        $param_date = $date;
        $param_email = $email;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        if (isset($_SESSION))
        {
            session_destroy();
        }
        if (isset($_SESSION["message_shown"]))
        {
            unset($_SESSION["message_shown"]);
        }
        mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_email);
        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
        $param_email = $email;
        if (mysqli_stmt_execute($stmt))
        {
            $sql = "DELETE FROM password_reset_temp WHERE email = ?";
            if ($stmt2 = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt2, "s", $param_email);
                $param_email = $email;
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
            }
            die(json_encode(["success" => 1]));
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($link);
?>