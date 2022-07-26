<?php
header("Content-Type: text/html");
// Include config file
require_once "config.php";
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';

if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
if(!empty($_SESSION["authorized"])&&$_SESSION["authorized"] === false) {
	header("location: ../login.php");
    exit;
}

date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");

$email = $new_password = $confirm_password = $code = $tfa_err = "";
$new_password_err = $confirm_password_err = "";

$expired = 0;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

if (isset($_GET["key"]) && isset($_GET["token"]))
{
    $sql = "SELECT * FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = $_GET["key"];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    $email = trim($_GET["key"]);
    $key = $_GET["token"];
    $curDate = $date;
    $sql = "SELECT * FROM password_reset_temp WHERE keyTO = ? AND email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_key, $param_email);
        // Set parameters
        $param_key = $key;
        $param_email = $email;
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt))
        {
            $result = mysqli_stmt_get_result($stmt);
            $array = mysqli_fetch_assoc($result);
            $row = mysqli_num_rows($result);
            if ($row == "")
            {
                $expired = 1;
            }
            else
            {
                $row = $array;
                $expDate = $row['expD'];
                if ($expDate < $curDate)
                {
                    mysqli_query($link, "DELETE FROM password_reset_temp WHERE email='" . $email . "';");
                    $expired = 1;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}
else
{
    $expired = 1;
}
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	require_once 'rateLimiter.php';
    $email = $_POST["email"];
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
    $key = $_POST["key"];
    $curDate = $date;
    $sql = "SELECT * FROM password_reset_temp WHERE keyTO = ? AND email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_key, $param_email);
        // Set parameters
        $param_key = $key;
        $param_email = $email;
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt))
        {
            $result = mysqli_stmt_get_result($stmt);
            $array = mysqli_fetch_assoc($result);
            $row = mysqli_num_rows($result);
            if ($row == "")
            {
                $expired = 1;
            }
            else
            {
                $row = $array;
                $expDate = $row['expD'];
                if ($expDate < $curDate)
                {
                    mysqli_query($link, "DELETE FROM password_reset_temp WHERE email='" . $email . "';");
                    $expired = 1;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"])))
    {
        $new_password_err = " ";
    }
    else if (password_verify(trim($_POST["new_password"]) , trim($userResults['password'])))
    {
        $new_password_err = 'Password used recently.';
        echo $new_password_err;
		die;
    }
    else if (!(preg_match('/[A-Za-z]/', trim($_POST["new_password"])) && preg_match('/[0-9]/', trim($_POST["new_password"])) && preg_match('/[A-Z]/', trim($_POST["new_password"])) && preg_match('/[a-z]/', trim($_POST["new_password"]))))
    {
        $new_password_err = " ";
    }
    else if (strlen(trim($_POST["new_password"])) < 8 || strlen(trim($_POST["new_password"])) > 25)
    {
        $new_password_err = " ";
    }
    else
    {
        $new_password = trim($_POST["new_password"]);
    }
    // Validate confirm new password
    if (empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = " ";
    }
    else
    {
        if (empty($new_password_err) && $new_password != trim($_POST["confirm_password"]))
        {
            $confirm_password_err = " ";
        }
        else if (empty($new_password_err))
        {
            $confirm_password = trim($_POST["confirm_password"]);
        }
    }
    if ($userResults["tfaen"] == 1)
    {
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $secret = decrypt($userResults["tfa"]);
        $code = trim($_POST["tfa"]);
        if ($g->checkCode($secret, $code))
        {
        }
        else if (!($g->checkCode($secret, $code)))
        {
            if (!empty(trim($_POST["new_password"])) && !empty(trim($_POST["confirm_password"])))
            {
                if (empty($code))
                {
                    $tfa_err = "Please enter OTP.";
                    echo $tfa_err;
					die;
                }
                else
                {
                    $tfa_err = "Incorrect or Expired OTP.";
                    echo $tfa_err;
					die;
                }
            }
        }
    }
    //Check all fields
    if (empty(trim($_POST["new_password"])) || empty(trim($_POST["confirm_password"])))
    {
        echo 'Please fill in all fields.';
		die;
    }
    // Check input errors before updating the database
    if (empty($new_password_err) && empty($tfa_err) && empty($confirm_password_err))
    {
        $sql = "UPDATE users SET email_verified_at = ? WHERE email = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_date, $param_email);
            // Set parameters
            $param_date = $date;
            $param_email = $email;
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql))
        {
			if(isset($_SESSION)) 
			{ 
				session_destroy();										
			}
			if(isset($_SESSION["message_shown"]))
			{ 
				unset($_SESSION["message_shown"]);				 										
			}
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_email);
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_email = $email;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt))
            {
                // Password updated successfully. Redirect to login page
                $sql = "DELETE FROM password_reset_temp WHERE email = ?";
                if ($stmt2 = mysqli_prepare($link, $sql))
                {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt2, "s", $param_email);
                    // Set parameters
                    $param_email = $email;
                    // Attempt to execute the prepared statement
                    mysqli_stmt_execute($stmt2);
                    // Close statement
                    mysqli_stmt_close($stmt2);
                }
                echo 1;
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
function decrypt($data) {
    $key = key;
    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length($cipher = encryption_method);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac))
    {
        return $original_plaintext;
    }
}
?>