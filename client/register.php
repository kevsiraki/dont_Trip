<?php 
require_once '../backend/config.php';
require_once '../backend/middleware.php';
require_once '../backend/php-csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Sign Up</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous"></script>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/form_style.css">
		<link rel="stylesheet" href="../style/form_base_style.css">
		<link rel="stylesheet" href="../style/header.css">
		<link rel="stylesheet" href="../style/footer.css">
		<script src="../js/lightMode.js"></script>
		<script src="../ajax/generalAJAX.js"></script>
		<script src="../ajax/registerAJAX.js"></script>
		<link rel="stylesheet" href="../style/meter_styles.css">
	</head>
	<body class="d-flex flex-column justify-content-between">
		<header class="header" id="header">
			<a href="../login" class="logo">
			<img draggable="false" src="../icons/icon_header.png" width="40" height="40"></img>
			</a>
			<div class="header-right">
				<a href="../login"><small>Login</small></a>
				<a href="register"class="active"><small>Sign Up</small></a>
			</div>
		</header>
		<div class="wrapper">
			<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div id="invalid-signup" class="center alert alert-danger"style="text-align:center; width: 90%; display:none;margin-bottom:30px !important;"></div>
			<input style="display:none">
			<input type="password" style="display:none" autocomplete="new-password"/>
			<input style="display: none" type="text" name="fakeusernameremembered" />
			<input style="display: none" type="password" name="fakepasswordremembered" />
			<div class="form-group" style="margin-bottom: 1% !important;">
				<input type="email" name="email" id="email" autocomplete="off" aria-describedby="emailHelp" class="center form-control" required>
				<div class="field-placeholder"><span>E-Mail Address</span></div>
			</div>
			<div id="ename_response" style="text-align:center;margin-bottom: 5.5%;"></div>
			<div class="form-group" style="margin-bottom: 1% !important;">
				<input type="text" name="username" id="username" autocomplete="off" class="center form-control" required>
				<div class="field-placeholder"><span>Username</span></div>
			</div>
			<div id="uname_response" style="text-align:center;margin-bottom: 5.5%;"></div>
			<div class="form-group">
				<input type="password" name="password" id="password" oninput="getBoth();" autocomplete="new-password" class="center form-control" required>
				<div class="field-placeholder"><span>Password</span></div>
				<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
			</div>
			<div id="password-strength" style="display: none;text-align:center;">
				<div id="length" class="pw-stength" ><small> At least 8 characters</small></div>
				<div id="lowercase" class="pw-stength"><small> At least 1 lowercase letter</small></div>
				<div id="uppercase" class="pw-stength"><small> At least 1 uppercase letter</small></div>
				<div id="number" class="pw-stength"><small> At least 1 number</small></div>
				<br>
			</div>
			<div class="form-group">
				<input type="password" name="confirm_password" id="confirm-password" oninput="getBoth();" autocomplete="new-password" class="center form-control" required>
				<div class="field-placeholder"><span>Confirm Password</span></div>
				<span toggle="#confirm-password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
			</div>
			<div id="confirm-password-strength" style="display: none;text-align:center;">
				<div id="matching" class="pw-stength"><small> Matching</small></div>
			</div>
			<br> 
				<button name="Submit" type="button" id="sign-up" class="center btn btn-success" onclick="this.blur();">Sign-Up</button>
			<br>
			<div id="info-two" class="darkable-text">Already have an account? <a href="../login">Login here</a></div>
			<input type="hidden" id="csrf" name="csrf" value="<?php echo $csrf; ?>">
		</div>
		<footer id="footer">
			<a href="../login" class="logo">
			<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github"></i>&nbsp;GitHub</a>
			</div>
		</footer>
		<script src="../js/ayhPassword.js"></script>
	</body>
</html>