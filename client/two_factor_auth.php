<?php 
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 
if (empty($_SESSION["username"])) {
    header("location: ../login.php");
    die;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Two Factor Authentication</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/form_style.css">
		<link rel="stylesheet" href="../style/form_base_style.css">
		<link rel="stylesheet" href="../style/header.css">
		<link rel="stylesheet" href="../style/footer.css">
		<script src="../js/lightMode.js"></script>
		<script src="../ajax/twoFactorLoginAJAX.js"></script>
	</head>
	<body class="d-flex flex-column justify-content-between">
		<header class="header" id="header">
			<a href="../login" class="logo">
			<img draggable="false" src="../icons/icon_header.png" width="40" height="40"></img>
			</a>
			<div class="header-right">
				<a href="../login" ><small>Login</small></a>
				<a href="register" ><small>Sign Up</small></a>
			</div>
		</header>
		<div class="wrapper" >
			<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class = "info-bar" id="info-bar">Please enter your OTP to login.</div>
			<div id="invalid-login" class="center alert alert-danger"style="text-align:center; width: 90%; display:none;"></div>
			<div class="form-group">
				<input type="2fa" name="tfa" id="tfa" placeholder="2FA OTP" autocomplete="off" class="center form-control" required>
			</div>
			<div class="form-group" style="margin-left:5%;">
				<button type="button" id="verify" onclick="this.blur();" class="btn btn-success" >Verify</button>
				<a class="btn btn-link ml-2" href="../backend/logout">Cancel</a>
			</div>
		</div>
		<div></div>
		<footer id="footer">
			<a href="." class="logo">
			<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github" >&nbsp;GitHub</i></a>
			</div>
		</footer>
	</body>
</html>