<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'config.php';
require_once 'middleware.php';
require_once 'rateLimiter.php';

$password = $password_err = "";

$username = $_SESSION["username"];

csrf();

//Validate Password
if (empty(trim($_POST["password"])))
{
    $password_err = "Please enter your password.";
    die(json_encode(["message" => $password_err]));
}
else
{
    $password = trim($_POST["password"]);
}
if (empty($password_err))
{
    $sql = "SELECT username, password FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        if (mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1)
            {
                mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt))
                {
                    if (password_verify($password, $hashed_password))
                    {
                        //Delete user's search history
                        $sql = "DELETE FROM searches WHERE username = ? ;";
                        if ($stmt = mysqli_prepare($link, $sql))
                        {
                            mysqli_stmt_bind_param($stmt, "s", $param_username);
                            $param_username = $username;
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }
                        //Delete user's password reset attempts
                        $sql = "DELETE FROM password_reset_temp WHERE email in (SELECT email FROM users WHERE username = ? );";
                        if ($stmt = mysqli_prepare($link, $sql))
                        {
                            mysqli_stmt_bind_param($stmt, "s", $param_username);
                            $param_username = $username;
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }
                        //Delete user's account
                        $sql = "DELETE FROM users WHERE username = ? ;";
                        if ($stmt = mysqli_prepare($link, $sql))
                        {
                            mysqli_stmt_bind_param($stmt, "s", $param_username);
                            $param_username = $username;
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }
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
}
mysqli_close($link);
?>
