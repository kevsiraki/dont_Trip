<?php 
require_once '../backend/config.php';
require_once '../backend/geolocation.php';
require_once '../backend/middleware.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-title" content="Don't Trip">
		<link rel="apple-touch-icon"  sizes="256x256" href="../icons/icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../../favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../../favicon-16x16.png">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="manifest" href="../../site.webmanifest">
		<script src="../../app.js"></script>
		<link rel="mask-icon" href="../../safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="description" content="An itinerary planner utilizing the Google Maps API to give you customized places along a route!">
		<title>Search History</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous" defer></script>
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
        <link rel="preload" href="../style/navbar.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<link rel="preload" href="../style/footer.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<script src="../js/nav.js" defer></script>
		<script src="../js/lightMode.js"></script>	
		<script src="../ajax/searchesDisplayAJAX.js" defer></script>
	</head>
	<body>
		<header class="topnav" id="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars" id="burger"></i>
			</a>
			<div id="myLinks">
				<a href="dt" class="navlink">Itinerary Planner</a>
				<a href="state" class="navlink">Popular In <?php echo $stateFull ?></a>
				<a href="searches" class="navlink currentPage">Your Searches</a>
				<a href="settings" class="navlink">Account Settings</a>
			</div>
		</header>
		<br>
		<h1 id="darkable" class="darkable-text">Past Searches</h1>
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
				<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40" alt="Don't Trip" loading="lazy"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link" rel="noopener"><i class="fa fa-github"></i>&nbsp;GitHub</a>
			</div>
		</footer>
	</body>
</html>