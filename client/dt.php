<?php 
	require_once '../backend/config.php';
	require_once '../backend/helpers.php';
	require_once '../backend/geolocation.php';
	require_once '../backend/middleware.php';
	require_once 'redirect.php';
	//$wilson = json_decode(get_web_page("https://owen-wilson-wow-api.herokuapp.com/wows/random?results=5"));
	$audio = null;//$wilson[rand(0, 4)]->audio;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
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
		<title>Don't Trip</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="this.onerror=null;this.href='../style/bootstrap.min.css';" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous" defer></script>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link href="../style/style.css" rel="stylesheet">
        <link rel="preload" href="../style/autofill.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <link rel="preload" href="../style/navbar.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<link rel="preload" href="../style/footer.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<script src="../js/nav.js" defer></script>
		<script src="../js/lightMode.js"></script>
		<style>
			#map {
				display: flex;
				flex-direction: column;
				min-height: 100vh;
				min-height: 100%;
			} 
			#container {
				flex-grow:1 !important;
			}
		</style>
		<?php
		if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
		{	
			echo('
				<script>
					if(window.opener)
					{
						window.opener.postMessage("'.strtok($_SESSION["username"],'(').'", "https://www.donttrip.org/donttrip/login");
					}
				</script>
			');
		}
		?>
	</head>
	<body>
		<?php
		if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]===true) {
		?>
			<header class="topnav" id="topnav">
				<a href="javascript:void(0);" class="active" onclick="myFunction()">
					<i class="fa fa-bars" id="burger"></i>
				</a>
				<div id="myLinks">
					<a href="dt" class="navlink currentPage">Itinerary Planner</a>
					<a href="state" class="navlink">Popular In <?php echo $stateFull ?></a>
					<a href="searches" class="navlink">Your Searches</a>
					<a href="settings" class="navlink">Account Settings</a>
				</div>
			</header>
		<?php
		}
		else {
		?>
			<header class="topnav" id="topnav">
				<a href="javascript:void(0);" class="active" onclick="myFunction()">
					<i class="fa fa-bars" id="burger"></i>
				</a>
				<div id="myLinks">
					<a href="dt" class="navlink currentPage">Itinerary Planner</a>
					<a href="settings" class="navlink">Settings</a>
				</div>
			</header>
		<?php 
		} 
		?>
		<div id="map" style="height:100vh;"></div>
		<div id="dragbar" style="display:none;">&#x21CA;</div>
		<div id="container" style="height:0%;">
			<div id="sidebar" class = "rust">
				<h5 id="darkable" class = "darakble-text"><span id="total"></span></h5>
				<button class = "btn btn-link btn-sm" id="clear" style="margin-right: auto; margin-left: 0;display:none;">Clear Previous Routes</button>
				<div id="panel"></div>
				<audio id="audio" src=""<?//php echo $audio; ?> autostart="0" autostart="false" preload ="none" ></audio>
			</div>
			<div id="sidebar" name = "rust" class = "rust" style="display:none;">
				<h5 id="darkable" class = "darakble-text" name="sort">Sort places by:</h5>
				<span>
				<button type="button" class = "btn btn-link btn-sm" id = "sortA" >Name</button> 
				<button type="button" class = "btn btn-link btn-sm" id = "sortD" >Distance</button>
				</span>
				<br>
				<ul id="places"></ul>
				<button id="more" class="btn btn-secondary btn-block">Load more results</button>
			</div>	
		</div>
		<input type="hidden" id="api_key" value="<?php echo $_ENV['gmaps_api_key'] ?>">
		<input type="hidden" id="countryCode" value="<?php echo $countryCode?>">
		<input type="hidden" id="lat" value="<?php echo $lat?>">
		<input type="hidden" id="lon" value="<?php echo $lon?>">
		<script src="../js/bundle.js" async defer></script>
		<script src="../js/mobile_dragbar.js" async defer></script>
		<script src="../js/keywords.js" async defer></script>
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