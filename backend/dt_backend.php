<?php
require_once "config.php";
require_once "geolocation.php";
require_once 'vendor/autoload.php';
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$gmaps_api_key = $_ENV['gmaps_api_key'];
//owen wilson api
$json = file_get_contents("https://owen-wilson-wow-api.herokuapp.com/wows/random?results=5");
$wilson = json_decode($json);
$audio = $wilson[rand(0,4)]->audio;
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