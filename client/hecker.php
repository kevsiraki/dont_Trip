<?php ?>
<!DOCTYPE html>
<html lang="en">
	<head> 
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
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
		<title>Welcome to nginx!</title>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<style>
			body {
			width: 35em;
			margin: 0 auto;
			font-family: Tahoma, Verdana, Arial, sans-serif;
		}
		</style>
	</head>
	<body>
		<h1>Welcome to nginx!</h1>
		<p>If you see this page, the nginx web server is successfully installed and
		working. Further configuration is required.</p>
		<p>For online documentation and support please refer to
		<a href="http://nginx.org/">nginx.org</a>.<br/>
		Commercial support is available at
		<a href="http://nginx.com/">nginx.com</a>.</p>
		<p><em>Thank you for using nginx.</em></p>
		<?php echo md5("cyrust"). "<sub>...If you figure out what this is, you get a cookie!</sub>"; ?>
	</body>
</html>