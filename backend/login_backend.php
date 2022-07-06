<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
/*
if (isset($_COOKIE["idrm_tkn"])) 
{
	$rem = "SELECT * FROM users WHERE remember = '" . $_COOKIE['idrm_tkn'] . "' ";
	$remRes = mysqli_query($link, $rem);
	$remArr = mysqli_fetch_assoc($remRes);
	$_SESSION["loggedin"] = true;
	$_SESSION["username"] = $remArr['username'];
	header("location: ./client/dt.php");
	exit();
}
*/
require_once "config.php";
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
require_once 'vendor/autoload.php';
require_once 'redirect_backend.php';

date_default_timezone_set('America/Los_Angeles');
$username = $password = $usernameO = "";
$username_err = $password_err = $login_err = $tfa_err = $isAuth = "";
$showTFA = false;
$date = date("Y-m-d H:i:s");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//safely stores all page visits.
mysqli_query($link, " INSERT INTO login_attempts(username, password, attempt_date, ip) 
VALUES ('" . "visitor" . "' , '" . $_SERVER['HTTP_USER_AGENT'] . "', '" . $date . "', '" . $_SERVER['REMOTE_ADDR'] . "' );");

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
// authenticate code from Google OAuth Flow
if (!isset($_GET['code'])) {
	$isAuth = "yes";
} 

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //safely stores all login attempts.
    if (isset($_POST["Submit"])) {
        mysqli_query($link, " INSERT INTO all_login_attempts(username, password, attempt_date, ip) 
		VALUES ('" . trim(str_replace(' ', '', $_POST["username"])) . "' , '" . password_hash(trim($_POST["password"]), PASSWORD_DEFAULT) . "', '" . $date . "', '" . $_SERVER['REMOTE_ADDR'] . "' );");
    }
    $sql3 = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "' ";
    $result3 = mysqli_query($link, $sql3);
    $basics = mysqli_fetch_assoc($result3);
    // Check if username is empty
    if (empty(trim($_POST["username"])) && isset($_POST["Submit"])) {
        $username_err = "Please enter username.";
    } else {
        $usernameO = $username = trim($_POST["username"]);
    }
    // Check if password is empty
    if (empty(trim($_POST["password"])) && isset($_POST["Submit"])) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    $sql4 = "SELECT * FROM users WHERE email = '" . $username . "' ";
    $result4 = mysqli_query($link, $sql4);
    $basics4 = mysqli_fetch_assoc($result4);
    if (!empty($basics4['username'])) {
        $usernameO = $username;
        $username = trim($basics4['username']);
    }
    $sql3 = "SELECT * FROM users WHERE username = '" . $username . "' ";
    $result3 = mysqli_query($link, $sql3);
    $basics = mysqli_fetch_assoc($result3);
    if ($basics["tfaen"] == 1 || $basics4["tfaen"] == 1) {
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $secret = $basics["tfa"];
        $code = trim($_POST["2fa"]);
        if ($g->checkCode($secret, $code) && isset($_POST["Submit"])) {
        } else if (!($g->checkCode($secret, $code)) && isset($_POST["Submit"])) {
            if (empty($code) && isset($_POST["Submit"])) {
                $tfa_err = " ";
            } else {
                $tfa_err = "Incorrect/Exipired.";
            }
        }
    } else {}
	
	if(($basics["tfaen"]==1 || $basics4["tfaen"] == 1)&&(password_verify($password, $basics4['password'])||password_verify($password, $basics['password']))) {
		$showTFA = true;
	} else {
		$login_err = "Invalid Credentials.";
	}
	
    // Validate credentials
    if (empty($username_err) && empty($password_err) && empty($tfa_err) && isset($_POST["Submit"])) {
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $username;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                //check for 2FA authentication
                if (($basics["tfaen"] == 0 || $basics4["tfaen"] == 0 || empty($tfa_err))) {
                    // Check if username exists, if yes then verify password
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                        if (mysqli_stmt_fetch($stmt) && isset($_POST["Submit"])) {
                            if (password_verify($password, $hashed_password)) { //email is not verified?
                                if(isset($basics["email_verified_at"]) || isset($basics4["email_verified_at"])) {
									if (isset($_POST['remember']) && $_POST['remember'] == 'Yes') {
										/*
										$hashy = bin2hex(random_bytes(32));
										$r = "SELECT * FROM users WHERE username = '" . $username . "' ";
										$rR = mysqli_query($link, $r);
										$rA = mysqli_fetch_assoc($rR);
										if(!empty(rA["remember"])) {
											setcookie("idrm_tkn", rA["remember"], time() + (86400 * 30), "/donttrip/"); // 86400 = 1 day
										}
										else {
											mysqli_query($link, "UPDATE users SET remember='".$hashy."' WHERE username = '".$username."';");
											setcookie("idrm_tkn", $hashy, time() + (86400 * 30), "/donttrip/"); // 86400 = 1 day
										}
										*/
									}
									// Password is correct and they are verified, so start a new session
									session_start();
									mysqli_query($link, "UPDATE users SET created_at='".$date."' WHERE username = '".$username."';");
									// Store data in session variables
									$_SESSION["loggedin"] = true;
									$_SESSION["username"] = $username;
									// Redirect user
									header("location: ./client/dt.php");
								} else {
									$login_err = "Please Verify Your E-Mail.";
								}
                            } else {
                                // Password is not valid, display a generic error message
                                $login_err = "Invalid Credentials.";
                            }
                        }
                    } else {
                        // Username doesn't exist, display a generic error message
                        $login_err = "Invalid Credentials.";
                    }
                }
            } else {
                echo "Oops!";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>