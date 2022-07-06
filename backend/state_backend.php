<?php
require_once "config.php";
require_once "geolocation.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
$result = mysqli_query($link, "SELECT DISTINCT destination FROM searches WHERE destination LIKE BINARY '%{$state}%' OR destination LIKE BINARY '%{$details->region}%' LIMIT 100");
$result2 = mysqli_query($link, "SELECT DISTINCT keyword FROM searches WHERE destination LIKE BINARY '%{$state}%' OR destination LIKE BINARY '%{$details->region}%' LIMIT 100");
?>