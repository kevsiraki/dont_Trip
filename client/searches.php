<?php require "../backend/searches_backend.php"; ?>
<!DOCTYPE html>
<html>
	<head>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<link href="../style/search_style.css" rel="stylesheet">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link href="../style/navbar.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="../js/nav.js"></script>
		<script src="../js/subPageLightMode.js"></script>	
		<title>Search History</title>
	</head>
	<body>
		<div class="topnav">
			<a href="javascript:void(0);" class="active" onclick="myFunction()">
				<i class="fa fa-bars"></i>
			</a>
			<div id="myLinks">
					<a href="../client/state.php">Popular In <?php echo $stateFull ?></a>
					<a href="../client/settings.php">Account Settings</a>
					<a href="../client/dt.php">Back to Don't Trip</a>
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
						$res= $toSplit = $rows["destination"];
						$res .= nl2br("\n ");
						if(isset($rows['destination'])) {
					?>
							<li>
								<form type="POST"> 																		
									<a href ="dt.php?destVal=<?php echo htmlspecialchars($rows["destination"]);?>"> <?php echo $res ; ?></a>
									<sub class = "text-danger">&nbsp;</sub>
									<input type="hidden" name="toDelete" value="<?php echo htmlspecialchars($rows["destination"]);?>"><button name = "delete" value="<?php echo htmlspecialchars($rows["destination"]);?>" type="submit" class="btn-close btn-sm" aria-label="Close" style="float:right;margin-top:9px"></button>
								</form>
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
								<form type="POST"> 
									<a href ="dt.php?keyVal=<?php echo htmlspecialchars($rows2["keyword"]);?>"> <?php echo $key;?></a>
									<sub class = "text-danger">&nbsp;</sub>
									<input type="hidden" name="toDelete2" value="<?php echo htmlspecialchars($rows2["keyword"]);?>"><button name = "delete2" value="<?php echo htmlspecialchars($rows2["keyword"]);?>" type="submit" class="btn-close btn-sm" aria-label="Close" style="float:right;margin-top:9px"></button>
								</form>
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