<?php
//Discord O-Auth API requests
require_once "config.php";

if (!isset($_GET['code'])) {
    header("location: ../login");
    exit();
}

$discord_code = $_GET['code'];

$payload = ['code' => $discord_code, 'client_id' => $_ENV["discord_client_id"], 'client_secret' => $_ENV["discord_client_secret"], 'grant_type' => 'authorization_code', 'redirect_uri' => 'https://www.donttrip.org/donttrip/backend/process-oauth', 'scope' => 'identify%20guids'];

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

if (!$result) {
    die(curl_error($ch));
}

$result = json_decode($result, true);
$access_token = $result['access_token'];

$discord_users_url = "https://discordapp.com/api/users/@me";
$header = array(
    "Authorization: Bearer $access_token",
    "Content-Type: application/x-www-form-urlencoded"
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL, $discord_users_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);

$result = json_decode($result, true);

if (!isset($_SESSION)) {
    $sessionConfig = (new \ByJG\Session\SessionConfig('donttrip.org'))->withSecret($_ENV["recovery_key"])->replaceSessionHandler();
    $handler = new \ByJG\Session\JwtSession($sessionConfig);
}

unset($_SESSION['userid']);
unset($_SESSION['userData']);
unset($_SESSION['googleAvatar']);

$_SESSION['logged_in'] = true;
$_SESSION['userData'] = ['name' => $result['username'], 'discord_id' => $result['id'], 'avatar' => $result['avatar']];

extract($_SESSION['userData']);

$_SESSION["username"] = $name . " (Discord)[" . $discord_id . "]";
$_SESSION['userid'] = "(Discord)[" . $discord_id . "]";
$_SESSION["loggedin"] = true;
$_SESSION['loginTime'] = time();

// Source - https://stackoverflow.com/a/2138534
// Posted by miku, modified by community. See post 'Timeline' for change history
// Retrieved 2026-08-10, License - CC BY-SA 4.0

// Modified by yours truly :)

$ch = curl_init($_ENV["discord_webhook_url"]);
// return the response instead of sending it to stdout:
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
// set the POST data, corresponding method and headers:
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['content' => $name . ' (Discord ID: ' . $discord_id . ') has logged in to Don\'t Trip !']));
// send the request and get the response
$server_output = curl_exec($ch);

$redirect_url = "../client/dt";
header("Location: $redirect_url");