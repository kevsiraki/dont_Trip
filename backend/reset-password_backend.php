<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'config.php';
require_once 'middleware.php';
require_once 'rateLimiter.php';

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

$data = json_decode(file_get_contents("php://input"));

csrf();

$sql = "SELECT * FROM users WHERE username = ? ;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $_SESSION["username"];
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userResults = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
//Validate new password
if (!isset($data->new_password) || empty(trim($data->new_password)))
{
    $new_password_err = "Please fill in all fields.";
    die(json_encode(["message" => $new_password_err]));
}
else if (password_verify(trim($data->new_password) , trim($userResults["password"])))
{
    $new_password_err = "Password used recently.";
    die(json_encode(["message" => $new_password_err]));
}
else if ((strlen(trim($data->new_password)) < 8 || strlen(trim($data->new_password)) > 25) || !(preg_match('/[A-Za-z]/', trim($data->new_password)) && preg_match('/[0-9]/', trim($data->new_password)) && preg_match('/[A-Z]/', trim($data->new_password)) && preg_match('/[a-z]/', trim($data->new_password))))
{
    $new_password_err = "Weak password.";
    die(json_encode(["message" => $new_password_err]));
}
else
{
    $new_password = trim($data->new_password);
}
//Validate confirm password
if (!isset($data->confirm_password) || empty(trim($data->confirm_password)))
{
    $confirm_password_err = "Please fill in all fields.";
    die(json_encode(["message" => $confirm_password_err]));
}
else
{
    if (empty($new_password_err) && $new_password != trim($data->confirm_password))
    {
        $confirm_password_err = "Passwords not matching.";
        die(json_encode(["message" => $confirm_password_err]));
    }
    else if (empty($new_password_err))
    {
        $confirm_password = trim($data->confirm_password);
    }
}
if (empty(trim($data->confirm_password)) || empty(trim($data->new_password)))
{
    $new_password_err = "Please fill in all fields.";
    die(json_encode(["message" => $new_password_err]));
}
if (empty($new_password_err) && empty($confirm_password_err))
{
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
        $param_username = $_SESSION["username"];
        if (mysqli_stmt_execute($stmt))
        {
            $innerSql = "UPDATE failed_login_attempts SET username = null, otp = null WHERE username = ? ";
            if ($innerStmt = mysqli_prepare($link, $innerSql))
            {
                mysqli_stmt_bind_param($innerStmt, "s", $param_username);
                $param_username = $userResults["username"];
                mysqli_stmt_execute($innerStmt);
                mysqli_stmt_close($innerStmt);
            }
            session_regenerate_id(true);
            session_destroy();
            die(json_encode(["message" => 1]));
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($link);
?>
