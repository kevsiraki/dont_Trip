<?php require "../backend/reset-password_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Reset Password</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/resetpass_style.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
		<script src="../js/lightMode.js"></script>
		<title>Password Reset</title>
	</head>
	<body>
		<div class="wrapper" >
			<h2>Reset Password</h2>
			<p>Please fill out this form to reset your password.</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
				<div class="form-group">
					<label>New Password</label>
					<input type="password" name="new_password" id = "new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
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
					<input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
					<span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
					<a class="btn btn-link ml-2" href="settings.php">Cancel</a>
				</div>
			</form>
		</div>    
	</body>
</html>