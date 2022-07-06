<?php require "../backend/fp_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Reset Password Request</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="../style/form_style.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<script src="../js/lightMode.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class = "info-bar">Enter your e-mail for a password recovery form.</div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
				<div class="form-group">
					<label for="exampleInputEmail1">Email address: </label>
					<input type="email" name="email"  id="email" required="" aria-describedby="emailHelp" class="form-control
						<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
					<span class="invalid-feedback"> <?php echo $email_err; ?> </span> 
					<small id="emailHelp" class="form-text text-muted">This serves as a re-verification token.</small>
				</div>
				<div class="form-group">
					<input type="submit" name="submit" class="btn btn-primary" value="Submit">
					<a class="btn btn-link ml-2" href="../login.php">Cancel</a>
				</div>
			</form>
		</div>    
	</body>
</html>