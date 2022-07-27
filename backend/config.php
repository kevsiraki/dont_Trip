<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once 'vendor/autoload.php';

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
    //Brute Force Killer
    $query = mysqli_query($link, "SELECT COUNT(*) AS total_count from failed_login_attempts where ip='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
	$response = get_web_page("https://check.getipintel.net/check.php?ip=".$ip_address."&contact=".$_ENV["email"]."&format=json&flags=m");
	$resArr = array();
	$resArr = json_decode($response);
	//echo $resArr;
    if ($total_count >= 20||$resArr->result!=0)
    {
        header('Location: https://donttrip.technologists.cloud/donttrip/client/hecker'); //Agile sprint log greg russ page
		die("404");
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
function get_web_page($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "test", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 500,    // time-out on connect
        CURLOPT_TIMEOUT        => 500,    // time-out on response
    ); 

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);

    curl_close($ch);

    return $content;
}

?>