<?php
// Initialize the session
session_start();
require_once "config.php";
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
require_once 'vendor/autoload.php';
require_once 'redirect_backend.php';

date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");
$username = $password = $usernameOrEmail = "";
$username_err = $password_err = $login_err = $isAuth = "";
$tfa_en = false;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
    //Safely stores all login attempts.
    if (isset($_POST["username"]) || isset($_POST["password"]))
    {
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
        }
        mysqli_stmt_close($stmt);
    }
    // Check if username is empty
    if (empty(trim($_POST["username"])))
    {
        $username_err = " ";
    }
    else
    {
        $usernameOrEmail = $username = trim($_POST["username"]);
    }
    // Check if password is empty
    if (empty(trim($_POST["password"])))
    {
        $password_err = " ";
    }
    else
    {
        $password = trim($_POST["password"]);
    }
    if (empty(trim($_POST["username"])) || empty(trim($_POST["password"])))
    {
        $login_err = "Enter username and password.";
        echo $login_err;
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
    
    //Check if credentials changed after showing 2FA input.
    if (($userResults["tfaen"] == 1 || $emailResults["tfaen"] == 1) && (password_verify($password, $emailResults['password']) || password_verify($password, $userResults['password'])))
    {
        $tfa_en = true;
        echo 2;
    }
    //echo $userResults["tfaen"];
    // Validate credentials
    if (empty($username_err) && empty($password_err))
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
                                    session_start();
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
                                    // Redirect user
                                    echo 1;
                                }
                                else
                                {
                                    session_start();
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = false;
                                    $_SESSION["username"] = $username;
                                }
                            }
                            else
                            {
                                $login_err = "Please Verify Your E-Mail.";
                                echo $login_err;
                            }
                        }
                        else
                        {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid Credentials.";
                            echo $login_err;
                        }
                    }
                }
                else
                {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid Credentials.";
                    echo $login_err;
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>
