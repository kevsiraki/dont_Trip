<?php
header("Content-Type: text/html");
require_once "config.php";
include('php-csrf.php');

if(isset($_SESSION['loginTime'])&&$_SESSION['loginTime']+$_ENV["expire"] < time()) { 
	$_SESSION = array();
	// Destroy the session.
	session_destroy();
	header('location: https://donttrip.technologists.cloud/donttrip/client/session_expired.php');
	die;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET")
{
	if(isset($_SESSION['loginTime'])) {
		if($_SESSION['loginTime']+($_ENV["expire"]/3) < time()) {
			session_regenerate_id(true); 
		}
		$_SESSION['loginTime'] = time();
	}
}

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    require_once 'rateLimiter.php';
    $sql = "SELECT * FROM users WHERE username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $_SESSION["username"];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    // Validate new password
    if (empty(trim($_POST["new_password"])))
    {
        $new_password_err = "Please fill in all fields.";
        die($new_password_err);
    }
    else if (password_verify(trim($_POST["new_password"]) , trim($userResults["password"])))
    {
        $new_password_err = "Password used recently.";
        die($new_password_err);
    }
    else if (!(preg_match('/[A-Za-z]/', trim($_POST["new_password"])) && preg_match('/[0-9]/', trim($_POST["new_password"])) && preg_match('/[A-Z]/', trim($_POST["new_password"])) && preg_match('/[a-z]/', trim($_POST["new_password"]))))
    {
        $new_password_err = "Weak password.";
        die($new_password_err);
    }
    else if (strlen(trim($_POST["new_password"])) < 8 || strlen(trim($_POST["new_password"])) > 25)
    {
        $new_password_err = "Weak password.";
        die($new_password_err);
    }
    else
    {
        $new_password = trim($_POST["new_password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = "Please fill in all fields.";
        die($confirm_password_err);
    }
    else
    {
        if (empty($new_password_err) && $new_password != trim($_POST["confirm_password"]))
        {
            $confirm_password_err = "Passwords not matching.";
            die($confirm_password_err);
        }
        else if (empty($new_password_err))
        {
            $confirm_password = trim($_POST["confirm_password"]);
        }
    }
    //Check all fields
    if (empty(trim($_POST["confirm_password"])) || empty(trim($_POST["new_password"])))
    {
        $new_password_err = "Please fill in all fields.";
        die($new_password_err);
    }
    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err))
    {
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_username = $_SESSION["username"];
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt))
            {
                mysqli_query($link, "UPDATE failed_login_attempts SET username = null, otp = null WHERE username = '" . $userResults["username"] . "' ;");
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                echo 1;
                die;
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>