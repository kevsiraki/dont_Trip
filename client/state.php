<?php 
require_once '../backend/config.php';
require_once '../backend/geolocation.php';
require_once '../backend/middleware.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Popular in <?php echo $state ?></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<link href="../style/footer.css" rel="stylesheet">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>
		<script src="../ajax/stateAJAX.js"></script>	
	</head>
	<body>
		<header class="topnav" id="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars" id="burger"></i>
			</a>
			<div id="myLinks">
				<a href="dt" class="navlink">Itinerary Planner</a>
				<a href="state" class="navlink currentPage">Popular In <?php echo $stateFull ?></a>
				<a href="searches" class="navlink">Your Searches</a>
				<a href="settings" class="navlink">Account Settings</a>
			</div>
		</header>
		<br>
		<h1 id="darkable" class="darkable-text">Popular in <?php echo $stateFull ?></h1>
		<br>
		<div id = "container">
			<div id="sidebar" class = "rust">
				<h1 id="underline">Destinations</h1>
				<br>
				<ul id = "destinations"></ul>
			</div>
			<div id="sidebar" name = "rust" class = "rust">
				<h1 id="underline" class="darkable-text">Keywords</h1>
				<br>
				<ul id = "keywords"></ul>
			</div>
		</div>
		<footer id="footer">
			<a href="../login" class="logo">
				<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link"><i class="fa fa-github" >&nbsp;GitHub</i></a>
			</div>
		</footer>
	</body>
</html>