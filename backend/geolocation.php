<?php
ini_set('allow_url_fopen', 'On');
require_once 'helpers.php';
$ip = $_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
$city = $details->city;
$state = $details->region;
$stateFull = $details->regionName;
$countryCode = $details->countryCode;
$lat = $details->lat;
$lon = $details->lon;
?>