<?php
require_once 'helpers.php';
$ip = getIpAddr();
$details = json_decode(get_web_page("http://ip-api.com/json/{$ip}"));
$city = $details->city;
$state = $details->region;
$stateFull = $details->regionName;
$countryCode = $details->countryCode;
$lat = $details->lat;
$lon = $details->lon;
?>