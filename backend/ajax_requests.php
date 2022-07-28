<?php
header("Content-Type: text/html");

include "config.php";

include "rateLimiter.php";

$username = $email = "";

function valid_email($email)
{
    if (is_array($email) || is_numeric($email) || is_bool($email) || is_float($email) || is_file($email) || is_dir($email) || is_int($email)) return false;
    else
    {
        $email = trim(strtolower($email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) return $email;
        else
        {
            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
            return (preg_match($pattern, $email) === 1) ? $email : false;
        }
    }
}
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
