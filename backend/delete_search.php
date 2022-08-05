<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'config.php';

/* Deletes 1 search from the user's history by id. */

$data = json_decode(file_get_contents("php://input"));

$what = $data->type;
$exactly = $data->id;

if($what=="destination") 
{
	$sql = "UPDATE searches SET destination = null WHERE destination = ? ;";
	if ($stmt = mysqli_prepare($link, $sql))
	{
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $param_destination);
		// Set parameters
		$param_destination = $exactly;
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo json_encode(array('message' => 'Destination Deleted'));
}
else if($what=="keyword") 
{
	$sql = "UPDATE searches SET keyword = null WHERE keyword = ? ;";
	if ($stmt = mysqli_prepare($link, $sql))
	{
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $param_keyword);
		// Set parameters
		$param_keyword = $exactly;
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	echo json_encode(array('message' => 'Keyword Deleted'));
} 
?>