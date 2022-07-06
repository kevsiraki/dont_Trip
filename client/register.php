<?php require "../backend/register_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head> 
		<meta charset="UTF-8">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
		<title>Sign Up</title>
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/form_style.css">
		<script src="../js/lightMode.js"></script>
		<title>Sign-Up</title>
	</head>
	<body>
		<div class="wrapper">
	  		<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class="info-bar">
				Fill Out This Form to Sign-Up
			</div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-group">
					<label for="exampleInputEmail1">Email Address</label>
					<input type="email" name="email"  id="email" required="" aria-describedby="emailHelp" class="form-control
					<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
					<span class="invalid-feedback"> <?php echo $email_err; ?> </span> 
					<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
				</div>
				<div class="form-group">
					<label>Username</label>
					<input type="text" name="username" class="form-control 
					<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
					<span class="invalid-feedback"> <?php echo $username_err; ?> </span>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" id="password" autocomplete="new-password" class="form-control 
						<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
					<span class="invalid-feedback"> <?php echo $password_err; ?> </span>
					<br>
					<input type="checkbox"  onclick="showF()">Show Password </input>
					<script>
						function showF() {
							var x = document.getElementById("password");
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
							var password = document.getElementById('password');
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
					<input type="password" name="confirm_password" class="form-control 
						<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
					<span class="invalid-feedback"> <?php echo $confirm_password_err; ?> </span>
				</div>
				<div class="form-group">
					<label for='questions[]'>Select a Recovery Question:</label><br>
					<select  name="questions[]" class="form-control" size = "4">
						<option value="1" <?php if (isset($aQuestions[0]) && $aQuestions[0]=="1") echo "selected";?>>What is your mother's maiden name?</option>
						<option value="2" <?php if (isset($aQuestions[0]) && $aQuestions[0]=="2") echo "selected";?>>What is your favorite pet's name?</option>
						<option value="3" <?php if (isset($aQuestions[0]) && $aQuestions[0]=="3") echo "selected";?>>What city was your first job in?</option>
						<option value="4" <?php if (isset($aQuestions[0]) && $aQuestions[0]=="4") echo "selected";?>>Where did you go to for 6th grade?</option>
						<option value="5" <?php if (isset($aQuestions[0]) && $aQuestions[0]=="5") echo "selected";?>>Who was your 3rd grade teacher?</option>
						<option value="6" <?php if (isset($aQuestions[0]) && $aQuestions[0]=="6") echo "selected";?>>What was your childhood nickname?</option>
					</select>  
				</div>
				<div class="form-group">
					<label>Answer</label>
					<input type="answer" name="answer" class="form-control <?php echo (!empty($ans_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ans; ?>">
					<span class="invalid-feedback"><?php echo $ans_err; ?></span>
				</div>
				<div class="form-group">
					<input name="Submit" type="submit" value="Submit" class="btn btn-primary">
					<input type="reset" class="btn btn-secondary ml-2" value="Reset">
					<br>
					<br>
					<p>Already have an account? <a href="../login.php">Login here</a></p> 
				</div>
			</form>
		</div><br><br>
	</body>
</html>