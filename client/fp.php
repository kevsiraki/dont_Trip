<?php require "../backend/fp_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Reset Password Request</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" href="../style/form_style.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<script src="../js/lightMode.js"></script>
		<script src="../js/checkpw.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class = "info-bar" id="info-bar">Enter your e-mail for a password recovery form.</div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
				<div class="form-group">
					<input type="email" name="email"  id="email-reset" required="" aria-describedby="emailHelp" 
					autocomplete="off" placeholder="E-mail Address" class="center form-control
						<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
					<div id="ename_response" style="text-align: center;"></div>	
					<span class="invalid-feedback" style="text-align:center;"> <?php echo $email_err; ?> </span> 
					<small id="emailHelp" class="form-text text-muted" style="margin-left:5%">
						This serves as a re-verification token.
					</small>
				</div>
				<div class="form-group" style="margin-left:5%">
					<input type="submit" name="submit" class="btn btn-primary" value="Submit">
					<a class="btn btn-link ml-2" href="../login.php">Cancel</a>
				</div>
			</form>
		</div>    
	</body>
</html>