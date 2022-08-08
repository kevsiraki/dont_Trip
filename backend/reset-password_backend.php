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
if (empty(trim($_POST["new_password"])))
{
    $new_password_err = "Please fill in all fields.";
    die(json_encode(["message" => $new_password_err]));
}
else if (password_verify(trim($_POST["new_password"]) , trim($userResults["password"])))
{
    $new_password_err = "Password used recently.";
    die(json_encode(["message" => $new_password_err]));
}
else if ((strlen(trim($_POST["new_password"])) < 8 || strlen(trim($_POST["new_password"])) > 25) || !(preg_match('/[A-Za-z]/', trim($_POST["new_password"])) && preg_match('/[0-9]/', trim($_POST["new_password"])) && preg_match('/[A-Z]/', trim($_POST["new_password"])) && preg_match('/[a-z]/', trim($_POST["new_password"]))))
{
    $new_password_err = "Weak password.";
    die(json_encode(["message" => $new_password_err]));
}
else
{
    $new_password = trim($_POST["new_password"]);
}
//Validate confirm password
if (empty(trim($_POST["confirm_password"])))
{
    $confirm_password_err = "Please fill in all fields.";
    die(json_encode(["message" => $confirm_password_err]));
}
else
{
    if (empty($new_password_err) && $new_password != trim($_POST["confirm_password"]))
    {
        $confirm_password_err = "Passwords not matching.";
        die(json_encode(["message" => $confirm_password_err]));
    }
    else if (empty($new_password_err))
    {
        $confirm_password = trim($_POST["confirm_password"]);
    }
}
if (empty(trim($_POST["confirm_password"])) || empty(trim($_POST["new_password"])))
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
