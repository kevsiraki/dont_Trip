<?php
require_once "../backend/config.php";
require_once '../backend/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Arduino Control Hub</title>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Arduino Control Panel">
		<link rel="apple-touch-icon"  sizes="256x256" href="../icons/icon.png">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous"></script>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/form_style.css">
		<link rel="stylesheet" href="../style/form_base_style.css">
		<link rel="stylesheet" href="../style/header.css">
		<link rel="stylesheet" href="../style/footer.css">
		<link rel="stylesheet" href="../style/arduino_style.css">
		<link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet'>
		<script src="../js/lightMode.js"></script>
		<script src="../js/arduino.js"></script>
		<style>
			iframe {
        	    width:100%;
        	    height:40vh;
        	    overflow:hidden;
        	    margin:0px;
        	    padding:0px;
        	    border:none;
        	}
    	</style>
	</head>
	<body class="d-flex flex-column justify-content-between">
		<header class="header" id="header">
			<a href="../login" class="logo">
			<img draggable="false" src="../icons/icon_header.png" width="40" height="40"></img>
			</a>
			<div class="header-right">
				<a href="../login"><small>Login</small></a>
				<a href="register"><small>Sign Up</small></a>
			</div>
		</header>
		
		<div class="wrapper">
			<h2 id="info-two" class="darkable-text">Control LEDs</h2>
			<br>
			<div id="status" class="center alert"></div>
			<div id="states" class="center alert alert-dark"></div>
			<br>
			<a class="btn btn-danger" onclick="setState('RH','center alert alert-danger');">Red On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary" onclick="setState('RL','center alert alert-secondary');" >Red Off</a>&nbsp;&nbsp;<br><br>
			<a class="btn btn-primary" onclick="setState('BH','center alert alert-primary');" >Blue On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary" onclick="setState('BL','center alert alert-secondary');" >Blue Off</a>&nbsp;&nbsp;<br><br>
			<a class="btn btn-warning" onclick="setState('OH','center alert alert-warning');" >Orange On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary" onclick="setState('OL','center alert alert-secondary');" >Orange Off</a>&nbsp;&nbsp;<br><br>
			<a class="btn  cbutton" onclick="setState('CH','center cbutton alert ');" >Tree On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary cobutton" onclick="setState('CL','center coalert alert alert-secondary');" >Tree Off</a>&nbsp;&nbsp;<br><br>
			<!--iframe src="https://donttrip.org/live/?action=stream"  scrolling="no"></iframe-->
		</div>
		<br>
		<footer id="footer">
			<a href="../login" class="logo">
			<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github"></i>&nbsp;GitHub</a>
			</div>
		</footer>
	</body>
</html>