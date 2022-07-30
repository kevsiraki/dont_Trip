<?php require_once "../backend/settings_backend.php";?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Account Settings</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous"></script>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/navbar.css" >
		<link rel="stylesheet" href="../style/settings_style.css">
		<link rel="stylesheet" href="../style/footer.css">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>
		<script src="../ajax/twoFactorAJAX.js"></script>
        <style> 
			#footer { 
				position:fixed;
				bottom: 0; 
				width: 100%; 
			} 
		</style> 
	</head>			
	<body>
	<?php 
	if(!empty($_SESSION['loggedin'])&&$_SESSION['loggedin']===true) {
	?>
		<header class="topnav" id="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars" id="burger"></i>
			</a>
			<div id="myLinks">
				<a href="dt" class="navlink">Itinerary Planner</a>
				<a href="state" class="navlink">Popular In <?php echo $stateFull ?></a>
				<a href="searches" class="navlink">Your Searches</a>
				<a href="settings" class="navlink currentPage">Account Settings</a>
			</div>
		</header>
	<?php } else { ?>
		<header class="topnav" id="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars" id="burger"></i>
			</a>
			<div id="myLinks">
				<a href="dt" class="navlink">Itinerary Planner</a>
				<a href="settings" class="navlink currentPage">Settings</a>
			</div>
		</header>
	<?php } ?>
		<br>
		<div class="center">
			<div class="wrapper">
			<?php 
			if(!empty($_SESSION['loggedin'])&&$_SESSION['loggedin']===true) {
			?>
				<div id="usernav-bg" style="width=90%;border-radius: 25px; background: rgba(211, 211, 211, 0.2);">
					<?php 
					$login_type_logo = '';
					if(!empty($_SESSION['userData'])) { //Discord Profile Picture
						extract($_SESSION['userData']);
						$avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";
					?>
						<br>
						<img id="user-pic" src="<?php echo $avatar_url;?>" />
					<?php 
					}
					else if(!empty($_SESSION['googleAvatar'])) { //Google Profile Picture
					?>
						<br>
						<img id="user-pic" src="<?php echo $_SESSION['googleAvatar'];?>" />
					<?php 
					}
					else if(!empty($_SESSION['fbAvatar'])) { //Facebook Profile Picture
					?>
						<br>
						<img id="user-pic" src="<?php echo $_SESSION['fbAvatar']["url"];?>" />
					<?php 
					} else if(empty($_SESSION['userData'])&&empty($_SESSION['googleAvatar'])&&empty($_SESSION['fbAvatar'])) {
					?>
					<br>
					<img id="user-pic" src="../icons/icon_pfp.png" style="background-color:#A9A9A9;" />
					<?php 
					} if(!empty($_SESSION['userData'])||!empty($_SESSION['googleAvatar'])||!empty($_SESSION['fbAvatar'])) {
						preg_match_all('/\(([A-Za-z0-9 ]+?)\)/', $_SESSION["username"], $out); 
						$logo = strtolower($out[1][0]);
						$class = "fab fa-{$logo}";
						$login_type_logo = "<i class=\"".$class."\"></i>";
					}?>
					<h4 id="usernav" style="padding: 20px;text-align:center;font-family: 'Courier New', monospace;">
						<?php echo trim(preg_replace('/\[[^)]+\]/', '', preg_replace('/\([^)]+\)/',' '.$login_type_logo,$_SESSION['username']))); ?>
					</h4>
				</div>
			<?php } else { ?>
				<div id="usernav-bg" style="width=90%;border-radius: 25px; background: rgba(211, 211, 211, 0.3);">
					<br>
					<img id="user-pic" src="../icons/user.png" />
					<h4 id="usernav" style="padding: 20px;text-align:center;font-family: 'Courier New', monospace;">Guest</h4>
				</div>
				<br>
			<?php } ?>
				<br>
				<?php 
				if(!empty($_SESSION['loggedin'])&&$_SESSION['loggedin']===true) {
				?>
					<button style = "float:right;"class="btn btn-outline-info btn-sm" id="reset-dark" onclick="resetDarkMode();">Reset Theme</button>
					<button style = "float:right;margin-right:5px;"class="btn btn-secondary btn-sm" id="toggle-dark" onclick="toggleDarkMode();">&#127769;</button>
				<?php } else { ?>
					<div style="text-align:center;">
						<button class="btn btn-secondary " id="toggle-dark" onclick="toggleDarkMode();">&#127769;</button>
						<br><br>
						<button class="btn btn-info " id="reset-dark" onclick="resetDarkMode();">Reset Theme</button>
					</div>
				<?php } ?>
				<script>
					//Dark mode button icon on page load.
					if( //custom localStorage setting
						localStorage.getItem("dark_mode")==="true"
						//Automatic mode
						||(((new Date).getHours() < 6 || (new Date).getHours() > 18) && localStorage.getItem("dark_mode") === null)
					) {
						document.getElementById("toggle-dark").innerText = "☀️";
					}
				</script>
				<?php if (isset($userResults) && !empty($_SESSION["loggedin"])&&$_SESSION['loggedin']===true) { ?>
					<label class="switch" style="float:left;">
						<input type="checkbox" name="accept" id="check" value="yes">
						<span class="slider round"></span>
					</label>
					<div id="info" class="noselect">&nbsp;&nbsp;Two Factor Auth.</div>
					<br>
					<?php 
					if($userResults["tfaen"] == 1) { 
					?>
						<script>
							document.querySelector('input[type="checkbox"]').checked = true;
						</script>
					<?php
						echo
						"
							<script>
								function copySecret() {
									var copyText = document.getElementById(\"copy\").innerText;
									var elem = document.createElement(\"textarea\");
									document.body.appendChild(elem);
									elem.value = copyText;
									elem.select();
									elem.setSelectionRange(0, 99999); /* For mobile devices */
									navigator.clipboard.writeText(elem.value);
									alert(\"Copied Secret: \" + copyText+\"\\nPaste into your authenticator app.\");
									document.body.removeChild(elem);
								}
							</script>				
							<div id=\"to-hide\" style=\"display:block;\">		
								<br>
								<div id=\"two_factor_div\">
									2FA Secret: <b id=\"copy\">".decrypt($userResults['tfa'])."</b> &nbsp;
									<button class = \"btn btn-outline-info btn-sm\" onclick=\"copySecret();\">📋</button>
									<br><br>
									<p>Keep this secret somewhere safe in case you lose access to your authenticator app.</p>
								</div>
							</div>
						";
					} 
					?>
					<div id="two_factor_response"></div>
					<br>
					<a class="btn btn-outline-danger" href="delete_confirmation">Delete Account</a>
					<br><br>
					<a href="../client/reset-password" class="btn btn-outline-primary">Reset Password</a>
					<br><br>
				<?php } ?>
				<?php 
				if(!empty($_SESSION['username'])&& !empty($_SESSION["loggedin"])&&$_SESSION['loggedin']===true) { 
				?>
					<a class="btn btn-outline-warning" id="clear-searches" href="#">Clear Search History</a>
					<br>
					<div id="clear_response"></div>
				<?php } ?>
				<br><br>
				<?php 
				if(!empty($_SESSION['loggedin'])&&$_SESSION['loggedin']===true) {
				?>
					<div style="text-align:center;">
						<a href="../backend/logout" class="btn btn-secondary" value="Submit" >Sign Out</a>
					</div>
				<?php } else { ?>
					<div style="text-align:center;">
						<a href="../login" class="btn btn-secondary" value="Submit" >Back to Homepage</a>
					</div>
				<?php } ?>
				<br>
			</div>
		</div> 
		<br><br><br><br>
		<footer id="footer">
			<a href="../login" class="logo">
				<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github" >&nbsp;GitHub</i></a>
			</div>
		</footer>
		<script src="../ajax/clearSearchesAJAX.js"></script>
	</body>
</html>