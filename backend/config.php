<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('DB_SERVER', $_ENV['db_server']);
define('DB_USERNAME', $_ENV['db_user']);
define('DB_PASSWORD', $_ENV['db_pass']);
define('DB_NAME', $_ENV['db_name']);
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$ip_address = getIpAddr();
if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    //Brute Force Killer
    $query = mysqli_query($link, "SELECT COUNT(*) AS total_count from failed_login_attempts where ip='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
    if ($total_count >= 20)
    {
        header('Location: https://donttrip.technologists.cloud/donttrip/client/heckerman');
		//die;
    }
}
function getIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ipAddr = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddr;
}
?>