<?php require "../backend/reset-password_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Reset Password</title>
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
		<div class="wrapper" >
			<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class = "info-bar" id="info-bar">Fill out this form to reset your password.</div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<input style="display:none">
				<input type="password" style="display:none" autocomplete="new-password"/>
				<div class="form-group">
					<label></label>
					<input type="password" name="new_password" id="password" 
					onkeyup="getPassword();getConfirmPassword();" onfocus="showMeter();showConfirmMeter();getPassword();getConfirmPassword();" 
					autocomplete="new-password" required="" aria-describedby="emailHelp"
					placeholder="New Password" class="center form-control 
					<?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
					<span class="invalid-feedback"style="text-align:center;"><?php echo $new_password_err; ?></span>
				</div>
				<label style="margin-left:5%;">
					<input type="checkbox" onclick="showF();showMeter();showConfirmMeter();getPassword();getConfirmPassword();">
					<small id="info">Show</small>
				</label>
				<div id="password-strength" style="display: none;text-align:center;">
					<div id="length" class="pw-stength"><small> At least 8 characters</small></div>
					<div id="lowercase" class="pw-stength"><small> At least 1 lowercase letter</small></div>
					<div id="uppercase" class="pw-stength"><small> At least 1 uppercase letter</small></div>
					<div id="number" class="pw-stength"><small> At least 1 number</small></div>
					<br>
				</div>
				<div class="form-group">
					<input type="password" name="confirm_password" id="confirm-password"
					onkeyup="getPassword();getConfirmPassword();" onfocus="showMeter();showConfirmMeter();getPassword();getConfirmPassword();" 
					autocomplete="new-password" required="" aria-describedby="emailHelp"
					placeholder="Confirm New Password" class="center form-control 
					<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
					<span class="invalid-feedback"style="text-align:center;"><?php echo $confirm_password_err; ?></span>
				</div>
				<div id="confirm-password-strength" style="display: none;text-align:center;">
					<div id="matching" class="pw-stength"><small> Matching</small></div>
				<br>
				</div>
				<div class="form-group" style="margin-left:5%;">
					<input type="submit" class="btn btn-primary" value="Submit">
					<a class="btn btn-link ml-2" href="settings.php">Cancel</a>
				</div>
			</form>
		</div>    
	</body>
</html>