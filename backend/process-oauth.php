<?php
//Discord O-Auth API requests
require_once "config.php";

if(!isset($_GET['code']))
{
    header("location: ../login");
    exit();
}

$discord_code = $_GET['code'];

$payload = [
    'code'=>$discord_code,
    'client_id'=>$_ENV["discord_client_id"],
    'client_secret'=>$_ENV["discord_client_secret"],
    'grant_type'=>'authorization_code',
    'redirect_uri'=>'https://donttrip.org/donttrip/backend/process-oauth',
    'scope'=>'identify%20guids'
];

$payload_string = http_build_query($payload);
$discord_token_url = "https://discordapp.com/api/oauth2/token";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $discord_token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

if(!$result)
{
    die(curl_error($ch));
}

$result = json_decode($result,true);
$access_token = $result['access_token'];

$discord_users_url = "https://discordapp.com/api/users/@me";
$header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL, $discord_users_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

$result = json_decode($result, true);

if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['logged_in'] = true;

$_SESSION['userData'] = [
    'name'=>$result['username'],
    'discord_id'=>$result['id'],
    'avatar'=>$result['avatar']
];

extract($_SESSION['userData']);

$_SESSION["username"] = $name." (Discord)[".$discord_id."]";
$_SESSION["loggedin"] = true;
$_SESSION['loginTime'] = time();
session_regenerate_id(true);

echo('
	<script>
	window.opener.postMessage("'.$name.'", "https://donttrip.org/donttrip/login");
    </script>
	');
?>