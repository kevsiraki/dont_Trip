<?php
require_once 'config.php';

if (isset($_GET['key']) && isset($_GET['token']))
{
    $email = $_GET['key'];
    $token = $_GET['token'];
    $date = date('Y-m-d H:i:s');
    $sql = "SELECT * FROM users WHERE email_verification_link = ? and email= ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_token, $param_email);
        $param_token = $token;
        $param_email = $email;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    if (mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_array($result);
        if ($row['email_verified_at'] == NULL)
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
            $msg = "Congratulations! Your email has been verified.";
        }
        else
        {
            $msg = "You have already verified your account with us.";
        }
    }
    else
    {
        $msg = "This email has not been registered with us.";
    }
}
else
{
    $msg = "Invalid Token.";
}
?>