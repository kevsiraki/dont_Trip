<?php
require_once '../backend/config.php';
require_once '../backend/middleware.php';
include '../backend/php-csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Don't Trip">
		<link rel="apple-touch-icon"  sizes="256x256" href="../icons/icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../../favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../../favicon-16x16.png">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="manifest" href="../../site.webmanifest">
		<script src="../../app.js"></script>
		<link rel="mask-icon" href="../../safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="description" content="An itinerary planner utilizing the Google Maps API to give you customized places along a route!">
		<title>Reset Password Request</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="this.onerror=null;this.href='../style/bootstrap.min.css';" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous" defer></script>
		<link rel="stylesheet" href="../style/form_style.css">
		<link rel="stylesheet" href="../style/form_base_style.css">
		<link rel="preload" href="../style/header.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<link rel="preload" href="../style/footer.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<script src="../js/lightMode.js"></script>
		<script src="../ajax/generalAJAX.js" defer></script>
		<script src="../ajax/fpAJAX.js" defer></script>
	</head>
	<body class="d-flex flex-column justify-content-between">
		<header class="header" id="header">
			<a href="../login" class="logo">
				<img draggable="false" src="../icons/icon_header.png" width="40" height="40" alt="Don't Trip" loading="lazy"></img>
			</a>
			<div class="header-right">
				<a href="../login"><small>Login</small></a>
				<a href="register"><small>Sign Up</small></a>
			</div>
		</header>
		<div class="wrapper">
			<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" alt="Don't Trip" loading="lazy"/></img></h2>
			<a rel="noopener" href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class = "info-bar darkable-text" id="info-bar">Enter your e-mail for a password recovery form.</div>
			<div id="invalid-email" class="center alert alert-danger"style="text-align:center; width: 90%; display:none;margin-bottom:25px !important;"></div>
			<div class="form-group" style="margin-top:10px !important;margin-bottom:1% !important;">
				<input type="email" name="email"  id="email-reset" aria-describedby="emailHelp" autocomplete="off" class="center form-control" required>
				<div class="field-placeholder"><span>E-Mail Address</span></div>
			</div>
			<div id="ename_response" style="text-align: center;margin-bottom:15px"></div>
			<div class="form-group" style="margin-left:5%">
				<button type="button" id="submit-email" class="btn btn-primary" onclick="this.blur();">Submit</button>
				<a class="btn btn-link ml-2" href="../login">Cancel</a>
			</div>
			<input type="hidden" id="csrf" name="csrf" value="<?php echo $csrf; ?>">
		</div>
	</body>
	<footer id="footer">
		<a href="../login" class="logo">
			<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40" alt="Don't Trip" loading="lazy"></img>
		</a>
		<div class="footer-right">
			<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link" rel="noopener"><i class="fa fa-github"></i>&nbsp;GitHub</a>
		</div>
	</footer>
</html>