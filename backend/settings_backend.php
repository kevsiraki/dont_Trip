<?php
require_once "config.php";
require_once 'vendor/autoload.php';
include_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
include_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
include_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
include_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$salt = $_ENV['2fa_salt'];

//geolocation api
$ip = $_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$city = $details->city;
$stateFull = $details->region;
$sql3 = "SELECT * FROM users WHERE username = '" . $_SESSION['username'] . "' ";
$result3 = mysqli_query($link, $sql3);
$basics = mysqli_fetch_assoc($result3);
if ((!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)) {
    header("location: ../login.php");
    exit;
}
if (isset($_POST['formSubmit']) && $_POST['del'] == 'Yes') {
    mysqli_query($link, "DELETE FROM searches WHERE username = '" . trim($_SESSION["username"]) . "';");
    mysqli_query($link, "DELETE FROM users WHERE username = '" . trim($_SESSION["username"]) . "';");
    header("location: ../backend/logout.php");
}
if (isset($_POST['formSubmit']) && $_POST['delS'] == 'Yes') {
    mysqli_query($link, "DELETE FROM searches WHERE username = '" . trim($_SESSION["username"]) . "';");
    header("location: ../client/settings.php");
}
?>