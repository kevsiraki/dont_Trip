<?php
/**
* Function list:
* - imageUrl()
* - getRandomBytes()
* - generatePassword()
*/
header("Content-Type: text/html");
// Include config file
require_once "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION))
{
    session_start();
}
if (!empty($_SESSION["authorized"]) && $_SESSION["authorized"] === false)
{
    header("location: ../login.php");
    die;
}

// Define variables and initialize with empty values
$username = $email = $new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = $email_err = $username_err = "";

function imageUrl()
{
    return "https://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "../") + 1) . "donttrip/icons/dont_Trip.png";
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    require_once 'rateLimiter.php';
    // Check if email is valid
    if (empty(trim($_POST["email"])))
    {
        $email_err = "Please enter an e-mail.";
        die($email_err);
    }
    else
    {
        $email = trim($_POST["email"]);
        $sql = "SELECT * FROM users WHERE email = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameters
            $param_email = $email;
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $userResults = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        }
        if (mysqli_num_rows($result) == 0)
        {
            $email_err = "E-mail address invalid.";
            die($email_err);
        }
        else
        {
            $email = trim($_POST["email"]);
        }
    }
    $sql = "SELECT COUNT(*) as cntEmail FROM password_reset_temp WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = $email;
        // Attempt to execute the prepared statement
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
            die($email_err);
        }
    }
    $sql = "SELECT sent_time FROM password_reset_temp WHERE email = ? ORDER BY sent_time DESC LIMIT 1;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = $email;
        // Attempt to execute the prepared statement
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
            die($email_err);
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
        echo 1;
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
            // enable SMTP authentication
            $mail->SMTPAuth = true;
            // email username
            $mail->Username = $_ENV['email'];
            // email password
            $mail->Password = $_ENV['password'];
            $mail->SMTPSecure = $_ENV['encryption'];
            // sets XXX as the SMTP server
            $mail->Host = $_ENV['host'];
            // set the SMTP port for the XXX server
            $mail->Port = $_ENV['port'];
            $mail->From = $_ENV['email'];
            $mail->FromName = "WebMaster";
            $mail->addAddress($_POST["email"], $userResults["username"]);
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
            $html = str_replace("{{LINK}}", "https://donttrip.technologists.cloud/donttrip/client/forgot-password.php?key=" . $_POST["email"] . "&token=" . $key . "", $html);
            $html = str_replace("{{GREETING}}", $greeting, $html);
            $mail->Body = $html;
        }
        catch(phpmailerException $e)
        {
            die($e->errorMessage());
        }
        catch(Exception $e)
        {
            die(404); //Boring error messages from anything else!
            
        }
        if ($mail->Send())
        {
            /*
            $sql = "UPDATE password_reset_temp SET keyTO = null WHERE email = ? ;";
            if ($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                // Set parameters
                $param_email = $email;
                // Attempt to execute the prepared statement
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $userResults = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
            } 
            */
            if (isset($_SESSION["message_shown"]))
            {
                unset($_SESSION["message_shown"]);
            }
            $sql = "INSERT INTO password_reset_temp (email, keyTo, expD, sent_time) VALUES (?,?,?,?)";
            if ($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_key, $param_expDate, $param_sent_time);
                // Set parameters
                $param_email = $email;
                $param_key = $key;
                $param_expDate = $expDate;
                $param_sent_time = $sent_time;
                // Attempt to execute the prepared statement
                mysqli_stmt_execute($stmt);
                // Close statement
                mysqli_stmt_close($stmt);
            }
            mysqli_close($link);
            die(1);
        }
        else
        {
            die("Mail Error - >" . $mail->ErrorInfo);
        }
    }
}

function getRandomBytes($nbBytes = 32)
{
    $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
    if (false !== $bytes && true === $strong)
    {
        return $bytes;
    }
    else
    {
        throw new \Exception("Unable to generate secure token from OpenSSL.");
    }
}

function generatePassword($length)
{
    return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length + 1))) , 0, $length);
}
?>