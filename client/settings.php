<?php require "../backend/settings_backend.php";?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Account Settings</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/navbar.css" >
		<link rel="stylesheet" href="../style/settings_style.css">
		<link rel="stylesheet" href="../style/footer.css">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>
		<script src="../js/twoFactorAJAX.js"></script>
        <style> 
        #footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
        } 
		</style> 
	</head>			
	<body>
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
		<br>
		<div class="center">
			<div class="wrapper">
				<img src="../icons/user.jpg" id="user-pic"/>
				<h3 id="usernav" style="float:left; ">&nbsp;&nbsp;<?php echo $_SESSION['username']; ?></h3>
				<button style = "float:right;"class="btn btn-outline-info btn-sm" id="reset-dark" onclick="resetDarkMode();">Reset</button>
				<button style = "float:right;margin-right:5px;"class="btn btn-secondary btn-sm" id="toggle-dark" onclick="toggleDarkMode();">&#127769;</button>
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
									2FA On. Secret: <b id=\"copy\">{$userResults['tfa']}</b>
									<button class = \"btn btn-outline-info btn-sm\" onclick=\"copySecret();\">📋</button>
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
				<a class="btn btn-outline-warning" id="clear-searches" href="#">Clear Search History</a>
				<br>
				<div id="clear_response"></div>
				<br><br>
				<div style="text-align:center;">
					<a href="../backend/logout" class="btn btn-secondary" value="Submit" >Sign Out</a>
				</div>
				<br>
			</div>
		</div>
              <br><br><br>
              	<footer id="footer">
		<a href="." class="logo">
			<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
		</a>
		<div class="footer-right">
			<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github" >&nbsp;Github</i></a>
		</div>
	</footer>
		<script src="../js/clearSearchesAJAX.js"></script>
	</body>
</html>