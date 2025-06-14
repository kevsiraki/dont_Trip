<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once "config.php";
require_once 'helpers.php';
require_once "geolocation.php";
require_once 'middleware.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data) && isset($data->destination) && !empty($data->destination))
{
	$ip = getIpAddr();
    $sql = "INSERT INTO searches (username, destination, keyword, ip) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_destination, $param_keyword, $param_ip);
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
        {
            $param_username = $_SESSION["username"];
        }
        else
        {
            $param_username = "Guest";
        }
        $param_destination = trim($data->destination);
        if (!empty($data->keyword))
        {
            $param_keyword = trim($data->keyword);
        }
        else
        {
            $param_keyword = null;
        }
		$param_ip = $ip;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
	die(json_encode(["message" => "Successfully Recorded Search."]));
}
else
{
    die(json_encode(["message" => "Query String Malformed."]));
}
?>