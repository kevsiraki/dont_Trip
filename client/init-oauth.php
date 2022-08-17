<?php
//Initialize Discord O-Auth API
require_once "../backend/config.php";
session_destroy();
session_start();
header("Location:". $_ENV["discord_url"]);
die;
?>