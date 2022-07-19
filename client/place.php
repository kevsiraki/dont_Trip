<?php 
    //echo 'testingrust';
?>
<html lang="en">
	<head>
		<!--<meta charset="UTF-8" http-equiv="refresh" content="300;url=../backend/logout"/> -->
		<title><?php echo $_GET['name'] ?></title>
		<link href="../icons/dt.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<link rel="stylesheet" href="../style/place_info_style.css">
		<link rel="stylesheet" href="../style/footer.css">
		<script src="../js/lightMode.js"></script>
		<style> 
			#footer { 
				position: fixed;
				padding: 10px 10px 0px 10px; 
				bottom: 0; 
				width: 100%; 
			} 
		</style> 
	</head>	
	<body>
		<div id="bg" ></div>
		<div id="panel1">
			<button class = "btn btn-link btn-lg fa fa-close" style="color:red; float:left;"onclick="window.top.close();"></button>
			<?php if(isset($_GET['photo'])&&$_GET['photo']!=="undefined") { ?>
				<img id="placeImg" src=<?php echo $_GET['photo']; ?>></img>
			<?php } ?>
			<div id="placeName"><?php echo htmlspecialchars($_GET['name']);?></div>
			<div id="placeDist"><?php echo $_GET['dist'];?> miles away</div>
		</div>
		<div id="panel2">
			<?php if($_GET['rating']!=="undefined") { ?>
				<div id="placeRatingTitle">Rating</div>
				<?php if($_GET['rating']<=3) { ?>
					<div id="placeRating"style="color:red;"><?php echo $_GET['rating']; ?>/5</div>
				<?php } 
				else if($_GET['rating']>=3&&$_GET['rating']<4) { ?>
					<div id="placeRating"style="color:orange;"><?php echo $_GET['rating']; ?>/5</div>
				<?php } 
				else {?>
					<div id="placeRating"><?php echo $_GET['rating']; ?>/5</div>
				<?php } ?>	
			<?php } ?>
		</div>
		<div id="panel3">
			<div id="placeInfoTitle">Info about <?php echo $_GET['name'];?></div>
			<div id="placeAddress"><?php echo $_GET['address'];?></div>
			<?php if($_GET['phone']!=="undefined") { ?>
				<div id="placePhone"><?php echo $_GET['phone']; ?></div>
			<?php } ?>
			<?php if($_GET['website']!=="undefined" && (strpos($_GET['website'], 'undefined') === false) ) { ?>
				<a id="placeWebsite" target = "_blank" href=<?php echo urldecode($_GET['website']);?> >Website</a>
			<?php } ?>
		</div>
		<?php if(isset($_GET['week'])||isset($_GET['status'])) { ?>
			<div id="panel4">
				<div id="placeStatusTitle">Status</div>
				<?php if(isset($_GET['status'])&&$_GET['status']!=="undefined") { ?>
					<?php if(trim($_GET['status'])=="Open") { ?>
						<div id="placeStatus" style="color: #6bf342;"><?php echo $_GET['status'];?></div>
					<?php } else { ?>
						<div id="placeStatus"><?php echo $_GET['status'];?></div>
					<?php } ?>
				<?php } ?>
				<?php if(isset($_GET['week'])&&$_GET['week']!=="undefined") { ?>
					<div id="placeHoursTitle">Hours</div>
					<br>
					<div id="placeHoursBox">
						<div class="placeHoursDayTitle">
							<?php echo str_replace(',', "<br />", $_GET['week']);?>
						</div>
					</div>
				<?php } ?>
			</div>
			<br><br>
		<?php } ?>
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