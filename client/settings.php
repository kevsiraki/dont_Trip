<?php require "../backend/settings_backend.php";?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Account Settings</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>
		<link rel="stylesheet" href="../style/settings_style.css">
		<script src="../js/twoFactorAJAX.js"></script>
	</head>	
	<div class="topnav">
		<a href="javascript:void(0);" class="active" onclick="myFunction()">
			<i class="fa fa-bars"></i>
		</a>
		<div id="myLinks">
			<a href="../client/searches.php">Your Searches</a>
			<a href="../client/state.php">Popular In <?php echo $stateFull ?></a>
			<a href="../client/dt.php">Back to Don't Trip</a>
		</div>
	</div>		
	<body>
		<br>
		<div class="center">
			<div class="wrapper">
				<img src="../icons/user.jpg" id="user-pic">
				<h3 id="usernav" style="float:left; ">&nbsp;&nbsp;<?php echo $_SESSION['username']; ?></h3>
				<br><br><br><br>
				<?php if (isset($userResults)){ ?>
					<label class="switch" style="float:left;">
						<input type="checkbox" name="accept" id="check" value="yes">
						<span class="slider round"></span>
					</label>
					<div id="info" class="noselect">&nbsp;&nbsp;Two Factor Authentication</div>
					<?php 
					if($userResults["tfaen"] == 1) { 
					?>
						<script>
							document.querySelector('input[type="checkbox"]').checked = true;
						</script>
					<?php
						echo"<div id=\"to-hide\" style=\"display:block;\">
								<br>
								<div id=\"two_factor_div\">
								2FA Enabled.  Secret: <b>{$userResults['tfa']}</b>
								</div>
							</div>";
					} 
					?>
					<div id="two_factor_response"></div>
					<br>
					<a class="btn btn-outline-danger" href="delete_confirmation.php">Delete Account</a>
					<br><br>
					<a href="../client/reset-password.php" class="btn btn-outline-primary">Reset Password</a>
					<br><br>
				<?php } ?>
				<a class="btn btn-outline-warning" id="clear-searches" href="#">Clear Search History</a>
				<br>
				<div id="clear_response"></div>
				<br><br>
				<div style="text-align:center;">
					<a href="../backend/logout.php" class="btn btn-secondary" value="Submit" >Sign Out</a>
				</div>
				<br>
			</div>
		</div>
               <br>
		<script src="../js/clearSearchesAJAX.js"></script>
	</body>
</html>