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

if ($link === false)
{
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
//Check if visitor is banned for bruteforcing, or if it is a recurring bruteforcer on a proxy...
if ($_SERVER["REQUEST_METHOD"] != "POST") //For GET/DELETE endpoints...
{
    //Check if banned for bruteforcing
    $total_count = getFailedAttempts($link, $ip_address);
    if ($total_count >= 20 || (checkIP() && $total_count >= 19))
    {
        header('Location: https://www.donttrip.org/donttrip/client/hecker'); //Agile sprint log greg russ page
        die("404");
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") //On POST attempts

{
    $total_count = getFailedAttempts($link, $ip_address);
    if ($total_count >= 20 ||(checkIP() && $total_count >= 19))
    {
        die("404"); //Agile sprint log greg russ page
    }
}
?>