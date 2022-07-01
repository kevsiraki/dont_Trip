<?php 
    //echo 'testingrust';
?>
<html lang="en">
	<head>
		<!--<meta charset="UTF-8" http-equiv="refresh" content="300;url=../backend/logout.php"/> -->
		<title><?php echo $_GET['name'] ?></title>
		<link href="../icons/dt.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="../js/login.js"></script>
		<link href="../icons/icon.ico" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
		<style>
        /*ifelse - mj*/
        #bg {
            position: fixed;
            background: #c6cfea;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        #panel1 {
            position: relative;
            background: #f4f4f4;
            width: 80%;
            top: 20px;
            left: 50%;
            margin-left: -40%;
            margin-bottom: 30px;
            border-radius: 30px;
            box-shadow: 0px 0px 20px 0px #888888;
            overflow: hidden;
        }
        #placeImg {
            position: relative;
            width: 256px;
			height: 256px;
            display: block;
			margin-left: auto;
			margin-right: auto;
			border-radius: 5%;
            top: 0;
            left: 0;
            margin-bottom: 10px;
			margin-top: 10px;
            object-fit: cover;
			box-shadow: 0px 0px 50px 0px #888888;
        }
        #placeName {
            position: relative;
            width: 70%;
            height: 40px;
            top: 0;
            left: 5%;
            margin-bottom: 7px;
            font-size: 34px;
            text-align: left;
        }
        #placeDist {
            position: relative;
            width: 70%;
            height: 30px;
            top: 0;
            left: 6%;
            margin-bottom: 10px;
            font-size: 21px;
            text-align: left;
        }

        #panel2, #panel3, #panel4 {
            position: relative;
            background: #f4f4f4;
            width: 76%;
            top: 20px;
            left: 50%;
            margin-left: -38%;
            margin-bottom: 20px;
            border-radius: 18px;
            box-shadow: 0px 0px 20px 0px #888888;
            overflow: hidden;
        }
        #panel2 {
            width: 40%;
            margin-left: -20%;
            border-radius: 50px;
        }
        #panel3 {
            padding-bottom: 24px;
        }
        #panel4 {
            width: 70%;
            margin-left: -35%;
            margin-bottom: 50px;
        }
        
        #placeInfoTitle {
            position: relative;
            width: 90%;
            height: 30px;
            top: 12px;
            left: 5%;
            margin-bottom: 12px;
            font-size: 22px;
            text-align: center;
        }
        #placeAddressTitle, #placePhoneTitle, #placeWebsiteTitle, #placeHoursTitle {
            position: relative;
            width: 90%;
            height: 20px;
            top: 8px;
            left: 8%;
            margin-bottom: 5px;
            font-size: 16px;
            text-align: left;
        }
        #placeHoursTitle {
            left: 5%;
            margin-top: 20px;
            margin-bottom: 0;
            font-size: 22px;
            text-align: center;
        }
        #placeAddress, #placePhone, #placeWebsite {
            position: relative;
            width: 88%;
            top: 8px;
            left: 5%;
            margin-bottom: 10px;
            line-height: 25px;
            font-size: 18px;
            text-align: left;
        }
        #placeRatingTitle, #placeStatusTitle {
            position: relative;
            width: 90%;
            height: 30px;
            top: 15px;
            left: 5%;
            margin-bottom: 12px;

            font-size: 22px;
            text-align: center;
        }
        #placeRating, #placeStatus {
            position: relative;
            color: #6bf342;
            width: 90%;
            top: 0px;
            left: 5%;
            margin-bottom: 5px;

            font-size: 45px;
            text-align: center;
        }
        #placeStatus {
            color: #bf6565;
            margin-bottom: 17px;
            font-size: 35px;
        }

        #placeHoursBox {
            position: relative;
            top: 3px;
            width: 80%;
            left: 10%;
            margin-bottom: 34px;
        }
        .placeHoursDayTitle {
			text-align: center;
            top: 8px;
            margin-top: 17px;
            margin-bottom: 5px;
            margin-left: auto;
			margin-right: auto;
            font-size: 22px;
           
        }
        .placeHoursDayTitleTime {
            position: relative;
            width: 200px;
            height: 20px;
            top: 8px;
            left: 50%;
            margin-left: -100px;
            margin-bottom: 5px;

            font-size: 16px;
            text-align: center;
        }
    </style>
	</head>	
	<body>
	<div id="bg"></div>
    <div id="panel1">
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
	<?php } ?>
  </body>
</html>
