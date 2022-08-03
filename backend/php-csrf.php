<?php
if (isset($_SESSION))
{
    unset($_SESSION['key']);
}
else if (!isset($_SESSION))
{
    session_start();
}
if (empty($_SESSION['key'])) {
	$_SESSION['key'] = bin2hex(random_bytes(32));
}

//create CSRF token
$csrf = hash_hmac('sha256', $_ENV["recovery_key"],  $_SESSION['key']);
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (empty($csrf)|| empty($_POST['csrf']) || !hash_equals($csrf, $_POST['csrf']))
	{
		die("Malformed request.");
	}
}
?>
