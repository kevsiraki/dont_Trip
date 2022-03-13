<?php
    $placename = $_GET['n'];
    $placeid = $_GET['p'];
    $geometry = $_GET['geo'];
    $icon = $_GET['icon'];
    $website = $_GET['website'];
    $hours = $_GET['hours'];
    $rating = $_GET['rating'];
    $number = $_GET['number'];
    $address = $_GET['address'];
    $vicinity = $_GET['vicinity'];
    echo $placename.'<br>';
    //echo $placeid.'<br>';
    //echo $geometry.'<br>';
    //echo $icon.'<br>';
    echo $website.'<br>';
    echo $hours.'<br>';
    echo $rating.'<br>';
    echo $number.'<br>';
    //echo $address.'<br>';
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
	<link href="icons/dt.ico" rel="shortcut icon" type="image/x-icon">
	<meta charset="utf-8">
	<title><?php echo $placename;?></title>
	<link href="style.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="apple-touch-icon"  sizes="512x512" href="https://cdn-icons.flaticon.com/png/512/819/premium/819814.png?token=exp=1641170884~hmac=721b9b657a34997403340971a5367135">
	
</head>
<body>
	<div id="name"><?php 
	//echo $placename;
	?>
	</div>
	<div id="type"><?php 
	//echo $placename;
	?></div>
	<div id="container">
        
	</div>
	<script src="jquery.js"></script>
</body>
</html>
