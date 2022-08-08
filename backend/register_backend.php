<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once "config.php";
require_once 'helpers.php';
require_once 'middleware.php';
require_once "rateLimiter.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
$row = 0;

csrf();

//Check all inputs are filled before any further checks
if (!isset ($_POST["email"]) || !isset ($_POST["username"]) || !isset ($_POST["password"]) || !isset ($_POST["confirm_password"]) || empty(trim($_POST["email"])) || empty(trim($_POST["username"])) || empty(trim($_POST["password"])) || empty(trim($_POST["confirm_password"])))
{
    die(json_encode(["message" => "Please fill in all fields."]));
}
//Validate email exists and is not taken.
if (empty(trim($_POST["email"])))
{
    $email_err = " ";
}
else
{
    $sql = "SELECT username FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = trim($_POST["email"]);
        if (mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1)
            {
                $email_err = "E-mail Unavailable.";
                die(json_encode(["message" => $email_err]));
            }
            else if (!valid_email(trim($_POST["email"])))
            {
                $email_err = "Invalid E-Mail.";
                die(json_encode(["message" => $email_err]));
            }
            else
            {
                $key = $_ENV["ip_quality_api_key"];
                $email = $_POST["email"];
                $timeout = 1;
                $fast = 'false';
                $abuse_strictness = 0;
                $parameters = array(
                    'timeout' => $timeout,
                    'fast' => $fast,
                    'abuse_strictness' => $abuse_strictness
                );
                $formatted_parameters = http_build_query($parameters);
                $url = sprintf('https://www.ipqualityscore.com/api/json/email/%s/%s?%s', $key, urlencode($email) , $formatted_parameters);
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
                $json = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($json, true);
                if (isset($result['success']) && $result['success'] === true)
                {
                    if ($result['valid'] !== true)
                    {
                        $email_err = "E-mail Address Does Not Exist.";
                        die(json_encode(["message" => $email_err]));
                    }
                    else
                    {
                        $email = trim($_POST["email"]);
                        $token = hash("SHA512", $email);
                        $addKey = hash("SHA512", generatePassword(8));
                        $token = substr(str_shuffle($token . $addKey) , 0, 32);
                    }
                }
                else
                {
                    $email = trim($_POST["email"]);
                    $token = hash("SHA512", $email);
                    $addKey = hash("SHA512", generatePassword(8));
                    $token = substr(str_shuffle($token . $addKey) , 0, 32);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}
// Validate username.
if (empty(trim($_POST["username"])))
{
    $username_err = " ";
}
else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"])) || count(array_count_values(str_split(trim($_POST["username"])))) == 1 || strlen(trim($_POST["username"])) < 8 || strlen(trim($_POST["username"])) > 25)
{
    $username_err = "Invalid Username.";
    die(json_encode(["message" => $username_err]));
}
else
{
    $sql = "SELECT email FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = trim($_POST["username"]);
        if (mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1)
            {
                $username_err = "Username taken.";
                die(json_encode(["message" => $username_err]));
            }
            else
            {
                $username = trim($_POST["username"]);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
// Validate password
if (empty(trim($_POST["password"])))
{
    $password_err = " ";
}
else if (!(preg_match('/[A-Za-z]/', trim($_POST["password"])) && preg_match('/[0-9]/', trim($_POST["password"])) && preg_match('/[A-Z]/', trim($_POST["password"])) && preg_match('/[a-z]/', trim($_POST["password"]))) || (strlen(trim($_POST["password"])) < 8 || strlen(trim($_POST["password"])) > 25))
{
    $password_err = "Weak Password.";
    die(json_encode(["message" => $password_err]));
}
else
{
    $password = trim($_POST["password"]);
}
// Validate confirm password
if (empty(trim($_POST["confirm_password"])))
{
    $confirm_password_err = " ";
}
else if (empty($password_err) && $password != trim($_POST["confirm_password"]))
{
    $confirm_password_err = "Passwords Do Not Match.";
    die(json_encode(["message" => $confirm_password_err]));
}
else
{
    $confirm_password = trim($_POST["confirm_password"]);
}
// Check input errors before inserting in database
if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err))
{
    $sql = "INSERT INTO users (username, password, email, email_verification_link) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_email, $param_token);
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        $param_email = $email;
        $param_token = $token;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    $sql = "SELECT * FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_num_rows($result);
        mysqli_stmt_close($stmt);
    }
    if ($row == 1)
    {
        require_once "phpmail/src/Exception.php";
        require_once "phpmail/src/PHPMailer.php";
        require_once "phpmail/src/SMTP.php";
        $mail = new PHPMailer(true);
        try
        {
            $mail->CharSet = "utf-8";
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['email'];
            $mail->Password = $_ENV['password'];
            $mail->SMTPSecure = $_ENV['encryption'];
            $mail->Host = $_ENV['host'];
            $mail->Port = $_ENV['port'];
            $mail->From = $_ENV['email'];
            $mail->FromName = "WebMaster";
            $mail->addAddress($email, $username);
            $mail->Subject = "Verify your E-mail";
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
            $html = file_get_contents('../email_templates/register.html');
            $html = str_replace("{{USERNAME}}", $username, $html);
            $html = str_replace("{{IMGICON}}", imageUrl() , $html);
            $html = str_replace("{{LINK}}", "https://donttrip.technologists.cloud/donttrip/client/verify-email.php?key=" . $_POST["email"] . "&token=" . $token . "", $html);
            $html = str_replace("{{GREETING}}", $greeting, $html);
            $mail->Body = $html;
        }
        catch(phpmailerException $e)
        {
            die(json_encode(["message" => $e->errorMessage()]));
        }
        catch(Exception $e)
        {
            $sql = "DELETE FROM users WHERE username = ? ;";
            if ($stmt = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $param_usernamel);
                $param_username = $username;
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
        if ($mail->Send())
        {
            if (isset($_SESSION["message_shown"]))
            {
                unset($_SESSION["message_shown"]);
            }
            die(json_encode(["message" => 1]));
        }
        else
        {
            die(json_encode(["message" => "Mail Error ->" . $mail->ErrorInfo]));
        }
    }
}
mysqli_close($link);

?>
