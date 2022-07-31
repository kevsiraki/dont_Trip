<?php
header("Content-Type: text/html");
require_once 'redirect_backend.php';
require_once 'helpers.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once "phpmail/src/Exception.php";
require_once "phpmail/src/PHPMailer.php";
require_once "phpmail/src/SMTP.php";
$username = $password = $usernameOrEmail = "";
$username_err = $password_err = $login_err = $isAuth = "";
$tfa_en = false;
$lock = false;
$total_count = 0;
$first_limit = 5;
$second_limit = 20;
date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");
//Safely stores all page visits.
$sql = "INSERT INTO page_visits(browser, visit_date, ip) VALUES ( ?, ?, ? );";
if ($stmt = mysqli_prepare($link, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "sss", $param_browser, $param_visit_date, $param_ip);
    // Set parameters
    $param_browser = $_SERVER['HTTP_USER_AGENT'];
    $param_visit_date = $date;
    $param_ip = $_SERVER['REMOTE_ADDR'];
    // Attempt to execute the prepared statement
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
//Creates Client Request to access Google Login API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
//Authenticate code from Google OAuth Flow
if (!isset($_GET['code']))
{
    $isAuth = "yes";
}
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    require_once 'rateLimiter.php';
    //First step of brute force prevention, lock user out consecutively for 10 seconds after 5 failed attempts;
    $total_count = getFailedAttempts($link, $ip_address);
    if ($total_count >= $first_limit && $total_count < $second_limit)
    {
		$sql = "SELECT * from failed_login_attempts where ip = ? ORDER BY attempt_time DESC LIMIT 1 ;";
		if ($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_ip);
			// Set parameters
			$param_ip = $ip_address;
			// Attempt to execute the prepared statement
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
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_ip);
				// Set parameters
				$param_ip = $ip_address;
				// Attempt to execute the prepared statement
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
            $lock = false;
        }
        if ($lastAttempt['attempt_time'] + 10 > time())
        {
            $lock = true;
            $password_err = "Try again in ten seconds.";
            die($password_err);
        }
        else
        {
            $lock = false;
        }
    }
    //Safely stores ALL login attempts (hash attempted passwords, too).
    $sql = "INSERT INTO all_login_attempts(username, password, attempt_date, ip) VALUES ( ?, ?, ?, ? );";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_attempt_date, $param_ip);
        // Set parameters
        $param_username = $_POST["username"];
        $param_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $param_attempt_date = $date;
        $param_ip = $_SERVER['REMOTE_ADDR'];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    if (!empty((trim($_POST["username"]))) && !empty((trim($_POST["password"]))))
    {
        $usernameOrEmail = $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
    }
    else
    {
        $password_err = "Please fill in all fields.";
        die($password_err);
    }
    //Check if user is logging in via E-mail address
    $sql = "SELECT * FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $username;
        // Attempt to execute the prepared statement
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
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $username;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    //Check if user is authorized before sending 2FA response.
    if (!empty($userResults["tfaen"]) && ($userResults["tfaen"] == 1 || $emailResults["tfaen"] == 1) && (password_verify($password, $userResults['password'])))
    {
		$tfa_en = true;
    }
    // Validate credentials against database
    if (empty($username_err) && empty($password_err) && !$lock)
    {
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $username;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt))
            {
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1)
                {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt))
                    {
                        if (password_verify($password, $hashed_password))
                        { //Check if email is verified
                            if (isset($userResults["email_verified_at"]) || isset($emailResults["email_verified_at"]))
                            {
                                //Ensure 2FA is disabled before authorizing a session.
                                if (!$tfa_en)
                                {
                                    // Password is correct and they are verified, so start a new session
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
                                        // Bind variables to the prepared statement as parameters
                                        mysqli_stmt_bind_param($stmt, "ss", $param_date, $param_username);
                                        // Set parameters
                                        $param_date = $date;
                                        $param_username = $username;
                                        // Attempt to execute the prepared statement
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_close($stmt);
                                    }
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["username"] = $username;
									deleteFailedAttempts($link,$ip_address, $username); 
                                    // Redirect user
                                    echo 1;
                                }
                                else
                                {
                                    
                                    //2FA Enabled, don't log them in yet.
                                    if (isset($_SESSION))
                                    {
                                        session_destroy();
                                        session_start();
                                    }
                                    else if (!isset($_SESSION))
                                    {
                                        session_start();
                                    }
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = false;
                                    $_SESSION["username"] = $username;
									deleteFailedAttempts($link,$ip_address, $username); 
									//Redirect user
									echo 2;
                                }
                            }
                            else
                            {
                                //E-mail is not verified yet.
                                $login_err = "Please Verify Your E-Mail.";
                                die($login_err);
                            }
                        }
                        else
                        {
                            // Password is not valid
                            $try_time = time();
                            //Brute Force Killer, the final step.
                            $total_count = getFailedAttemptsByUser($link, $ip_address, $username); 
                            //On malicious attempts, ban the IP address, destroy victims password, and send victim an email.
                            $check_email_sent = getFailedAttemptsInfoByUser($link, $ip_address, $username);
                            //If account is already reset, do not add failed attempts since their password is invalid.
                            if (empty($check_email_sent['otp']))
                            {
                                $sql = "INSERT INTO failed_login_attempts (ip,attempt_time,username) values(?, ?, ?) ;";
                                if ($stmt = mysqli_prepare($link, $sql))
                                {
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "sss", $param_ip, $param_attempt_time, $param_username);
                                    // Set parameters
                                    $param_ip = $ip_address;
                                    $param_attempt_time = $try_time;
                                    $param_username = $username;
                                    // Attempt to execute the prepared statement
                                    mysqli_stmt_execute($stmt);
                                    mysqli_stmt_close($stmt);
                                }
                            }
                            if ($total_count >= $second_limit)
                            {
                                if ($ip_address == $check_email_sent['ip'])
                                {
                                    echo "google"; //redirect the attacker to some random place
                                }
                                else
                                {
                                    if (!isset($_SESSION))
                                    {
                                        session_start();
                                    }
                                    $_SESSION["locked"] = true;
                                    $_SESSION["username"] = $username;
                                    $password_err = "Error 404";
                                    echo $password_err;
                                }
                                if (empty($check_email_sent['otp']))
                                {
                                    $recovery_otp = random_str(16);
                                    $resetted_password = random_str(60); //random numbers that cant be used to login ever.
                                    $sql = "UPDATE failed_login_attempts SET otp = ? WHERE username = ? ;";
									if ($stmt = mysqli_prepare($link, $sql))
									{
										// Bind variables to the prepared statement as parameters
										mysqli_stmt_bind_param($stmt, "ss", $param_otp, $param_username);
										// Set parameters
										$param_otp = password_hash($recovery_otp, PASSWORD_DEFAULT);
										$param_username = $username;
										// Attempt to execute the prepared statement
										mysqli_stmt_execute($stmt);
										mysqli_stmt_close($stmt);
									}
									$sql = "UPDATE users SET password = ? WHERE username = ? ;";
									if ($stmt = mysqli_prepare($link, $sql))
									{
										// Bind variables to the prepared statement as parameters
										mysqli_stmt_bind_param($stmt, "ss", $param_otp, $param_username);
										// Set parameters
										$param_password = $resetted_password;
										$param_username = $username;
										// Attempt to execute the prepared statement
										mysqli_stmt_execute($stmt);
										mysqli_stmt_close($stmt);
									}
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
                                        die($e->errorMessage());
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
                                die($login_err);
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
                    die($login_err);
                }
            }
        }
    }
    mysqli_close($link);
}
?>