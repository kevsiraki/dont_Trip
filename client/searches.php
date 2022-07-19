<?php require "../backend/searches_backend.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Search History</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<link href="../style/footer.css" rel="stylesheet">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>	
		<script src="../js/searchesAJAX.js"></script>
		<script>
			function redirectTo(s,event) {
				window.location.href = s;
			}
			function no(event) {
				event.stopPropagation();
			}
		</script>
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
		<h1 id="darkable">Past Searches</h1>
		<br>
		<div id = "container">
			<div id="sidebar">
				<h1 id="underline">Destinations</h1>
				<br>
				<ul>
					<?php
					while($rows = mysqli_fetch_assoc($result)) {
						$res = $toSplit = $rows["destination"];
						$res .= nl2br("\n ");
					?>
						<li class="links" onclick="redirectTo('dt?destVal=<?php echo htmlspecialchars($rows["destination"]);?>');">																	
							<a class="link" href ="dt?destVal=<?php echo htmlspecialchars($rows["destination"]);?>"> <?php echo $res ; ?></a>
							<sub>&nbsp;</sub>
							<button onclick ="no(event);" data-id= "<?php echo htmlspecialchars($rows["destination"]);?>" value="<?php echo htmlspecialchars($rows["destination"]);?>" type="button" class="deleteDest btn-close btn-sm" aria-label="Close" style="float:right;margin-top:9px"></button>
						</li>
					<?php
						
					}
					?>
				</ul>
			</div>
			<div id="sidebar" name = "rust">
				<h1 id="underline" name="keywords">Keywords</h1>
				<br>
				<ul>
					<?php
					while($rows2 = mysqli_fetch_assoc($result2)) {
						$key = nl2br($rows2['keyword']);
						$key .= nl2br("\n ");
					?>
						<li class="links" onclick="redirectTo('dt?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>');">
							<a class="link" href ="dt?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>"> <?php echo $key;?></a>
							<sub>&nbsp;</sub>
							<button onclick ="no(event);" data-id="<?php echo htmlspecialchars($rows2["keyword"]);?>" value="<?php echo htmlspecialchars($rows2["keyword"]);?>" type="button" class="deleteKey btn-close btn-sm" aria-label="Close" style="float:right;margin-top:9px"></button>
						</li>
					<?php
						
					}
					?>
				</ul>
			</div>
		</div>
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