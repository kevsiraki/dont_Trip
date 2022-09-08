<?php 
    //echo 'testingrust';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<!--<meta charset="UTF-8" http-equiv="refresh" content="300;url=../backend/logout"/> -->
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
		<title><?php echo $_GET['name'] ?></title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="this.onerror=null;this.href='../style/bootstrap.min.css';" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous" defer></script>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="preload" href="../style/place_info_style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<script src="../js/lightMode.js"></script>
	</head>	
	<body>
		<div id="bg"></div>
		<div id="panel1" class="panel">
			<button class = "btn btn-link btn-lg fa fa-close" style="color:orange; float:left;"onclick="window.top.close();"> Close</button>
			<br>
			<div id = "panel1wrapper">
				<?php if(isset($_GET['photo'])&&$_GET['photo']!=="undefined") { ?>
					<img id="placeImg" alt="<?php echo htmlspecialchars($_GET['name'])." Information."; ?>" src=<?php echo $_GET['photo']; ?> loading = "lazy"></img>
				<?php } ?>
				<div id="placeName"><?php echo htmlspecialchars($_GET['name']);?></div>
				<div id="placeDist"><?php echo $_GET['dist'];?> miles away</div>
			</div>
		</div>
		<div id="panel2" class="panel">
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
		<div id="panel3" class="panel">
			<div id="placeInfoTitle">Info about <?php echo $_GET['name'];?></div>
			<div id="placeAddress"><?php echo $_GET['address'];?></div>
			<?php if($_GET['phone']!=="undefined") { ?>
				<div id="placePhone"><?php echo $_GET['phone']; ?></div>
			<?php } ?>
			<?php if($_GET['website']!=="undefined" && (strpos($_GET['website'], 'undefined') === false) ) { ?>
				<a id="placeWebsite" target = "_blank" rel="noopener" href=<?php echo urldecode($_GET['website']);?> >Website</a>
			<?php } ?>
		</div>
		<?php if(isset($_GET['week'])||isset($_GET['status'])) { ?>
			<div id="panel4" class="panel">
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
					
					<div id="placeHoursBox">
						<div class="placeHoursDayTitle">
							<small><small><?php echo str_replace(',', "<br />", $_GET['week']);?></small></small>
						</div>
					</div>
				<?php } ?>
			</div>
			<br><br>
		<?php } ?>
	</body>
</html>