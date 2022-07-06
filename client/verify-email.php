<?php require "../backend/verify_email_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Email Verification</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="../style/verify-email_style.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
	</head>
	<body> 
		<div class="container mt-3">
			<div class="card">
				<div class="card-header text-center">
					<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="150" height="40" /></img></h2>
					<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
					<br>
					<div class="info-bar">
						Email Verification
					</div>
				</div>
				<div class="card-body">
					<p> <?php echo $msg; ?> </p>
					<a href="../login.php" class="btn-primary btn-sm " style="margin: auto;">Return to Login</a>
				</div>
			</div>
		</div>
	</body>
</html>