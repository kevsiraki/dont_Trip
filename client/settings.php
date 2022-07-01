<?php 
require "../backend/settings_backend.php";
ini_set("allow_url_fopen", 1);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Account Settings</title>
		<link href="../icons/dt.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="../style/settings_style.css">	
		<script src="../js/settings.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="../js/nav.js"></script>
		<title>Account Settings</title>
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
		<div class="center" >
			<div class="wrapper" >
				<h5><?php echo $_SESSION['username']; ?></h5><br><br>
				<form  method="post"  >
					<?php if (isset($basics)): ?>
						<input type="checkbox"  name="2fa" value="Yes" ><b>&nbsp;&nbsp;&nbsp;</b></input>
						<input type="submit" name="formSubmit2" value="Update 2FA Status?" class="btn btn-secondary btn-sm" />
						<br>
						<?php
							ob_start();
							if($basics["tfaen"] == 0 && isset($_POST['formSubmit2']) &&$_POST["2fa"]!="Yes") {
								ob_end_clean(); 
								echo "2FA is already Disabled."; 
							}
							else if (isset($_POST['formSubmit2']) && $_POST['2fa']!='Yes' && $basics["tfaen"] == 1 ) {
								ob_end_clean();
								echo "2FA has been Disabled";
								mysqli_query($link, "UPDATE users SET tfaen=0 WHERE username = '" . $basics["username"] . "';");
								mysqli_query($link, "UPDATE users SET tfa='0' WHERE username = '" . $basics["username"] . "';");
							}
							else if($basics["tfaen"] == 1) {
								ob_end_clean(); 
								echo "2FA is already Enabled. Your secret: ".$basics["tfa"];
							}
						?>
						<?php if(isset($_POST['2fa']) && $basics["tfaen"] == 0 && $_POST["2fa"]=="Yes")  : ?>	
							<div class="form-group" id="myDIV">
								<b>
									<label>2FA has been Enabled. <br> Remember this Google Authenticator Code:</label>
									<?php 
										$g = new \Google\Authenticator\GoogleAuthenticator();
										$secret = str_shuffle('XVQ2UIGO75XRUKJ2');
										echo (htmlspecialchars($secret));
										mysqli_query($link,"UPDATE users SET tfaen=1 WHERE username = '".$basics["username"]."';");
										mysqli_query($link,"UPDATE users SET tfa='".$secret ."' WHERE username = '".$basics["username"]."';");   
										$url =  \Google\Authenticator\GoogleQrUrl::generate(urlencode($basics["username"]), urlencode($secret),urlencode("DT"));
									?>	
								</b> 
								<br>
								<img src = "<?php echo $url; ?>" alt = "QR Code" />
							</div>
					<?php endif; ?>
				</form>
				<br>
				<form  method="post">
					<input type="checkbox" name="del" value="Yes" > <b>&nbsp;&nbsp;</b></input>
					<input type="submit" name="formSubmit" value="Delete Account?" class="btn btn-secondary btn-sm" />
				</form>
				<?php endif; ?>
				<br>
				<form  method="post">
					<input type="checkbox" name="delS" value="Yes" > <b>&nbsp;&nbsp;</b></input>
					<input type="submit" name="formSubmit" value="Clear Search History?" class="btn btn-secondary btn-sm" />
				</form>
				<br>
				<br><br>
				<p>
					&nbsp;<a href="../backend/logout.php" class="btn btn-secondary btn-sm" value="Submit" >Sign Out</a>
					<br><br>
					<?php if (isset($basics)): ?>
						&nbsp;<a href="../client/reset-password.php" class="btn btn-secondary btn-sm">Reset Password</a>
					<?php endif; ?>
					<br><br>
				</p>
			</div>
		</div>
	</body>
</html>
