<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'redirect_backend.php';
require_once 'middleware.php';
require_once 'helpers.php';
require_once 'rateLimiter.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username = $password = $usernameOrEmail = "";
$username_err = $password_err = $login_err = "";
$tfa_en = false;
$lock = false;
$total_count = 0;
$first_limit = 5;
$second_limit = 20;

date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");

$data = json_decode(file_get_contents("php://input"));

csrf();

//First step of brute force prevention, lock user out consecutively for 10 seconds after 5 failed attempts;
$total_count = getFailedAttempts($link, $ip_address);
if ($total_count >= $first_limit && $total_count < $second_limit)
{
    $sql = "SELECT * from failed_login_attempts where ip = ? ORDER BY attempt_time DESC LIMIT 1 ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_ip);
        $param_ip = $ip_address;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $lastAttempt = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    //undo this security check after 1 day if they don't exceed 20 attempts.
    if ($lastAttempt['attempt_time'] + (24 * 3600) < time())
    {
        $sql = "DELETE from failed_login_attempts where ip = ? ";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $param_ip);
            $param_ip = $ip_address;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $lock = false;
    }
    if ($lastAttempt['attempt_time'] + 10 > time())
    {
        $lock = true;
        $password_err = "Try again in ten seconds.";
		die(json_encode(["message" => $password_err]));
    }
    else
    {
        $lock = false;
    }
}

