<?php require "backend/login_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Don't Trip">
		<title>Don't Trip Login</title>
		<meta name="description" content="An itinerary planner utilizing the Google Maps API to give you customized places along a route!">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="style/form_style.css">
		<link rel="stylesheet" href="style/header.css">
		<link rel="stylesheet" href="style/footer.css">
		<link href="icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="icons/icon.png">
		<script src="js/loginAJAX.js"></script>
		<script src="js/lightMode.js"></script>
	</head>
	<body>
		<header class="header" id="header">
			<a href="." class="logo">
				<img draggable="false" src="icons/icon_header.png" width="40" height="40"></img>
			</a>
			<div class="header-right">
				<a href="." class="active"><small>Login</small></a>
				<a href="client/register"><small>Sign Up</small></a>
			</div>
		</header>
		<div class="wrapper" >
			<h2><img draggable="false" src="icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br><br>
			<div id="invalid-login" class="center alert alert-danger"style="text-align:center; width: 90%; display:none;"></div>
			<form>  
				<div class="form-group">
					<input type="text" id="username" name="username" placeholder="Username or E-mail" class="center form-control" required>
				</div>
				<div class="form-group">
					<input type="password" id="password" name="password" placeholder="Password" class="center form-control" required>
				</div>
				<br>
				<button type="button" id="log-in" onclick="this.blur();" class="center btn btn-success">Login</button>
				<br>
				<p id="other">&nbsp;&nbsp;Other Providers&nbsp;&nbsp;</p>
				<?php
					if($isAuth == "yes") {
						echo "<a class=\"btn btn-secondary btn-block\" href='".$client->createAuthUrl()."'><img width=\"20px\" style=\"margin-bottom:3px; margin-right:5px\" alt=\"Google sign-in\" src=\"https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png\" />Google</a>";
					}
				?>
				<br>
				<?php
				if(isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]===true) {
				?>
					<p><a href="client/dt" class="text-success">Continue current session?</a></p>
				<?php
				}
				else {
				?>
					<p><a href="client/dt" class="text-info">Continue as guest?</a></p>
				<?php
				}
				?>
				<p id="info">Need an account? <a href="client/register" style="">Sign up here</a></p>
				<a href="client/fp" style="white-space: nowrap;">Forgot your password?</a>
			</form>
			<br>
		</div>
		<footer id="footer">
			<a href="." class="logo">
				<img draggable="false" src="icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github" >&nbsp;Github</i></a>
			</div>
		</footer>
	</body>
</html>