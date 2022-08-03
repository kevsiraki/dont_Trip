<?php
header("Content-Type: text/html");
require_once "config.php";
require_once "geolocation.php";
if (!isset($_SESSION))
{
    session_start();
}
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: ../login.php");
    die;
}
if (isset($_SESSION["authorized"]) && $_SESSION["authorized"] === false)
{
    header("location: ../backend/logout.php");
    die;
}
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

$sql = "SELECT DISTINCT destination, COUNT(destination) AS destCnt FROM searches WHERE username in (SELECT username FROM searches WHERE username = ? ) AND destination IS NOT NULL GROUP BY destination ORDER BY destCnt DESC;";
if ($stmt = mysqli_prepare($link, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    // Set parameters
    $param_username = $_SESSION["username"];
    // Attempt to execute the prepared statement
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}
$sql = "SELECT DISTINCT keyword, COUNT(keyword) AS keyCnt FROM searches WHERE username in (SELECT username FROM searches WHERE username = ? ) AND keyword IS NOT NULL GROUP BY keyword ORDER BY keyCnt DESC";
if ($stmt = mysqli_prepare($link, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    // Set parameters
    $param_username = $_SESSION["username"];
    // Attempt to execute the prepared statement
    mysqli_stmt_execute($stmt);
    $result2 = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}
if (isset($_GET['toDeleteDestination']))
{
    $sql = "UPDATE searches SET destination = null WHERE destination = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_destination);
        // Set parameters
        $param_destination = $_GET['toDeleteDestination'];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    echo 1;
}
if (isset($_GET['toDeleteKeyword']))
{
    $sql = "UPDATE searches SET keyword = null WHERE keyword = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_keyword);
        // Set parameters
        $param_keyword = $_GET['toDeleteKeyword'];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    echo 2;
}
?>