<?php
// KS 6/10/2025 - For localhost IP issue:
//error_reporting(E_ERROR | E_PARSE);

require_once 'helpers.php';
require_once 'middleware.php';

startSession();

//Limit API calls to once per session.
// KS 6/10/2025: Limit API calls to once per session
if (!isset($_SESSION['geolocated']) || $_SESSION['geolocated'] !== true)
{
    $ip = getIpAddr(); // KS 6/10/2025
    $details = json_decode(get_web_page("http://ip-api.com/json/{$ip}")); // KS 6/10/2025

    // KS 6/10/2025: Use default location if API fails
    $defaults = [
        'city' => 'Los Angeles',
        'region' => 'CA',
        'regionName' => 'California',
        'countryCode' => 'US',
        'lat' => 34.0522,
        'lon' => -118.2437
    ]; // KS 6/10/2025

    if ($details && isset($details->status) && $details->status === 'success') {
        $_SESSION['city'] = $details->city ?? $defaults['city']; // KS 6/10/2025
        $_SESSION['state'] = $details->region ?? $defaults['region']; // KS 6/10/2025
        $_SESSION['stateFull'] = $details->regionName ?? $defaults['regionName']; // KS 6/10/2025
        $_SESSION['countryCode'] = $details->countryCode ?? $defaults['countryCode']; // KS 6/10/2025
        $_SESSION['lat'] = $details->lat ?? $defaults['lat']; // KS 6/10/2025
        $_SESSION['lon'] = $details->lon ?? $defaults['lon']; // KS 6/10/2025
    } else {
        // KS 6/10/2025: Set defaults if API fails
        $_SESSION['city'] = $defaults['city']; // KS 6/10/2025
        $_SESSION['state'] = $defaults['region']; // KS 6/10/2025
        $_SESSION['stateFull'] = $defaults['regionName']; // KS 6/10/2025
        $_SESSION['countryCode'] = $defaults['countryCode']; // KS 6/10/2025
        $_SESSION['lat'] = $defaults['lat']; // KS 6/10/2025
        $_SESSION['lon'] = $defaults['lon']; // KS 6/10/2025
    }

    $_SESSION['geolocated'] = true; // KS 6/10/2025
}

// KS 6/10/2025: Assign session values
$city = $_SESSION['city']; // KS 6/10/2025
$state = $_SESSION['state']; // KS 6/10/2025
$stateFull = $_SESSION['stateFull']; // KS 6/10/2025
$countryCode = $_SESSION['countryCode']; // KS 6/10/2025
$lat = $_SESSION['lat']; // KS 6/10/2025
$lon = $_SESSION['lon']; // KS 6/10/2025
?>