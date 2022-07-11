<?php
require_once "config.php";
require_once "geolocation.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
$sql = "SELECT DISTINCT destination FROM searches WHERE destination LIKE BINARY ? OR destination LIKE BINARY ? LIMIT 100;";
if ($stmt = mysqli_prepare($link, $sql))
{
	// Bind variables to the prepared statement as parameters
	mysqli_stmt_bind_param($stmt, "ss", $param_state, $param_stateFull);
	// Set parameters
	$param_state = "%$state%";
	$param_stateFull = "%$$stateFull%";
	// Attempt to execute the prepared statement
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
}
$sql = "SELECT DISTINCT keyword FROM searches WHERE destination LIKE BINARY ? OR destination LIKE BINARY ? LIMIT 100;";
if ($stmt = mysqli_prepare($link, $sql))
{
	// Bind variables to the prepared statement as parameters
	mysqli_stmt_bind_param($stmt, "ss", $param_state, $param_stateFull);
	// Set parameters
	$param_state = "%$state%";
	$param_stateFull = "%$stateFull%";
	// Attempt to execute the prepared statement
	mysqli_stmt_execute($stmt);
	$result2 = mysqli_stmt_get_result($stmt);
	mysqli_stmt_close($stmt);
}
?>