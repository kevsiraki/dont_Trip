<?php require "../backend/register_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head> 
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Sign Up</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/form_style.css">
		<script src="../js/lightMode.js"></script>
		<script src="../js/checkpw.js"></script>
		<link rel="stylesheet" href="../style/meter_styles.css">
	</head>
	<body>
		<div class="wrapper">
	  		<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class="info-bar" id="info-bar">
				Fill Out This Form to Sign-Up
			</div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<input style="display:none">
				<input type="password" style="display:none" autocomplete="new-password"/>
				<input style="display: none" type="text" name="fakeusernameremembered" />
				<input style="display: none" type="password" name="fakepasswordremembered" />
				<div class="form-group">
					<input type="email" name="email" placeholder="E-mail Address" id="email" required="" 
						autocomplete="off" aria-describedby="emailHelp" class="center form-control
						<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>"/>
					<div id="ename_response" style="text-align:center;"></div>
					<span class="invalid-feedback" style="text-align:center;"> <?php echo $email_err; ?> </span> 
				</div>
				<div class="form-group">
					<input type="text" placeholder="Username" name="username" id="username" required="" aria-describedby="emailHelp" 
					autocomplete="off" class="center form-control 
					<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
					<div id="uname_response" style="text-align:center;"></div>
					<span class="invalid-feedback"style="text-align:center;"> <?php echo $username_err; ?> </span>
				</div>
				<div class="form-group">
					<input type="password" placeholder="Password" name="password" id="password" 
					onkeyup="getPassword();getConfirmPassword();" onfocus="showMeter();showConfirmMeter();getPassword();getConfirmPassword();" 
					autocomplete="new-password" required="" aria-describedby="emailHelp" 
					class="center form-control 
						<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
					<span class="invalid-feedback"style="text-align:center;"> <?php echo $password_err; ?> </span>
				</div>
				<label style="margin-left:5%;">
					<input type="checkbox" onclick="showF();showMeter();showConfirmMeter();getPassword();getConfirmPassword();">
					<small id="info">Show</small>
				</label>
				<div id="password-strength" style="display: none;text-align:center;">
					<div id="length" class="pw-stength" ><small> At least 8 characters</small></div>
					<div id="lowercase" class="pw-stength"><small> At least 1 lowercase letter</small></div>
					<div id="uppercase" class="pw-stength"><small> At least 1 uppercase letter</small></div>
					<div id="number" class="pw-stength"><small> At least 1 number</small></div>
					<br>
				</div>
				<div class="form-group">
					<input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" 
					onfocus="showMeter();showConfirmMeter();getPassword();getConfirmPassword();" 
					autocomplete="new-password" onkeyup="getPassword();getConfirmPassword();" 
					required="" aria-describedby="emailHelp" class="center form-control 
						<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
					<span class="invalid-feedback"style="text-align:center;"> <?php echo $confirm_password_err; ?> </span>
				</div>
				<div id="confirm-password-strength" style="display: none;text-align:center;">
					<div id="matching" class="pw-stength"><small> Matching</small></div>
					<br>
				</div>
					<script src="../js/lightMode.js"></script>
					<input name="Submit" type="submit" value="Sign-Up" class="center btn btn-success">
					<br>
					<p id="info-two">Already have an account? <a href="../login.php">Login here</a></p> 
			</form>
		</div>
		<br>
		<br>
	</body>
</html>