<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "config.php";
require_once 'helpers.php';

if (!isset($_SESSION))
{
    session_start();
}
//throttle live checking
if (isset($_SESSION['LAST_CALL']))
{
    $last = $_SESSION['LAST_CALL'];
    $curr = date("Y-m-d h:i:s.u");
    if (compareMilliseconds($last, $curr, 250))
    {
        die(json_encode(["check" => "Verifying..."]));
    }
}
$_SESSION['LAST_CALL'] = date('Y-m-d h:i:s.u');

$username = $email = "";
$response = array();

if (isset($_POST['username']))
{
    $username = trim($_POST['username']);
    $sql = "SELECT COUNT(*) as cntUser FROM users WHERE username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
	$response = array("success" => "Available");
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username))
        {
			$response = array("error" => "Can only contain letters, numbers, and underscores");
        }
		else if (strlen($username) < 8 || strlen($username) > 25)
        {
			$response = array("error" => "Must contain least 8 characters and not exceed 25");
        }
        else if (count(array_count_values(str_split($username))) == 1)
        {
			$response = array("error" => "Cannot contain all the same character");
        }
        else if ($count > 0)
        {
            $response = array("error" => "Not Available");
        }
    }
    die(json_encode($response));
}
if (isset($_POST['email']))
{
    $email = trim($_POST['email']);
    $sql = "SELECT COUNT(*) as cntEmail FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    $response = array("success" => "Available");
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntEmail'];
        if (!valid_email($email))
        {
			$response = array("error" => "E-mail Address Invalid");
        }
        else if ($count > 0)
        {
			$response = array("error" => "Not Available");
        }
    }
	die(json_encode($response));
}
if (isset($_POST['email_reset']))
{
    $email = trim($_POST['email_reset']);
    $sql = "SELECT COUNT(*) as cntEmail FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
    $response = array("error" => "Not Found");
    if (mysqli_num_rows($result))
    {
        $row = mysqli_fetch_array($result);
        $count = $row['cntEmail'];
        if (!valid_email($email))
        {
			$response = array("error" => "E-mail Address Invalid");
        }
        else if ($count > 0)
        {
            $response = array("success" => "Found");
        }
    }
    $sql = "SELECT COUNT(*) as cntEmail FROM password_reset_temp WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;
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
			$response = array("error" => "Too Many Requests");
        }
    }
	die(json_encode($response));
}
?>