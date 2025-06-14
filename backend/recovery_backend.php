<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'config.php';
require_once 'middleware.php';
require_once 'rateLimiter.php';

$password = $password_err = "";

$data = json_decode(file_get_contents("php://input"));

csrf();

// Check if password is empty
if (!isset($data->password) || empty(trim($data->password)))
{
    $password_err = "Please enter your password.";
    die(json_encode(["message" => $password_err]));
}
else
{
    $password = trim($data->password);
}

$sql = "SELECT * FROM failed_login_attempts WHERE username = ? ";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = trim($data->username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userResults = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

$username = $userResults["username"];

if (empty($password_err))
{
    $sql = "SELECT username, otp FROM failed_login_attempts WHERE username = ? and otp IS NOT NULL";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        if (mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) >= 1)
            {
                mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt))
                {
                    if (password_verify($password, $hashed_password))
                    {
                        if (!isset($_SESSION))
                        {
                            $sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
                            $handler = new \ByJG\Session\JwtSession($sessionConfig);
                        }
                        else if (isset($_SESSION))
                        {
                            session_destroy();
                            $sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
                            $handler = new \ByJG\Session\JwtSession($sessionConfig);
                        }
                        $_SESSION["loggedin"] = true;
                        $_SESSION["authorized"] = false;
                        $_SESSION["username"] = $username;
                        $_SESSION['loginTime'] = time();
                        die(json_encode(["message" => 1]));
                    }
                    else
                    {
                        $password_err = "Incorrect Password.";
                        die(json_encode(["message" => $password_err]));
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);
?>