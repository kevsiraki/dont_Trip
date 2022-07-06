<?php
require_once "config.php";
require_once "geolocation.php";
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
$result = mysqli_query($link, "SELECT DISTINCT destination FROM searches WHERE username in (SELECT username FROM searches WHERE username = '" . $_SESSION['username'] . "')");
$result2 = mysqli_query($link, "SELECT DISTINCT keyword FROM searches WHERE username IN (SELECT username FROM searches WHERE username = '" . $_SESSION['username'] . "')");
if(isset($_GET['toDelete'])) 
{
	mysqli_query($link,"UPDATE searches SET destination = null  WHERE destination = '".$_GET['toDelete']."'");
	header("location: searches.php");
}
if(isset($_GET['toDelete2'])) 
{
	mysqli_query($link,"UPDATE searches SET keyword = null  WHERE keyword = '".$_GET['toDelete2']."'");
	header("location: searches.php");
}
?>