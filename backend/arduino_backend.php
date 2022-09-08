<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "config.php";
require_once 'helpers.php';
if(isset($_GET['action']) && !empty($_GET['action']))
{
	$response = json_decode(get_web_page("http://".$_ENV["myIP"]."/".$_GET['action'].'?ip='.getIpAddr()));
	if(!empty($response)) {
		echo(json_encode($response));
	}
	else {
		die(json_encode(['LED_UPDATE'=>'Arduino is currently sleeping.']));
	}
}
?>