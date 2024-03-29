<?php 
require_once '../backend/config.php';
require_once '../backend/middleware.php';
require_once '../backend/helpers.php';
require_once '../backend/geolocation.php';

define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);

if (isset($_SESSION['username']))
{
    $sql = "SELECT * FROM users WHERE username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $_SESSION['username'];
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}
?>
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
		<title>Account Settings</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.3/css/bootstrap.min.css" integrity="sha512-oc9+XSs1H243/FRN9Rw62Fn8EtxjEYWHXRvjS43YtueEewbS6ObfXcJNyohjHqVKFPoXXUxwc+q1K7Dee6vv9g==" crossorigin="anonymous" referrerpolicy="no-referrer" onerror="this.onerror=null;this.href='../style/bootstrap.min.css';" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://kit.fontawesome.com/4b68e7bba8.js" crossorigin="anonymous" defer></script>
		<link href="../icons/icon_header.png" rel="shortcut icon" type="image/x-icon">
		<link rel="stylesheet" href="../style/settings_style.css">
        <link rel="preload" href="../style/navbar.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<link rel="preload" href="../style/footer.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		<script src="../js/nav.js" defer></script>
		<script src="../js/lightMode.js"></script>
		<script src="../ajax/twoFactorAJAX.js" defer></script>

	</head>			
	<body class="d-flex flex-column justify-content-between">
		<?php if(!empty($_SESSION['loggedin'])&&$_SESSION['loggedin']===true) { ?>
			<header class="topnav" id="topnav">
				<a href="javascript:void(0);" class="active" onclick="myFunction()">
					<i class="fa fa-bars" id="burger"></i>
				</a>
				<div id="myLinks">
					<a href="dt" class="navlink">Itinerary Planner</a>
					<a href="state" class="navlink">Popular In <?php echo $stateFull ?></a>
					<a href="searches" class="navlink">Your Searches</a>
					<a href="settings" class="navlink currentPage">Account Settings</a>
				</div>
			</header>
		<?php } else { ?>
			<header class="topnav" id="topnav">
				<a href="javascript:void(0);" class="active" onclick="myFunction()">
					<i class="fa fa-bars" id="burger"></i>
				</a>
				<div id="myLinks">
					<a href="dt" class="navlink">Itinerary Planner</a>
					<a href="settings" class="navlink currentPage">Settings</a>
				</div>
			</header>
		<?php } ?>
			<div class="wrapper">
			<?php if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']===true) { ?>
				<div id="usernav-bg" style="width=90%;border-radius: 25px; background: rgba(211, 211, 211, 0.2);">
					<br>
					<?php 
					$login_type_logo = '';
					if(!empty($_SESSION['userData'])) { //Discord/Steam Profile Picture pull
						extract($_SESSION['userData']);
						if(isset($discord_id)) //Discord
						{
							$avatar_url = "https://cdn.discordapp.com/avatars/$discord_id/$avatar.jpg";
						}
						else //Steam
						{
							$avatar_url = $avatar;
						}
					?>
						<img id="user-pic" alt="Discord Avatar" src="<?php echo $avatar_url;?>" />
					<?php 
					}
					else if(!empty($_SESSION['googleAvatar'])) { //Google Profile Picture
					?>
						<img id="user-pic" alt = "Google Avatar" src="<?php echo $_SESSION['googleAvatar'];?>" />
					<?php 
					}
					else if(!empty($_SESSION['fbAvatar'])) { //Facebook Profile Picture
					?>
						<img id="user-pic" alt="Facebook Avatar" src="<?php echo $_SESSION['fbAvatar']["url"];?>" />
					<?php 
					} else if(empty($_SESSION['userData'])&&empty($_SESSION['googleAvatar'])&&empty($_SESSION['fbAvatar'])) {
					?>

						<img id="user-pic" alt="Don't Trip Avatar" src="../icons/icon.png" style="background-color:#A9A9A9;" />
					<?php 
					} if(!empty($_SESSION['userData'])||!empty($_SESSION['username'])) {
						preg_match_all('/\(([A-Za-z0-9 ]+?)\)/', $_SESSION["username"], $out); 
						if(!empty($out[1][0]) && isset($out)) 
						{
							$logo = strtolower($out[1][0]);
							$class = "fab fa-{$logo}";
						}
						else
						{
							$class = "fa fa-user";
						}
						$login_type_logo = "<i class=\"".$class."\"></i>";
					}
					?>
					<h4 id="usernav" class="darkable-text" style="padding: 20px;text-align:center;font-family: 'Courier New', monospace;">
						<?php echo $login_type_logo." ".strtok(substr($_SESSION['username'],0,16),'('); ?>
					</h4>
				</div>
			<?php } else { ?>
				<div id="usernav-bg" style="width=90%;border-radius: 25px; background: rgba(211, 211, 211, 0.3);">
					<br>
					<img id="user-pic" alt = "Guest Avatar" src="../icons/user.png" />
					<h4 id="usernav" class="darkable-text" style="padding: 20px;text-align:center;font-family: 'Courier New', monospace;">Guest</h4>
				</div>
				<br>
			<?php } ?>
				<br>
				<?php 
				if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']===true) {
				?>
					<button style = "float:right;"class="btn btn-outline-info btn-sm" id="reset-dark" onclick="resetDarkMode();">Reset</button>
					<button style = "float:right;margin-right:5px;"class="btn btn-secondary btn-sm" id="toggle-dark" onclick="toggleDarkMode();">&#127769;</button>
				<?php } else { ?>
					<div style="text-align:center;">
						<button class="btn btn-secondary " id="toggle-dark" onclick="toggleDarkMode();">&#127769;</button>
						<br><br>
						<button class="btn btn-info " id="reset-dark" onclick="resetDarkMode();">Reset</button>
					</div>
				<?php } ?>
				<?php if (isset($userResults) && !empty($_SESSION["loggedin"]) && $_SESSION['loggedin']===true) { ?>
					<label class="switch" style="float:left;">
						<input type="checkbox" name="accept" id="check" value="yes">
						<span class="slider round"></span>
					</label>
					<div id="info" class="noselect darkable-text">&nbsp;&nbsp;Two Factor Auth.</div>
					<br>
					<?php 
					if($userResults["tfaen"] == 1) { 
					?>
						<script>document.querySelector('input[type="checkbox"]').checked = true;</script>
						<div id="to-hide" style="display:block;">		
							<div id="two_factor_div" class="darkable-text">2FA Secret: <b id="copy"><?php echo decrypt($userResults['tfa']);?></b>&nbsp;
								<button class = "btn btn-outline-info btn-sm" onclick="copySecret(copy);">📋</button><br><br>
								<p>Keep this secret somewhere safe in case you lose access to your authenticator app.</p>
							</div>
						</div>
					<?php } ?>
					<div id="two_factor_response" class ="darkable-text"></div>
					<br>
					<a class="btn btn-outline-danger" href="delete_confirmation">Delete Account</a>
					<br><br>
					<a href="../client/reset-password" class="btn btn-outline-primary">Reset Password</a>
					<br><br>
				<?php } ?>
				<?php 
				if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']===true) { 
				?>
					<a class="btn btn-outline-warning" id="clear-searches" href="#">Clear Search History</a>
					<br>
					<div id="clear_response" class ="darkable-text"></div>
				<?php } ?>
				<br><br>
				<?php 
				if(!empty($_SESSION['loggedin']) && $_SESSION['loggedin']===true) {
				?>
					<div style="text-align:center;">
						<a href="../backend/logout" class="btn btn-secondary" value="Submit" >Sign Out</a>
					</div>
				<?php } else { ?>
					<div style="text-align:center;">
						<a href="../login" class="btn btn-secondary" value="Submit" >Back to Homepage</a>
					</div>
				<?php } ?>
				<br>
			</div>
		<footer id="footer">
			<a href="../login" class="logo">
				<img draggable="false" src="../icons/dont_Trip.png" width="150" height="40" alt="Don't Trip" loading="lazy"></img>
			</a>
			<div class="footer-right">
				<a href="https://github.com/kevsiraki/dont_Trip" target="_blank" id="footer-link" rel="noopener"><i class="fa fa-github"></i>&nbsp;GitHub</a>
			</div>
		</footer>
		<script src="../ajax/searchesDeleteAJAX.js"></script>
	</body>
</html>