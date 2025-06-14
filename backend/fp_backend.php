<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once "config.php";
require_once 'helpers.php';
require_once 'middleware.php';
require_once 'rateLimiter.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username = $email = $new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = $email_err = $username_err = "";

$data = json_decode(file_get_contents("php://input"));

csrf();

//Validate email
if (!isset($data->email) || empty(trim($data->email)))
{
    $email_err = "Please enter an e-mail.";
	die(json_encode(["message" => $email_err]));
}
else
{
    $email = trim($data->email);
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
    if (mysqli_num_rows($result) == 0)
    {
        $email_err = "E-mail address invalid.";
        die(json_encode(["message" => $email_err]));
    }
    else
    {
        $email = trim($data->email);
    }
}
$sql = "SELECT COUNT(*) as cntEmail FROM password_reset_temp WHERE email = ? ;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_email);
    $param_email = $email;
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}
if (mysqli_num_rows($result))
{
    $row = mysqli_fetch_array($result);
    $count = $row['cntEmail'];
    if ($count >= 5)
    {
        $email_err = "Too many requests.";
        die(json_encode(["message" => $email_err]));
    }
}
$sql = "SELECT sent_time FROM password_reset_temp WHERE email = ? ORDER BY sent_time DESC LIMIT 1;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_email);
    $param_email = $email;
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
if (!empty($row))
{
    $count = $row['sent_time'];
    if ($count + 120 > time())
    {
        $email_err = "Wait to request another reset.";
        die(json_encode(["message" => $email_err]));
    }
}
// Check if email is validated
$expFormat = mktime(date("H") , date("i") , date("s") , date("m") , date("d") + 1, date("Y"));
$expDate = date("Y-m-d H:i:s", $expFormat);
$key = hash("SHA512", $email);
$addKey = hash("SHA512", generatePassword(8));
$key = substr(str_shuffle($key . $addKey) , 0, 64); //have fun cracking this lol
if (empty($email_err))
{
    $sent_time = time();
    require_once "phpmail/src/Exception.php";
    require_once "phpmail/src/PHPMailer.php";
    require_once "phpmail/src/SMTP.php";
    $mail = new PHPMailer(true);
    try
    {
        $mail->CharSet = "utf-8";
        $mail->IsSMTP();
        $mail->IsHTML();
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['email'];
        $mail->Password = $_ENV['password'];
        $mail->SMTPSecure = $_ENV['encryption'];
        $mail->Host = $_ENV['host'];
        $mail->Port = $_ENV['port'];
        $mail->From = $_ENV['email'];
        $mail->FromName = "WebMaster";
        $mail->addAddress($data->email, $userResults["username"]);
        $mail->Subject = "Reset your Password";
        $mail->IsHTML(true);
        date_default_timezone_set("America/Los_Angeles");
        $date = date("Y-m-d H:i:s");
        $greeting = "";
        if (date('H') < 12)
        {
            $greeting = "Good morning";
        }
        else if (date('H') >= 12 && date('H') < 18)
        {
            $greeting = "Good afternoon";
        }
        else if (date('H') >= 18)
        {
            $greeting = "Good evening";
        }
        $html = file_get_contents('../email_templates/forgot_password.html');
        $html = str_replace("{{USERNAME}}", $userResults["username"], $html);
        $html = str_replace("{{IMGICON}}", imageUrl() , $html);
        $html = str_replace("{{LINK}}", "https://www.donttrip.org/donttrip/client/forgot-password.php?key=" . $data->email . "&token=" . $key . "", $html);
        $html = str_replace("{{GREETING}}", $greeting, $html);
        $mail->Body = $html;
    }
    catch(phpmailerException $e)
    {
		die(json_encode(["message" => $e->errorMessage()]));
    }
    catch(Exception $e)
    {
        die(json_encode(["message" => 404]));
        
    }
    if ($mail->Send())
    {
        if (isset($_SESSION["message_shown"]))
        {
            unset($_SESSION["message_shown"]);
        }
        $sql = "INSERT INTO password_reset_temp (email, keyTo, expD, sent_time) VALUES ( ?, ?, ?, ? )";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_key, $param_expDate, $param_sent_time);
            $param_email = $email;
            $param_key = $key;
            $param_expDate = $expDate;
            $param_sent_time = $sent_time;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
        die(json_encode(["message" => 1]));
    }
    else
    {
        die(json_encode(["message" => "Mail Error - >" . $mail->ErrorInfo]));
    }
}
?>