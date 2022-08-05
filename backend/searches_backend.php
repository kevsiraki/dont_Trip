<?php
header("Content-Type: text/html");

require_once "config.php";
require_once "geolocation.php";
require_once 'middleware.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
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
}
?>