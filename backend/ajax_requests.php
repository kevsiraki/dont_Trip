<?php
header("Content-Type: text/html");
require_once "config.php";
require_once 'helpers.php';
if (!isset($_SESSION))
{
    session_start();
}
if (isset($_SESSION['LAST_CALL']))
{
    $last = $_SESSION['LAST_CALL'];
    $curr = date("Y-m-d h:i:s.u");
    //throttle abuse of the live checking
    if (compareMilliseconds($last, $curr, 250))
    {
        die("<small><span style='color: #ff8c00;'>Verifying...</span></small>");
    }
}
$_SESSION['LAST_CALL'] = date('Y-m-d h:i:s.u');
$username = $email = "";
if (isset($_POST['username']))
{
    $username = trim($_POST['username']);
    $sql = "SELECT COUNT(*) as cntUser FROM users WHERE username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $username;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    $response = "<small><span style='color: green;'>Available.</span></small>";
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username))
        {
            $response = "<small><span style='color: red;'>Can only contain letters, numbers, and underscores.</span></small>";
        }
        else if (count(array_count_values(str_split($username))) == 1)
        {
            $response = "<small><span style='color: red;'>Cannot contain all the same character.</span></small>";
        }
        else if (strlen($username) < 8 || strlen($username) > 25)
        {
            $response = "<small><span style='color: red;'>Must contain least 8 characters and not exceed 25.</span></small>";
        }
        else if ($count > 0)
        {
            $response = "<small><span style='color: red;'>Not Available.</span></small>";
        }
    }
    die($response);
}
if (isset($_POST['email']))
{
    $email = trim($_POST['email']);
    $sql = "SELECT COUNT(*) as cntEmail FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = $email;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    $response = "<small><span style='color: green;'>Available.</span></small>";
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntEmail'];
        if (!valid_email($email))
        {
            $response = "<small><span style='color: red;'>E-mail Address Invalid.</span></small>";
        }
        else if ($count > 0)
        {
            $response = "<small><span style='color: red;'>Not Available.</span></small>";
        }
    }
	die($response);
}
if (isset($_POST['email_reset']))
{
    $email = trim($_POST['email_reset']);
    $sql = "SELECT COUNT(*) as cntEmail FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = $email;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    $response = "<small><span style='color: red;'>Not Found.</span></small>";
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntEmail'];
        if (!valid_email($email))
        {
            $response = "<small><span style='color: red;'>E-mail Address Invalid.</span></small>";
        }
        else if ($count > 0)
        {
            $response = "<small><span style='color: green;'>Found.</span></small>";
        }
    }
    $sql = "SELECT COUNT(*) as cntEmail FROM password_reset_temp WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        // Set parameters
        $param_email = $email;
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntEmail'];
        if ($count >= 5)
        {
            $response = "<small><span style='color: red;'>Too Many Requests.</span></small>";
        }
    }
    die($response);
}
?>