<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

require_once "config.php";
require_once 'vendor/autoload.php';

$password = $password_err = "";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = $_SESSION["username"];
    // Check if password is empty
    if (empty(trim($_POST["password"])))
    {
        $password_err = "Please enter your password.";
		echo $password_err;
    }
    else
    {
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if (empty($password_err))
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
                        {
                            // Password is correct and they are verified, so delete the account
							//Delete user's search history
							$sql = "DELETE FROM searches WHERE username = ? ;";
							if ($stmt = mysqli_prepare($link, $sql))
							{
								// Bind variables to the prepared statement as parameters
								mysqli_stmt_bind_param($stmt, "s", $param_username);
								// Set parameters
								$param_username = $username;
								// Attempt to execute the prepared statement
								mysqli_stmt_execute($stmt);
								mysqli_stmt_close($stmt);
							}
							//Delete user's account
							$sql = "DELETE FROM users WHERE username = ? ;";
							if ($stmt = mysqli_prepare($link, $sql))
							{
								// Bind variables to the prepared statement as parameters
								mysqli_stmt_bind_param($stmt, "s", $param_username);
								// Set parameters
								$param_username = $username;
								// Attempt to execute the prepared statement
								mysqli_stmt_execute($stmt);
								mysqli_stmt_close($stmt);
							}
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