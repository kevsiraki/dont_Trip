<?php
require_once "config.php";
require_once "geolocation.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
$sql = "SELECT DISTINCT destination FROM searches WHERE username in (SELECT username FROM searches WHERE username = ? ) ;";
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
$sql = "SELECT DISTINCT keyword FROM searches WHERE username IN (SELECT username FROM searches WHERE username = ? ) ;";
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
if(isset($_GET['toDelete'])) 
{
	$sql = "UPDATE searches SET destination = null WHERE destination = ? ;";
	if ($stmt = mysqli_prepare($link, $sql))
	{
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $param_destination);
		// Set parameters
		$param_destination = $_GET['toDelete'];
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	header("location: searches.php");
}
if(isset($_GET['toDelete2'])) 
{
	$sql = "UPDATE searches SET keyword = null WHERE keyword = ? ;";
	if ($stmt = mysqli_prepare($link, $sql))
	{
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $param_keyword);
		// Set parameters
		$param_keyword = $_GET['toDelete2'];
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	header("location: searches.php");
}
?>