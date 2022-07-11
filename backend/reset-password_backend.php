<?php
// Initialize the session
session_start();
// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } else if (password_verify(trim($_POST["new_password"]), trim($userResults["password"]))) {
        $new_password_err = 'New password cannot be the same as before.';
    } else if (!(preg_match('/[A-Za-z]/', trim($_POST["new_password"])) && preg_match('/[0-9]/', trim($_POST["new_password"])) && preg_match('/[A-Z]/', trim($_POST["new_password"])) && preg_match('/[a-z]/', trim($_POST["new_password"])))) {
        $new_password_err = 'New password must contain a lowercase letter, uppercase letter, and a number.';
    } else if (strlen(trim($_POST["new_password"])) < 8 || strlen(trim($_POST["new_password"])) > 25) {
        $new_password_err = "New password must have atleast 8 characters and not exceed 25.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    // Validate confirm password
	if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        if (empty($new_password_err) && $new_password != trim($_POST["confirm_password"])) {
            $confirm_password_err = "Password did not match.";
        }
		else if (empty($new_password_err)) {
			$confirm_password = trim($_POST["confirm_password"]);
		}
    }
    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_username = $_SESSION["username"];
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: ../login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
?>