<?php 
	require_once "../backend/dt_backend.php"; 
	require_once "redirect.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Don't Trip</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/style.css" rel="stylesheet">
		<link href="../style/autofill.css" rel="stylesheet">
        <link href="../style/navbar.css" rel="stylesheet">
        <link href="../style/footer.css" rel="stylesheet">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>
	</head>
	<body>
		<?php
		if(isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]===true) {
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
		<div id="map"></div>
		<div id="dragbar">&#x21CA;</div>
		<div id="container" >
			<div id="sidebar" class = "rust">
				<h5 id="darkable" class = "darakble-text"><span id="total"></span></h5>
				<button class = "btn btn-link btn-sm" id="clear" style="margin-right: auto; margin-left: 0;display:none;">Clear Previous Routes</button>
				<div id="panel"></div>
				<audio id="audio" src=<?php echo $audio; ?> autostart="0" autostart="false" preload ="none" ></audio>
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
		<input type="hidden" id="api_key" name="api_key" value="<?php echo $gmaps_api_key?>">
		<input type="hidden" id="countryCode" name="countryCode" value="<?php echo $countryCode?>">
		<script src="../js/bundle.js"></script>
		<script src="../js/mobile_dragbar.js"></script>
		<script src="../js/keywords.js"></script>
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