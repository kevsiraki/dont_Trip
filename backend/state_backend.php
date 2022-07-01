<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location: ../login.php');
    exit;
}
require_once "config.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
$ip = $_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$city = $details->city;
$state = state_abreviation_for(strtoupper($details->region));
$stateFull = $details->region;
$result = mysqli_query($link, "SELECT DISTINCT destination FROM searches WHERE destination LIKE '%{$state}%' LIMIT 100");
$result2 = mysqli_query($link, "SELECT DISTINCT keyword FROM searches WHERE destination LIKE '%{$state}%' LIMIT 100");
function state_abreviation_for($state) {
    // from https://gist.github.com/maxrice/2776900 and http://www.comeexplorecanada.com/abbreviations.php
    static $states = ['ALABAMA' => 'AL', 'ALASKA' => 'AK', 'ARIZONA' => 'AZ', 'ARKANSAS' => 'AR', 'CALIFORNIA' => 'CA', 'COLORADO' => 'CO', 'CONNECTICUT' => 'CT', 'DELAWARE' => 'DE', 'FLORIDA' => 'FL', 'GEORGIA' => 'GA', 'HAWAII' => 'HI', 'IDAHO' => 'ID', 'ILLINOIS' => 'IL', 'INDIANA' => 'IN', 'IOWA' => 'IA', 'KANSAS' => 'KS', 'KENTUCKY' => 'KY', 'LOUISIANA' => 'LA', 'MAINE' => 'ME', 'MARYLAND' => 'MD', 'MASSACHUSETTS' => 'MA', 'MICHIGAN' => 'MI', 'MINNESOTA' => 'MN', 'MISSISSIPPI' => 'MS', 'MISSOURI' => 'MO', 'MONTANA' => 'MT', 'NEBRASKA' => 'NE', 'NEVADA' => 'NV', 'NEW HAMPSHIRE' => 'NH', 'NEW JERSEY' => 'NJ', 'NEW MEXICO' => 'NM', 'NEW YORK' => 'NY', 'NORTH CAROLINA' => 'NC', 'NORTH DAKOTA' => 'ND', 'OHIO' => 'OH', 'OKLAHOMA' => 'OK', 'OREGON' => 'OR', 'PENNSYLVANIA' => 'PA', 'RHODE ISLAND' => 'RI', 'SOUTH CAROLINA' => 'SC', 'SOUTH DAKOTA' => 'SD', 'TENNESSEE' => 'TN', 'TEXAS' => 'TX', 'UTAH' => 'UT', 'VERMONT' => 'VT', 'VIRGINIA' => 'VA', 'WASHINGTON' => 'WA', 'WEST VIRGINIA' => 'WV', 'WISCONSIN' => 'WI', 'WYOMING' => 'WY', 'ALBERTA' => 'AB', 'BRITISH COLUMBIA' => 'BC', 'MANITOBA' => 'MB', 'NEW BRUNSWICK' => 'NB', 'NEWFOUNDLAND AND LABRADOR' => 'NL', 'NOVA SCOTIA' => 'NS', 'NORTWEST TERRITORIES' => 'NT', 'NUNAVUT' => 'NU', 'ONTARIO' => 'ON', 'PRINCE EDWARD ISLAND' => 'PE', 'QUEBEC' => 'QC', 'SASKATCHEWAN' => 'SK', 'YUKON' => 'YT', 'PUERTO RICO' => 'PR', 'VIRGIN ISLANDS' => 'VI', 'WASHINGTON DC' => 'DC'];
    // first check if input is two letters, and if so make sure that it matches one of the abbreviations, then return that
    if (strlen($state) == 2) {
        if (in_array(strtoupper($state), $states)) {
            return strtoupper($state);
        } else {
            return null;
        }
    }
    // check for the full state name in the array
    if (array_key_exists(strtoupper($state), $states)) {
        return $states[strtoupper($state) ];
    } else {
        return null;
    }
}
?>