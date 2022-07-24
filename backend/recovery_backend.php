<?php
header("Content-Type: text/html");

require_once "config.php";
require_once 'vendor/autoload.php';

$password = $password_err = "";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	require_once 'rateLimiter.php';
	// Check if password is empty
    if (empty(trim($_POST["password"])))
    {
        $password_err = "Please enter your password.";
		echo $password_err;
		die;
    }
	else
    {
        $password = trim($_POST["password"]);
    }
	
	$sql = "SELECT * FROM failed_login_attempts WHERE username = ? ";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = trim($_POST["username"]);
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
	$username = $userResults["username"];
	
    // Validate credentials
    if (empty($password_err))
    {
        // Prepare a select statement
        $sql = "SELECT username, otp FROM failed_login_attempts WHERE username = ? and otp is not null";
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
                if (mysqli_stmt_num_rows($stmt) >= 1)
                {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt))
                    {
						
						// One Time Password is correct and they are verified
                        if (password_verify($password, $hashed_password))
                        {
							if(!isset($_SESSION)) 
							{ 
								session_start(); 
							} 
							//Delete the failed attempts on success.
							$_SESSION["loggedin"] = true;
							$_SESSION["authorized"] = false;
                            $_SESSION["username"] = $username;
							// Redirect user response
							echo 1;
                        }
						else
						{
							// Password is not valid, display a generic error message
							$password_err = "Incorrect Password.";
							echo $password_err;
						}
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($link);
?>