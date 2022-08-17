<?php 
require_once 'backend/redirect_backend.php';
require_once 'backend/middleware.php';
include 'backend/php-csrf.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Don't Trip">
		<title>Don't Trip Login</title>
		<meta name="description" content="An itinerary planner utilizing the Google Maps API to give you customized places along a route!">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="style/form_style.css">
		<link rel="stylesheet" href="style/form_base_style.css">
		<link rel="stylesheet" href="style/header.css">
		<link rel="stylesheet" href="style/footer.css">
		<link href="icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="icons/icon.png">
		<script src="ajax/loginAJAX.js"></script>
		<script src="js/lightMode.js"></script>
		<script>
			if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
				window.location.reload();
			}
		</script>
        <style> 
			#footer { 
				width: 100%;
			} 
			#space {
				height: 90%;
			}
		</style> 
	</head>
	<body>
		<header class="header" id="header">
			<a href="." class="logo">
				<img draggable="false" src="icons/icon_header.png" width="40" height="40"></img>
			</a>
			<div class="header-right">
				<a href="." class="active"><small>Login</small></a>
				<a href="client/register"><small>Sign Up</small></a>
			</div>
		</header>
		<div class="wrapper">
			<h2><img draggable="false" src="icons/dont_Trip.png" class="center"  width="300" height="80" /></img></h2>
			<a href="https://github.com/kevsiraki/dont_Trip"><sub><i><small style ="float: right !important;">The better way to travel</small></i></sub></a>
			<br>
			<div id="invalid-login" class="center alert alert-danger"style="text-align:center; width: 90%; display:none; margin-bottom:30px !important;"> </div>
			<div id = "successful">
			<?php 
			if(isset($_GET["message"])&& !isset($_SESSION['message_shown']))
			{
				$msg_success = $_GET["message"];
				$_SESSION['message_shown'] = 1;
				echo 
				'
				<div class="center alert alert-success"style="text-align:center; width: 90%; margin-bottom:30px !important; ">'.$msg_success.'
					<span style = "float:right;margin-right:-12px;margin-top:-12px;"> &#215; </span>
				</div>
				';
			}
			?>
			</div>
			<div class="form-group">
				<input type="text" id="username" name="username"  class="center form-control" required>
				<div class="field-placeholder"><span>Username or E-mail</span></div>
			</div>
			<div class="form-group" style="margin-top:20px;margin-bottom:1px !important;">
				<input type="password" id="password" name="password" class="center form-control" required>
				<div class="field-placeholder"><span>Password</span></div>
				<span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
			</div>
			<span><small><a href="client/fp" style = "float:right;margin-right:5%;">Forgot?</a></small></span>
			<br>
			<button type="button" style = "margin-top:10px;" id="log-in" onclick="this.blur();" class="center btn btn-success">Login</button>
			<br>
			<p id="other" class="other">&nbsp;&nbsp;Alternatives&nbsp;&nbsp;</p>
			<p>
				<div  style = "display: flex;justify-content: center; margin-top:15px; width:100%; ">
					<?php
					echo ($isAuth == "yes") ? "&nbsp;&nbsp;<a class=\"btn btn-link\" style=\"display: inline-block; color: white; background-color: #306998;\" href='".$client->createAuthUrl()."'><i class=\"fa-brands fa-google\">&nbsp;</i>Google</a>&nbsp;&nbsp;" : "";
					//echo "<a class=\"btn btn-link\" style=\"display: inline-block; color: white; background-color: #4267B2;\" href='client/facebook_bootstrap'><i class=\"fa fa-facebook\">&nbsp;</i>Facebook</a>&nbsp;&nbsp;";
					echo "<a class=\"btn btn-link\" style=\"display: inline-block; color: white; background-color: #4267B2;\" href='client/init-openId'><i class=\"fa fa-steam\">&nbsp;</i>Steam</a>&nbsp;&nbsp;";
					echo "<a class=\"btn btn-link\" style=\"color: white; background-color: #738ADB;\" href='client/init-oauth.php'><i class=\"fa-brands fa-discord\">&nbsp;</i>Discord</a>&nbsp;&nbsp;";
					?>
				</div>
			</p>
			<p id="other" class="other">&nbsp;&nbsp;Or&nbsp;&nbsp;</p>
			<p>
				<?php
				if(isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]===true && (empty($_SESSION["authorized"])||$_SESSION["authorized"] !== false)) {
					preg_match_all('/\(([A-Za-z0-9 ]+?)\)/', $_SESSION["username"], $out); 
					if(!empty($out[1][0]) && isset($out)) 
					{
						$logo = strtolower($out[1][0]);
						$class = "fab fa-{$logo}";
					}
					else
					{
						$class = "fa fa-user";
					}
					$login_type_logo = "<i class=\"".$class."\"></i>";
					echo "<a class=\"btn btn-link bg-success center\" style=\" color: white; \" href='client/dt.php'>".$login_type_logo." ".trim(preg_replace('/\[[^)]+\]/', '', preg_replace('/\([^)]+\)/',' '.'', $_SESSION['username'])))."'s Session</a>";
				}
				else {
					echo "<a class=\"btn btn-link center\" style=\" background-color: gray; color: white; \" href='client/dt.php'><i class=\"fa fa-user\">&nbsp;</i>Continue as Guest</a>";
				}
				?>
			</p>
			<span id="info" class="darkable-text">Need an account? <a href="client/register">Sign up here</a></span>
			<input type="hidden" id="csrf" name="csrf" value="<?php echo $csrf ?>">
		</div>
              
		<div id="space"></div>
		<footer id="footer">
			<a href="." class="logo">
				<img draggable="false" src="icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fab fa-github" >&nbsp;GitHub</i></a>
			</div>
		</footer>
		<script src="js/ayhPassword.js"></script>
	</body>
</html>