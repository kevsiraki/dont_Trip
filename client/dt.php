<?php 
	require "../backend/dt_backend.php"; 
	require "redirect.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Don't Trip</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/style.css" rel="stylesheet">
		<link href="../style/autofill.css" rel="stylesheet">
		<link href="../style/navbar.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="../js/nav.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<?php
		if(isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]===true) {
	?>
			<div class="topnav">
				<a href="javascript:void(0);" class="active" onclick="myFunction()">
					<i class="fa fa-bars"></i>
				</a>
				<div id="myLinks">
					<a href="../client/searches">Your Searches</a>
					<a href="../client/state">Popular In <?php echo $stateFull ?></a>
					<a href="../client/settings">Account Settings</a>
				</div>
			</div>
	<?php
		}
		else {
	?>
			<div class="topnav">
				<a href="javascript:void(0);" class="active" onclick="myFunction()">
					<i class="fa fa-bars"></i>
				</a>
				<div id="myLinks">
					<a href="../login">Homepage</a>
				</div>
			</div>
		<?php } ?>
	<body>
		<div id="map"></div>
		<div id="dragbar">&#9776;</div>
		<div id="container" >
			<div id="sidebar">
				<h5 id="darkable"><span id="total"></span></h5>
				<button class = "btn btn-link btn-sm" id="clear" style="margin-right: auto; margin-left: 0;display:none;">Clear Previous Routes</button>
				<div id="panel"></div>
				<audio id="audio" src=<?php echo $audio; ?> autostart="0" autostart="false" preload ="none" ></audio>
			</div>
			<div id="sidebar" name = "rust" style="display:none;">
				<h5 id="darkable" name="sort">Sort places by:</h5>
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
		<script src="../js/bundle.js"></script>
		<script src="../style/mobile_dragbar.js"></script>
		<script src="../js/keywords.js"></script>
	</body>
</html>