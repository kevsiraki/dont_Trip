<!DOCTYPE html>
<html lang="en">
	<head> 
		<meta charset="UTF-8">
		<meta content="initial-scale=1.0, user-scalable=no" name="viewport">
		<title>Welcome to nginx!</title>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="apple-touch-icon"  sizes="512x512" href="../icons/icon.png">
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
	</body>
</html>
<?php 
$nice_cookie = md5("cyrust"). "<sub>...If you figure out what this is, you get a cookie!</sub>";
echo
	"
		$nice_cookie
	";
?>
