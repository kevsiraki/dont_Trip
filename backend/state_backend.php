<?php
header("Content-Type: text/html");

require_once "config.php";
require_once "geolocation.php";
require_once 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
	$sql = "SELECT DISTINCT destination, COUNT(destination) AS destCnt FROM searches WHERE (destination LIKE BINARY ? OR destination LIKE BINARY ? ) AND destination IS NOT NULL GROUP BY destination ORDER BY destCnt DESC LIMIT 100;";
	if ($stmt = mysqli_prepare($link, $sql))
	{
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "ss", $param_state, $param_stateFull);
		// Set parameters
		$param_state = "%$state%";
		$param_stateFull = "%$stateFull%";
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		mysqli_stmt_close($stmt);
	}
	$sql = "SELECT DISTINCT keyword, COUNT(keyword) AS keyCnt FROM searches WHERE (destination LIKE BINARY ? OR destination LIKE BINARY ? ) AND keyword IS NOT NULL GROUP BY keyword ORDER BY keyCnt DESC LIMIT 100;";
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
}
?>