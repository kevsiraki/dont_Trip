<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
function checkIP() {
	//check for proxies
	$key = $_ENV["ip_quality_api_key"];
	$ip = getIpAddr();
	if($ip != $_ENV["myIP"]&&$ip != $_ENV["myPhoneIP"]) {
		$user_agent = $_SERVER['HTTP_USER_AGENT']; 
		$user_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$strictness = 1;
		$allow_public_access_points = 'true';
		$lighter_penalties = 'false';
		$parameters = array(
			'user_agent' => $user_agent,
			'user_language' => $user_language,
			'strictness' => $strictness,
			'allow_public_access_points' => $allow_public_access_points,
			'lighter_penalties' => $lighter_penalties
		);
		$formatted_parameters = http_build_query($parameters);
		$url = sprintf(
			'https://www.ipqualityscore.com/api/json/ip/%s/%s?%s', 
			$key,
			$ip, 
			$formatted_parameters
		);
		$timeout = 5;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		$json = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($json, true);
		if($result['proxy'] === true && $result['is_crawler'] === false){
			return true;
		}
	}
	return false;
}
?>