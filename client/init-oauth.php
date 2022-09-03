<?php
//Initialize Discord O-Auth API
require_once "../backend/config.php";
if(!empty($_SESSION)) {
	session_destroy();
}
session_start();
header("Location: ". $_ENV["discord_url"]);
die;
?>
