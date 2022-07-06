<?php require "../backend/forgot-password_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">

		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">

		<title>Reset Password</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
		<link rel="stylesheet" href="../style/form_style.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<script src="../js/lightMode.js"></script>
		<title>Password Reset</title>
	</head>
	<body>
		<?php if($expired == 1): ?>
			<div class="wrapper">
				<h2>Expired Link.</h2>
				<a class=" btn btn-secondary " href="../login.php">GO BACK TO LOGIN</a>
			</div>    
		<?php endif; ?>
		<?php if($expired == 0): ?>
			<div class="wrapper">
				<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
				<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
				<br>
				<div class = "info-bar">Please fill out this form to reset your password.</div>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
					<div class="form-group">
						<label>Question: <?php  echo nl2br(htmlspecialchars($array[$index])); ?></label>
						<input type="answer" name="answer" class="form-control <?php echo (!empty($ans_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ans; ?>">
							<span class="invalid-feedback"><?php echo $ans_err; ?></span>
					</div>
					<div class="form-group">
						<label>Username</label>
						<input type="text" name="username" class="form-control 
							<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
						<span class="invalid-feedback"> <?php echo $username_err; ?> </span>
					</div>
					<div class="form-group">
						<label>New Password</label>
						<input type="password" name="new_password" autocomplete="new-password" id = "new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
						<span class="invalid-feedback"><?php echo $new_password_err; ?></span>
						<input type="checkbox" onclick="showF()">Show Password</input>
						<script>
							function showF() {
								var x = document.getElementById("new_password");
								if (x.type === "password") {
									x.type = "text";
								} else {
									x.type = "password";
								}
							}
						</script>
						<div class="container">
							<br>
							<meter max="4" id="password-strength"></meter>
							<p id="password-strength-text"></p>
							<script type="text/javascript">
								var strength = {
								0: "Weakest",
								1: "Weak",
								2: "OK",
								3: "Good",
								4: "Strong"
								}
								var password = document.getElementById('new_password');
								var meter = document.getElementById('password-strength');
								var text = document.getElementById('password-strength-text');
								password.addEventListener('input', function() {
									var val = password.value;
									var result = zxcvbn(val);
									// This updates the password strength meter
									meter.value = result.score;
									// This updates the password meter text
									if (val !== "") {
										text.innerHTML = "Password Strength: " + strength[result.score]; 
									} else {
										text.innerHTML = "";
									}
								});
							</script>
						</div>
					</div>
					<div class="form-group">
						<label>Confirm Password</label>
						<input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
						<span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
					</div>
					<?php if($basics["tfaen"]==1 || $question['tfaen']==1)  : ?>
						<div class="form-group">
							<label>2FA Google Authenticator Code</label>
							<input type="2fa" name="2fa" id="2fa" class="form-control
								<?php echo (!empty($tfa_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $code; ?>">
							<span class="invalid-feedback"> <?php echo $tfa_err; ?> </span>
							<small id="2faHelp" class="form-text text-muted">2FA is Enabled.</small>
						</div>
					<?php endif; ?>
					<input type="hidden" name="email" value="<?php echo $email;?>"/>
					<input type="hidden" name="key" value="<?php echo $key;?>"/>
					<div class="form-group">
						<input type="submit" name="submit" class="btn btn-primary" value="Submit">
						<a class="btn btn-link ml-2" href="../login.php">Cancel</a>
					</div>
				</form>
			</div>    
		<?php endif; ?>
	</body>
</html>