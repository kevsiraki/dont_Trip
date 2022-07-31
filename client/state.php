<?php require_once "../backend/state_backend.php"; ?>
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
				<ul>
					<?php
					while($rows = mysqli_fetch_assoc($result)) {
					?>
						<li class="links" onclick="redirectTo('dt?destVal=<?php echo htmlspecialchars($rows["destination"]);?>');">
							<a class="link"  href ="dt?destVal=<?php echo htmlspecialchars($rows["destination"]);?>"><?php echo htmlspecialchars($rows["destination"]);?></a>
							<br>
							<sub><span class="bubble" id="bubble"><?php echo $rows["destCnt"]>1?$rows["destCnt"]." searches":$rows["destCnt"]." search"; ?></span></sub>
						</li>
					<?php
					}
					?>
				</ul>
			</div>
			<div id="sidebar" name = "rust" class = "rust">
				<h1 id="underline" class="darkable-text">Keywords</h1>
				<br>
				<ul>
					<?php
					while($rows2 = mysqli_fetch_assoc($result2)) {
					?>
						<li class="links" onclick="redirectTo('dt?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>');">
							<a class="link" href ="dt?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>"> <?php echo htmlspecialchars($rows2["keyword"]);?></a>
							<br>
							<sub><span class="bubble" id="bubble"><?php echo $rows2["keyCnt"]>1?$rows2["keyCnt"]." searches":$rows2["keyCnt"]." search"; ?></span></sub>
						</li>
					<?php
						
					}
					?>
				</ul>
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