if (isset($data->username) && isset($data->password) && !empty((trim($data->username))) && !empty((trim($data->password))))
{
    $usernameOrEmail = $username = trim($data->username);
    $password = trim($data->password);
}
else
{
    $password_err = "Please fill in all fields.";
	die(json_encode(["message" => $password_err]));
    
}
//Safely stores ALL login attempts (hash attempted passwords, too).
$sql = "INSERT INTO all_login_attempts(username, password, attempt_date, ip) VALUES ( ?, ?, ?, ? );";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_attempt_date, $param_ip);
    $param_username = $data->username;
    $param_password = password_hash($data->password, PASSWORD_DEFAULT);
    $param_attempt_date = $date;
    $param_ip = $_SERVER['REMOTE_ADDR'];
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
//Check if user is logging in via E-mail address
$sql = "SELECT * FROM users WHERE email = ? ;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $emailResults = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
//Get user information for 2FA
if (!empty($emailResults['username']))
{
    $usernameOrEmail = $username;
    $username = $emailResults['username'];
}
//Get user results tied both to an E-mail address or Username
$sql = "SELECT * FROM users WHERE username = ? ;";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userResults = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
//Check if user is authorized before checking for 2FA.
if (!empty($userResults["tfaen"]) && ($userResults["tfaen"] == 1 || $emailResults["tfaen"] == 1) && (password_verify($password, $userResults['password'])))
{
    $tfa_en = true;
}
// Validate credentials against database
if (empty($username_err) && empty($password_err) && !$lock)
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
                        if (isset($userResults["email_verified_at"]) || isset($emailResults["email_verified_at"]))
                        {
                            if (!$tfa_en)
                            {
                                if (isset($_SESSION))
                                {
                                    session_destroy();
                                    session_start();
                                }
                                else if (!isset($_SESSION))
                                {
                                    session_start();
                                }
                                $sql = "UPDATE users SET last_login = ? WHERE username = ? ;";
                                if ($stmt = mysqli_prepare($link, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "ss", $param_date, $param_username);
                                    $param_date = $date;
                                    $param_username = $username;
                                    mysqli_stmt_execute($stmt);
                                    mysqli_stmt_close($stmt);
                                }
                                session_regenerate_id(true);
                                $_SESSION["loggedin"] = true;
                                $_SESSION["username"] = $username;
                                $_SESSION['loginTime'] = time();
                                deleteFailedAttempts($link, $ip_address, $username);
                                die(json_encode(["message" => 1]));
                            }
                            else
                            {
                                if (isset($_SESSION))
                                {
                                    session_destroy();
                                    session_start();
                                }
                                else if (!isset($_SESSION))
                                {
                                    session_start();
                                }
                                session_regenerate_id(true);
                                $_SESSION["loggedin"] = false;
                                $_SESSION["username"] = $username;
                                $_SESSION['loginTime'] = time();
                                deleteFailedAttempts($link, $ip_address, $username);
                                die(json_encode(["message" => 2]));
                            }
                        }
                        else
                        {
                            $login_err = "Please Verify Your E-Mail.";
							die(json_encode(["message" => $login_err]));
                        }
                    }
                    else
                    {
                        $try_time = time();
                        //Brute Force Killer, the final step.
                        $total_count = getFailedAttemptsByUser($link, $ip_address, $username);
                        //On malicious attempts, ban the IP address, destroy victims password, and send victim an email.
                        $check_email_sent = getFailedAttemptsInfoByUser($link, $ip_address, $username);
                        //If account is already reset, do not add failed attempts since their password is invalid.
                        if (empty($check_email_sent['otp']))
                        {
                            $sql = "INSERT INTO failed_login_attempts (ip,attempt_time,username) VALUES( ?, ?, ? ) ;";
                            if ($stmt = mysqli_prepare($link, $sql))
                            {
                                mysqli_stmt_bind_param($stmt, "sss", $param_ip, $param_attempt_time, $param_username);
                                $param_ip = $ip_address;
                                $param_attempt_time = $try_time;
                                $param_username = $username;
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                            }
                        }
                        if ($total_count >= $second_limit)
                        {
                            if ($ip_address == $check_email_sent['ip'])
                            {
                                echo(json_encode(["message" => "hecker"]));
                            }
                            else
                            {
                                if (!isset($_SESSION))
                                {
                                    session_start();
                                }
                                $_SESSION["locked"] = true;
                                $_SESSION["username"] = $username;
                                $_SESSION["authorized"] = false;
                                $password_err = "Error 404";
                                session_regenerate_id(true);
								echo(json_encode(["message" => $password_err]));
                                
                            }
                            if (empty($check_email_sent['otp']))
                            {
                                require_once "phpmail/src/Exception.php";
                                require_once "phpmail/src/PHPMailer.php";
                                require_once "phpmail/src/SMTP.php";
                                $recovery_otp = random_str(16);
                                $resetted_password = random_str(60); //random numbers that cant be used to login ever.
                                $sql = "UPDATE failed_login_attempts SET otp = ? WHERE username = ? ;";
                                if ($stmt = mysqli_prepare($link, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "ss", $param_otp, $param_username);
                                    $param_otp = password_hash($recovery_otp, PASSWORD_DEFAULT);
                                    $param_username = $username;
                                    mysqli_stmt_execute($stmt);
                                    mysqli_stmt_close($stmt);
                                }
                                $sql = "UPDATE users SET password = ? WHERE username = ? ;";
                                if ($stmt = mysqli_prepare($link, $sql))
                                {
                                    mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                                    $param_password = $resetted_password;
                                    $param_username = $username;
                                    mysqli_stmt_execute($stmt);
                                    mysqli_stmt_close($stmt);
                                }
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
                                    $mail->addAddress($userResults["email"], $userResults["username"]);
                                    $mail->Subject = "Suspicious Account Activity";
                                    $mail->IsHTML(true);
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
                                    $html = file_get_contents('../email_templates/brute_force_attempt.html');
                                    $html = str_replace("{{USERNAME}}", $userResults["username"], $html);
                                    $html = str_replace("{{GREETING}}", $greeting, $html);
                                    $html = str_replace("{{IPADD}}", $ip_address, $html);
                                    $html = str_replace("{{OTP}}", $recovery_otp, $html);
                                    $html = str_replace("{{LOCATION}}", getGeo($ip_address) , $html);
                                    $mail->Body = $html;
                                }
                                catch(phpmailerException $e)
                                {
									die(json_encode(["message" => $e->errorMessage()]));
                                }
                                if ($mail->Send())
                                {
                                    die; 
                                }
                            }
                        }
                        else
                        {
                            $login_err = "Invalid Credentials."; //regular failed attempt less than 5 times.
                            die(json_encode(["message" => $login_err]));
                        }
                    }
                }
                else
                {
                    mysqli_stmt_close($stmt);
                    die;
                }
            }
            else
            {
                //Username doesn't exist, attempt is saved before we get here.
                $login_err = "Invalid Credentials.";
                die(json_encode(["message" => $login_err]));
            }
        }
    }
}
mysqli_close($link);
?>