<?php /*echo mj-if-else*/ ?>
<!DOCTYPE html>
<html lang="en">
	<head> 
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Sign Up</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/form_style.css">
		<link rel="stylesheet" href="../style/header.css">
		<link rel="stylesheet" href="../style/footer.css">
		<script src="../js/lightMode.js"></script>
		<script src="../js/generalAJAX.js"></script>
		<script src="../js/registerAJAX.js"></script>
		<link rel="stylesheet" href="../style/meter_styles.css">
		<style> 
        #footer { 
            bottom:-15%;
            width: 100%;
        } 
    </style> 
	</head>
	<body>
		<header class="header" id="header">
			<a href="../login" class="logo">
				<img draggable="false" src="../icons/icon_header.png" width="40" height="40"></img>
			</a>
			<div class="header-right">
				<a href="../login"><small>Login</small></a>
				<a href="."class="active"><small>Sign Up</small></a>
			</div>
		</header>
		<div class="wrapper">
	  		<h2><img draggable="false" src="../icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div class="info-bar" id="info-bar">Fill Out This Form to Sign-Up</div>
			<div id="invalid-signup" class="center alert alert-danger"style="text-align:center; width: 90%; display:none;"></div>
			<form>
				<input style="display:none">
				<input type="password" style="display:none" autocomplete="new-password"/>
				<input style="display: none" type="text" name="fakeusernameremembered" />
				<input style="display: none" type="password" name="fakepasswordremembered" />
				<div class="form-group">
					<input type="email" name="email" placeholder="E-mail Address" id="email" autocomplete="off" 
					onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" aria-describedby="emailHelp" class="center form-control" required>
					<div id="ename_response" style="text-align:center;"></div>
				</div>
				<div class="form-group">
					<input type="text" placeholder="Username" name="username" id="username" autocomplete="off" 
					onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" class="center form-control" required>
					<div id="uname_response" style="text-align:center;"></div>
				</div>
				<div class="form-group">
					<input type="password" placeholder="Password" name="password" id="password" 
						onkeyup="getPassword();getConfirmPassword();" onfocus="showMeter();showConfirmMeter();getPassword();getConfirmPassword();" 
						onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false"
						oninput="getPassword();getConfirmPassword();"autocomplete="new-password" class="center form-control" required>
				</div>
				<label style="margin-left:5%;">
					<input type="checkbox" onclick="showF();showMeter();showConfirmMeter();getPassword();getConfirmPassword();">
					<small id="info">Show</small>
				</label>
				<div id="password-strength" style="display: none;text-align:center;">
					<div id="length" class="pw-stength" ><small> At least 8 characters</small></div>
					<div id="lowercase" class="pw-stength"><small> At least 1 lowercase letter</small></div>
					<div id="uppercase" class="pw-stength"><small> At least 1 uppercase letter</small></div>
					<div id="number" class="pw-stength"><small> At least 1 number</small></div>
					<br>
				</div>
				<div class="form-group">
					<input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" 
					onfocus="showMeter();showConfirmMeter();getPassword();getConfirmPassword();" 
					onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false"
					oninput="getPassword();getConfirmPassword();"autocomplete="new-password" onkeyup="getPassword();getConfirmPassword();" class="center form-control" required>
				</div>
				<div id="confirm-password-strength" style="display: none;text-align:center;">
					<div id="matching" class="pw-stength"><small> Matching</small></div>
				</div>
                    <br> 
					<script src="../js/lightMode.js"></script>
					<button name="Submit" type="button" id="sign-up" class="center btn btn-success" onclick="this.blur();">Sign-Up</button>
					<br>
					<p id="info-two">Already have an account? <a href="../login">Login here</a></p> 
			</form>
		</div>
		<br>
		<br>
		<footer id="footer">
			<a href="." class="logo">
				<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github" >&nbsp;Github</i></a>
			</div>
		</footer>
	</body>
</html>