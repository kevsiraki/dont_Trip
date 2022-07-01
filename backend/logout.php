<?php
// Initialize the session
//require_once "config.php";
session_start();
//mysqli_query($link, "UPDATE users SET remember = NULL WHERE username = '".$_SESSION["username"]."';");
// Unset all of the session/cookie variables
$_SESSION = array();
//$_COOKIE = array();
//setcookie("idrm_tkn", "", time() - 3600, "/donttrip/");

// Destroy the session.
session_destroy();
// Redirect to login page
header("location: https://donttrip.technologists.cloud/donttrip/");
exit;
?>