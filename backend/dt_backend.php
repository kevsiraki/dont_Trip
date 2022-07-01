<?php
require_once "config.php";
require_once 'vendor/autoload.php';
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$gmaps_api_key = $_ENV['gmaps_api_key'];

//geolocation api
$ip = $_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$city = $details->city;
$state = state_abreviation_for(strtoupper($details->region));
$stateFull = $details->region;
//owen wilson api
$json = file_get_contents("https://owen-wilson-wow-api.herokuapp.com/wows/random?results=5");
$wilson = json_decode($json);
$audio = $wilson[rand(0,4)]->audio;

function state_abreviation_for($state) {
    // from https://gist.github.com/maxrice/2776900 and http://www.comeexplorecanada.com/abbreviations.php
    static $states = ['ALABAMA' => 'AL', 'ALASKA' => 'AK', 'ARIZONA' => 'AZ', 'ARKANSAS' => 'AR', 'CALIFORNIA' => 'CA', 'COLORADO' => 'CO', 'CONNECTICUT' => 'CT', 'DELAWARE' => 'DE', 'FLORIDA' => 'FL', 'GEORGIA' => 'GA', 'HAWAII' => 'HI', 'IDAHO' => 'ID', 'ILLINOIS' => 'IL', 'INDIANA' => 'IN', 'IOWA' => 'IA', 'KANSAS' => 'KS', 'KENTUCKY' => 'KY', 'LOUISIANA' => 'LA', 'MAINE' => 'ME', 'MARYLAND' => 'MD', 'MASSACHUSETTS' => 'MA', 'MICHIGAN' => 'MI', 'MINNESOTA' => 'MN', 'MISSISSIPPI' => 'MS', 'MISSOURI' => 'MO', 'MONTANA' => 'MT', 'NEBRASKA' => 'NE', 'NEVADA' => 'NV', 'NEW HAMPSHIRE' => 'NH', 'NEW JERSEY' => 'NJ', 'NEW MEXICO' => 'NM', 'NEW YORK' => 'NY', 'NORTH CAROLINA' => 'NC', 'NORTH DAKOTA' => 'ND', 'OHIO' => 'OH', 'OKLAHOMA' => 'OK', 'OREGON' => 'OR', 'PENNSYLVANIA' => 'PA', 'RHODE ISLAND' => 'RI', 'SOUTH CAROLINA' => 'SC', 'SOUTH DAKOTA' => 'SD', 'TENNESSEE' => 'TN', 'TEXAS' => 'TX', 'UTAH' => 'UT', 'VERMONT' => 'VT', 'VIRGINIA' => 'VA', 'WASHINGTON' => 'WA', 'WEST VIRGINIA' => 'WV', 'WISCONSIN' => 'WI', 'WYOMING' => 'WY', 'ALBERTA' => 'AB', 'BRITISH COLUMBIA' => 'BC', 'MANITOBA' => 'MB', 'NEW BRUNSWICK' => 'NB', 'NEWFOUNDLAND AND LABRADOR' => 'NL', 'NOVA SCOTIA' => 'NS', 'NORTWEST TERRITORIES' => 'NT', 'NUNAVUT' => 'NU', 'ONTARIO' => 'ON', 'PRINCE EDWARD ISLAND' => 'PE', 'QUEBEC' => 'QC', 'SASKATCHEWAN' => 'SK', 'YUKON' => 'YT', 'PUERTO RICO' => 'PR', 'VIRGIN ISLANDS' => 'VI', 'WASHINGTON DC' => 'DC'];
    // first check if input is two letters, and if so make sure that it matches one of the abbreviations, then return that
    if (strlen($state) == 2) {
        if (in_array(strtoupper($state), $states)) {
            return strtoupper($state);
        } else {
            return $state;
        }
    }
    // check for the full state name in the array
    if (array_key_exists(strtoupper($state), $states)) {
        return $states[strtoupper($state) ];
    } else {
        return $state;
    }
}

// Check map input errors before inserting in database
if (isset($_GET["go"]) && !empty($_GET["destination"])) {
    // Prepare an insert statement
    $sql = "INSERT INTO searches (username, destination, keyword)VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_destination, $param_keyword);
        // Set parameters
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            $param_username = $_SESSION["username"];
        } else {
            $param_username = "Guest IP: ".$ip;
        }
        $param_destination = trim($_GET["destination"]);
        if (!empty($_GET["keyword"])) {
            $param_keyword = trim($_GET["keyword"]);
        } else {
            $param_keyword = null;
        }
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
?>