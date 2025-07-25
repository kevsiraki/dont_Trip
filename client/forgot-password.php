<?php 
require_once '../backend/config.php';
require_once '../backend/middleware.php';
require_once '../backend/php-csrf.php';
require_once '../backend/expired_form.php';
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
		<title>Password Reset Email</title>
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
		<script src="../ajax/forgotPasswordAJAX.js" defer></script>
		<link rel="preload" href="../style/meter_styles.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
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
		<?php if($expired == 1): ?>
			<div class = "info-bar darkable-text" id="info-bar">Expired/Invalid Link.</div>
			<a class="center btn btn-info" href="../login">Back To Login</a>
		</div>    
		<?php endif; ?>
		<?php if($expired == 0): ?>
				<div class = "info-bar darkable-text" id="info-bar">Please fill out this form to reset your password.</div>
				<div id="invalid-reset" class="center alert alert-danger"style="text-align:center; width: 90%; display:none; margin-bottom:30px !important;"></div>
				<input style="display:none">
				<input type="password" style="display:none" autocomplete="new-password">
				<div class="form-group" style="margin-top:10px !important;">
					<input type="password" name="new_password" id="password" oninput="getBoth();" autocomplete="new-password" class="center form-control" required>
					<div class="field-placeholder"><span>New Password</span></div>
					<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
				</div>
				<div id="password-strength" style="display: none;text-align:center;">
					<div id="length" class="pw-stength"><small> At least 8 characters</small></div>
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
					<br>
				</div>
				<?php if($userResults['tfaen']==1) : ?>
					<div class="form-group">
						<input type="text" name="tfa" id="tfa" autocomplete="off" class="center form-control" required>
						<div class="field-placeholder"><span>2FA code for <?php echo $userResults["username"]?></span></div>
					</div>
				<?php endif; ?>
				<input type="hidden" name="email" id="hidden-email" value="<?php echo $email;?>"/>
				<input type="hidden" name="key" id="hidden-key"value="<?php echo $key;?>"/>
				<div class="form-group"style="margin-left:5%;">
					<button type="button" id="submit-password" class="btn btn-primary" onclick="this.blur();">Submit</button>
					<a class="btn btn-link ml-2" href="../login">Cancel</a>
				</div>
				<input type="hidden" id="csrf" name="csrf" value="<?php echo $csrf ?>">
			</div>    
		<?php endif; ?>
		<footer id="footer">
			<a href="../login" class="logo">
				<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40" alt="Don't Trip" loading="lazy"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link" rel="noopener"><i class="fa fa-github"></i>&nbsp;GitHub</a>
			</div>
		</footer> 
		<script src="../js/ayhPassword.js"></script>
	</body>
</html>