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
		<link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet'>
		<script src="../js/lightMode.js"></script>
		<style>
			h2 {
				font-family: 'Comfortaa';font-size: 35px;
			}
			#status {
				display: none;
				text-align: center;
				width: 90%;
			}
			.btn {
				width: 40%;
			}
			.alert-dark {
				display: none;
				text-align: center;
				width: 60%;
				border-radius: 25px;
				border-color: rgba(45, 48, 51, 0.3);
				background: rgba(45, 48, 51, 0.3);
				background-color: rgba(45, 48, 51, 0.3);
			}
			.wrapper {
				text-align: center;
			}
		</style>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				getState();
			});
			window.setInterval(function() {
				getState();
			}, 30000);
			function setState(action, alertClass) {
				fetch('https://donttrip.org/donttrip/backend/arduino_backend?&action='+action)
				.then((response)=>response.json())
				.then(led => {
					$('#status').html(led.LED_UPDATE); 
					$('#status').attr('class', alertClass); 
					$('#status').show(); 
					if(led.Red) {
						$('#states').html('<span style="color:red">Red LED: '+led.Red+'</span> <br><span style="color:blue">Blue LED: '+led.Blue + '</span><span style="color:#CA4C31"><br>Orange LED: '+led.Orange+'</span>'); 
						$('#states').show(); 
					}
				});
			}
			function getState() {
				fetch('https://donttrip.org/donttrip/backend/arduino_backend?action=state')
				.then((response)=>response.json())
				.then(led => {
					if(led.Red) {
						$('#states').html('<span style="color:red">Red LED: '+led.Red+'</span> <br><span style="color:blue">Blue LED: '+led.Blue + '</span><span style="color:#CA4C31"><br>Orange LED: '+led.Orange+'</span>'); 
						$('#states').show(); 
					}
				});
			}
		</script>
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
			<h2 id="info-two" class="darkable-text">LED Controls</h2>
			<br>
			<div id="status" class="center alert"></div>
			<div id="states" class="center alert alert-dark"></div>
			<br>
			<a class="btn btn-danger" onclick="setState('RH','center alert alert-danger');">Red On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary" onclick="setState('RL','center alert alert-danger');" >Red Off</a>&nbsp;&nbsp;<br><br>
			<a class="btn btn-primary" onclick="setState('BH','center alert alert-primary');" >Blue On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary" onclick="setState('BL','center alert alert-primary');" >Blue Off</a>&nbsp;&nbsp;<br><br>
			<a class="btn btn-warning" onclick="setState('OH','center alert alert-warning');" >Orange On</a>&nbsp;&nbsp;
			<a class="btn btn-secondary" onclick="setState('OL','center alert alert-warning');" >Orange Off</a>&nbsp;&nbsp;<br><br>
		</div>
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