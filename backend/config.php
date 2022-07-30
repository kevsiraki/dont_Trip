<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'helpers.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
define('DB_SERVER', $_ENV['db_server']);
define('DB_USERNAME', $_ENV['db_user']);
define('DB_PASSWORD', $_ENV['db_pass']);
define('DB_NAME', $_ENV['db_name']);
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$ip_address = getIpAddr();
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if ($_SERVER["REQUEST_METHOD"] != "POST")
{
	//check for proxies
	if(checkIP()) {
		header('Location: https://donttrip.technologists.cloud/donttrip/client/hecker');
	}
    //Check if banned for bruteforcing
    $query = mysqli_query($link, "SELECT COUNT(*) AS total_count from failed_login_attempts where ip='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
    if ($total_count >= 20)
    {
        header('Location: https://donttrip.technologists.cloud/donttrip/client/hecker'); //Agile sprint log greg russ page
		die("404");
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//check for proxies
	if(checkIP()) 
	{
		die("Turn off your proxy. 404");
	}
    //Check if banned for bruteforcing
    $query = mysqli_query($link, "SELECT COUNT(*) AS total_count from failed_login_attempts where ip='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
    if ($total_count >= 20)
    {
		die("404");
    }
}
?>