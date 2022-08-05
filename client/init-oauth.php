<?php
//Initialize Discord O-Auth API
require_once "../backend/config.php";
header("Location:". $_ENV["discord_url"]);
die;
?>