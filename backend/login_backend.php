<?php
/**
* Function list:
* - random_str()
* - getGeo()
*/
header("Content-Type: text/html");

require_once 'redirect_backend.php';
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

$first_limit;
$second_limit;

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
    $query = mysqli_query($link, "SELECT COUNT(*) AS total_count from failed_login_attempts where ip='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
    if ($total_count >= 5 && $total_count < 20)
    {
        $queryLastAttempt = mysqli_query($link, "SELECT * from failed_login_attempts where ip='$ip_address' ORDER BY attempt_time DESC LIMIT 1");
        $lastAttempt = mysqli_fetch_assoc($queryLastAttempt);
        $lockout = $total_count - 4;
        //undo this security check after 1 day if they don't exceed 20 attempts.
        if ($lastAttempt['attempt_time'] + (24 * 3600) < time())
        {
            mysqli_query($link, "DELETE from failed_login_attempts where ip='$ip_address' ");
            $lock = false;
            $password_err = "";
            echo $password_err;
        }
        if ($lastAttempt['attempt_time'] + 10 > time())
        {
            $lock = true;
            $password_err = "Try again in ten seconds.";
            echo $password_err;
        }
        else
        {
            $lock = false;
            $password_err = "";
            echo $password_err;
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
    if (!empty($userResults["tfaen"]))
    {
        if (($userResults["tfaen"] == 1 || $emailResults["tfaen"] == 1) && (password_verify($password, $userResults['password'])))
        {
            $tfa_en = true;
            echo 2;
        }
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
                                    mysqli_query($link, "DELETE from failed_login_attempts where ip='$ip_address'AND username='$username'"); //reset failed attempts
                                    // Redirect user
                                    echo 1;
                                }
                                else
                                {
                                    mysqli_query($link, "DELETE from failed_login_attempts where ip='$ip_address' AND username='$username'"); //reset failed attempts
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
                            $query = mysqli_query($link, "SELECT COUNT(*) AS total_count from failed_login_attempts where ip='$ip_address' or username = '" . $username . "'");
                            $check_login_row = mysqli_fetch_assoc($query);
                            $total_count = $check_login_row['total_count'];
                            //On clearly malicious attempts, ban the IP address, send the real user an email.
                            //This is in a case where the attacker actually has the victims username or email.
                            //In other cases, we respond with a nominal failed attempt message.
                            $queryEmailSent = mysqli_query($link, "SELECT * from failed_login_attempts where ip='$ip_address' or username = '" . $username . "'");
                            $check_email_sent = mysqli_fetch_assoc($queryEmailSent);
                            //if they have reset their account, dont add failed attempts since their password is invalid.
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
                            if ($total_count >= 20)
                            {
                                if ($ip_address == $check_email_sent['ip'])
                                {
                                    echo "google";
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
                                    //Also prevents the victim from getting multiple emails in case of a distributed brute force attack.
                                    mysqli_query($link, "UPDATE failed_login_attempts SET otp='" . password_hash($recovery_otp, PASSWORD_DEFAULT) . "' WHERE username='" . $userResults["username"] . "'");
                                    mysqli_query($link, "UPDATE users SET password='$resetted_password' WHERE username='" . $userResults["username"] . "'");
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
                                $login_err = "Invalid Credentials."; //regular failed attempt less than 10 times.
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
                    //Username doesn't exist, attempt is saved, but since there is no account, not counted as a brute force attempt.
                    $login_err = "Invalid Credentials.";
                    die($login_err);
                }
            }
        }
    }
    mysqli_close($link);
}
// Function to generate OTP
function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^&()'):
    string
    {
        if ($length < 1)
        {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0;$i < $length;++$i)
        {
            $pieces[] = $keyspace[random_int(0, $max) ];
        }
        return implode('', $pieces);
    }
    function getGeo($ip_address)
    {
        ini_set('allow_url_fopen', 'On');
        $details = json_decode(file_get_contents("http://ip-api.com/json/{$ip_address}"));
        $city = $details->city;
        $stateFull = $details->regionName;
        return $city . ", " . $stateFull;
    }
?>