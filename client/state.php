<?php require "../backend/state_backend.php"; ?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="../js/nav.js"></script>
		<script src="../js/subPageLightMode.js"></script>	
		<title>Popular in <?php echo $state ?></title>
	</head>
	<body>
		<div class="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars"></i>
			</a>
			<div id="myLinks">
				<a href="../client/searches.php">Your Searches</a>
				<a href="../client/settings.php">Account Settings</a>
				<a href="../client/dt.php">Back to Don't Trip</a>
			</div>
		</div>
		<br>
		<h1 id="darkable">Popular in <?php echo $stateFull ?></h1>
		<br>
		<div id = "container">
			<div id="sidebar">
				<h1 id="underline">Destinations</h1>
				<br>
				<ul>
					<?php
					while($rows = mysqli_fetch_assoc($result)) {
					?>
						<li>
							<a href ="dt.php?destVal=<?php echo htmlspecialchars($rows["destination"]);?>"><?php echo htmlspecialchars($rows["destination"]);?></a>
						</li>
					<?php
					}
					?>
				</ul>
			</div>
			<div id="sidebar" name = "rust" >
				<h1 id="underline" name="keywords">Keywords</h1>
				<br>
				<ul>
					<?php
					while($rows2 = mysqli_fetch_assoc($result2)) {
						if(!empty($rows2["keyword"])) {
					?>
							<li>
								<a href ="dt.php?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>"> <?php echo htmlspecialchars($rows2["keyword"]);?></a>
							</li>
					<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</body>
</html>