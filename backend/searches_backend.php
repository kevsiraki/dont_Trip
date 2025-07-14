<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once "config.php";
require_once "geolocation.php";
require_once 'middleware.php';

$dest = array();
$keys = array();

$sql = "SELECT DISTINCT id, destination, COUNT(destination) AS destCnt FROM searches WHERE username in (SELECT username FROM searches WHERE username = ? ) AND destination IS NOT NULL GROUP BY destination ORDER BY destCnt DESC, destination ASC;";
if ($stmt = mysqli_prepare($link, $sql))
{
	mysqli_stmt_bind_param($stmt, "s", $param_username);
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && !isset($_SESSION["userid"])) {
		$param_username = $_SESSION["username"];
	}
	else {
		$param_username = $_SESSION["userid"];
	}
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	while($row = $result->fetch_assoc()) {
		$dest[] = $row;
	}
	mysqli_stmt_close($stmt);
}
$sql = "SELECT DISTINCT id, keyword, COUNT(keyword) AS keyCnt FROM searches WHERE username in (SELECT username FROM searches WHERE username = ? ) AND keyword IS NOT NULL GROUP BY keyword ORDER BY keyCnt DESC, keyword ASC;";
if ($stmt = mysqli_prepare($link, $sql))
{
	mysqli_stmt_bind_param($stmt, "s", $param_username);
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && !isset($_SESSION["userid"])) {
		$param_username = $_SESSION["username"];
	}
	else {
		$param_username = $_SESSION["userid"];
	}
	mysqli_stmt_execute($stmt);
	$result2 = mysqli_stmt_get_result($stmt);
	while($row = $result2->fetch_assoc()) {
		$keys[] = $row;
	}
	mysqli_stmt_close($stmt);
}
die(json_encode(array_merge($dest, $keys)));
?>