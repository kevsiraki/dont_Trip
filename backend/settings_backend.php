<?php
require_once "config.php";
require_once "geolocation.php";
require_once 'vendor/autoload.php';
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$salt = $_ENV['2fa_salt'];
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