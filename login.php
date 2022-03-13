<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
	<link href="icons/dt.ico" rel="shortcut icon" type="image/x-icon">
	<meta charset="utf-8">
	<title>Don't Trip</title>
	<link href="style.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="apple-touch-icon"  sizes="512x512" href="https://cdn-icons.flaticon.com/png/512/819/premium/819814.png?token=exp=1641170884~hmac=721b9b657a34997403340971a5367135">
</head>
<body>
	<div id="map"></div>
	<div id="dragbar"></div>
	<div id="container">
		<div id="sidebar" >
			<h2>Distance: <span id="total"></span></h2>
			<div id="panel"></div>
		</div>
		<div id="sidebar" name = "rust" >
			<h2>On Your Route</h2>
			<ul id="places"></ul><button id="more" class="btn btn-secondary btn-block">Load more results</button>
		</div>
	</div>
	<script src="bundle.js"></script>
	<script src="jquery.js"></script>
</body>
</html>