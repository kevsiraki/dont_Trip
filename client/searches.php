<?php require "../backend/searches_backend.php"; ?>
<!DOCTYPE html>
<html>
	<head>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Search History</title>
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="../js/nav.js"></script>
		<script src="../js/lightMode.js"></script>	
		<script src="../js/searchesAJAX.js"></script>
	</head>
	<body>
		<div class="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars"></i>
			</a>
			<div id="myLinks">
					<a href="../client/state">Popular In <?php echo $stateFull ?></a>
					<a href="../client/settings">Account Settings</a>
					<a href="../client/dt">Back to Don't Trip</a>
			</div>
		</div>
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
						if(isset($rows['destination'])) {
					?>
							<li>																	
								<a href ="dt?destVal=<?php echo htmlspecialchars($rows["destination"]);?>"> <?php echo $res ; ?></a>
								<sub>&nbsp;</sub>
								<button data-id= "<?php echo htmlspecialchars($rows["destination"]);?>" value="<?php echo htmlspecialchars($rows["destination"]);?>" type="button" class="deleteDest btn-close btn-sm" aria-label="Close" style="float:right;margin-top:9px"></button>
							</li>
					<?php
						}
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
						if(isset($rows2["keyword"])) {
					?>
							<li>
								<a href ="dt?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>"> <?php echo $key;?></a>
								<sub>&nbsp;</sub>
								<button data-id="<?php echo htmlspecialchars($rows2["keyword"]);?>" value="<?php echo htmlspecialchars($rows2["keyword"]);?>" type="button" class="deleteKey btn-close btn-sm" aria-label="Close" style="float:right;margin-top:9px"></button>
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