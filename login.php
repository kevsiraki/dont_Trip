<?php require "backend/login_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Don't Trip">
		<title>Don't Trip Login</title>
		<meta name="google-site-verification" content="oHK1-h8_kK5NiZbuI_qVhcujbIbJaFEH6neEfM20GgI" />
		<meta name="description" content="An itinerary planner utilizing the Google Maps API to give you customized places along a route!">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="style/form_style.css">
		<link href="icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="icons/icon.png">
		<script src="js/lightMode.js"></script>
	</head>
	<body><script src="js/lightMode.js"></script>
		<div class="wrapper" >
			<h2><img draggable="false" src="icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br><br>
			<?php 
				if(!empty($login_err)){
					echo '<div class="center alert alert-danger"style="text-align:center; width: 90%;">' . $login_err . '</div>';
				}
			?> 
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
				<div class="form-group">
					<input type="text" name="username" placeholder="Username" class="center form-control 
						<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $usernameOrEmail; ?>"required>
					<span class="invalid-feedback"style="text-align:center;"> <?php echo $username_err; ?> </span>
				</div>
				<div class="form-group">
					<input type="password" name="password" placeholder="Password" class="center form-control 
						<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $password; ?>"required>
					<span class="invalid-feedback"style="text-align:center;"> <?php echo $password_err; ?> </span>
				</div>
				<?php if((isset($userResults["tfaen"])||isset($emailResults["tfaen"]))) { ?>
					<?php if($showTFA) { ?>
						<div class="form-group">
							<input type="2fa" name="2fa" id="2fa" placeholder="2FA Google Authenticator Code" 
							autocomplete="off" class="center form-control
							<?php echo (!empty($tfa_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $code; ?>">
							<span class="invalid-feedback"style="text-align:center;"> <?php echo $tfa_err; ?> </span>
							<small id="2faHelp" style="margin-left:5%"class="form-text text-muted">2FA is Enabled.</small>
						</div>
					<?php 
						} 
					}  
					?>
				<br>
				<input name="Submit" type="submit"  value="Login" class="center btn btn-success"></input>
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
					<p><a href="client/dt.php" class="text-success">Continue current session?</a></p>
				<?php
					}
					else {
				?>
					<p><a href="client/dt.php" class="text-info">Continue as guest?</a></p>
				<?php
					}
				?>
				<p id="info">Need an account? <a href="client/register.php" style="">Sign up here</a></p>
				<a href="client/fp.php" style="white-space: nowrap;">Forgot your password?</a>
			</form>
		</div>
	</body>
</html>