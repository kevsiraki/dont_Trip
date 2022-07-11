<?php require "../backend/delete_confirmation_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Delete Account</title>
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
			<div class = "info-bar" id="info-bar">Please enter your password to confirm deletion.</div>
			<?php 
				if(!empty($login_err)){
					echo '<div class="alert alert-danger"style="text-align:center;">' . $login_err . '</div>';
				}
			?> 
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<input style="display:none">
				<input type="password" style="display:none" autocomplete="new-password"/>
				<div class="form-group">
					<input type="password" name="password" id="password" placeholder="Confirm Password" class="center form-control 
					<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" required>
					<span class="invalid-feedback"style="text-align:center;"><?php echo $password_err; ?></span>
				</div>
				<label style="margin-left:5%;">
					<input type="checkbox" onclick="showF();">
					<small id="info">Show</small>
				</label>
				<div class="form-group" style="margin-left:5%;">
					<input type="submit" class="btn btn-danger" value="Delete Account">
					<a class="btn btn-link ml-2" href="settings.php">Cancel</a>
				</div>
			</form>
		</div>    
	</body>
</html>