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
$username_err = $password_err = $login_err = $tfa_err = $isAuth = "";
$showTFA = false;

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
    if (isset($_POST["Submit"]))
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
    if (empty(trim($_POST["username"])) && isset($_POST["Submit"]))
    {
        $username_err = "Please enter your username.";
    }
    else
    {
        $usernameOrEmail = $username = trim($_POST["username"]);
    }
    // Check if password is empty
    if (empty(trim($_POST["password"])) && isset($_POST["Submit"]))
    {
        $password_err = "Please enter your password.";
    }
    else
    {
        $password = trim($_POST["password"]);
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
	//Generate 2FA input/secret
    if ($userResults["tfaen"] == 1 || $emailResults["tfaen"] == 1)
    {
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $secret = $userResults["tfa"];
        $code = trim($_POST["2fa"]);
        if ($g->checkCode($secret, $code) && isset($_POST["Submit"]))
        {
        }
        else if (!($g->checkCode($secret, $code)) && isset($_POST["Submit"]))
        {
            if (empty($code) && isset($_POST["Submit"]))
            {
                $tfa_err = " ";
            }
            else
            {
                $tfa_err = "Incorrect/Expired.";
            }
        }
    }
	//Check if credentials changed after showing 2FA input.
    if (($userResults["tfaen"] == 1 || $emailResults["tfaen"] == 1) 
		&& (password_verify($password, $emailResults['password']) 
		|| password_verify($password, $userResults['password'])))
    {
        $showTFA = true;
    }
    else
    {
        if (empty($username_err) && empty($password_err))
        {
            $login_err = "Invalid Credentials.";
        }
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err) && empty($tfa_err) && isset($_POST["Submit"]))
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
                //Check if 2FA authentication is enabled or code is authorized
                if (($userResults["tfaen"] == 0 || $emailResults["tfaen"] == 0 || empty($tfa_err)))
                {
                    // Check if username exists, if yes then verify password
                    if (mysqli_stmt_num_rows($stmt) == 1)
                    {
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                        if (mysqli_stmt_fetch($stmt) && isset($_POST["Submit"]))
                        {
                            if (password_verify($password, $hashed_password))
                            { 	//Check if email is verified
                                if (isset($userResults["email_verified_at"]) || isset($emailResults["email_verified_at"]))
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
                                    header("location: ./client/dt.php");
                                }
                                else
                                {
                                    $login_err = "Please Verify Your E-Mail.";
                                }
                            }
                            else
                            {
                                // Password is not valid, display a generic error message
                                $login_err = "Invalid Credentials.";
                            }
                        }
                    }
                    else
                    {
                        // Username doesn't exist, display a generic error message
                        $login_err = "Invalid Credentials.";
                    }
                }
            }
            else
            {
                echo "Database Issue.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>