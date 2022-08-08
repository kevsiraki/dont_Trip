<?php
require_once 'helpers.php';
require_once 'middleware.php';

startSession();

//Limit API calls to once per session.
if(!isset($_SESSION['geolocated'])||$_SESSION['geolocated']!==true) 
{
	$ip = getIpAddr();
	$details = json_decode(get_web_page("http://ip-api.com/json/{$ip}"));
	$_SESSION['city']  = $details->city;
	$_SESSION['state'] = $details->region;
	$_SESSION['stateFull'] = $details->regionName;
	$_SESSION['countryCode'] = $details->countryCode;
	$_SESSION['lat'] = $details->lat;
	$_SESSION['lon'] = $details->lon;
	$_SESSION['geolocated'] = true;
}
$city = $_SESSION['city'];
$state = $_SESSION['state'];
$stateFull= $_SESSION['stateFull'];
$countryCode = $_SESSION['countryCode'];
$lat= $_SESSION['lat'];
$lon = $_SESSION['lon'];
?>