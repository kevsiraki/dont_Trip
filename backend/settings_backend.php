<?php
header("Content-Type: text/html");
ini_set('allow_url_fopen', 'On');
require_once "config.php";
require_once 'helpers.php';
require_once 'geolocation.php';
require_once 'vendor/sonata-project/google-authenticator/src/FixedBitNotation.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticatorInterface.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php';
require_once 'vendor/sonata-project/google-authenticator/src/GoogleQrUrl.php';
if (!isset($_SESSION))
{
    session_start();
}
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
}
else if (!empty($_SESSION["authorized"]) && $_SESSION["authorized"] === false)
{
    header("location: ../login.php");
    die;
}
$response = '';
define("encryption_method", $_ENV["recovery_encryption"]);
define("key", $_ENV["recovery_key"]);
if (isset($_SESSION['username']))
{
    $sql = "SELECT * FROM users WHERE username = ? ;"; //Get user information.
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $_SESSION['username'];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}
//AJAX request to enable/disable 2FA
if (isset($_POST['two_factor']) && !empty($userResults))
{
    ob_start();
    if ($_POST['two_factor'] == "true" && $userResults["tfaen"] == 0)
    {
        ob_end_clean();
        $g = new \Google\Authenticator\GoogleAuthenticator();
        $secret = $g->generateSecret();
        $sql = "UPDATE users SET tfaen = 1 WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $userResults["username"];
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $sql = "UPDATE users SET tfa = ? WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_secret, $param_username);
            // Set parameters
            $param_secret = encrypt($secret);
            $param_username = $userResults["username"];
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $url = \Google\Authenticator\GoogleQrUrl::generate(urlencode($userResults["username"]) , urlencode($secret) , urlencode("Don't-Trip"));
        $response = "
						<script>
							function copySecret() {
								var copyText = document.getElementById(\"copy\").innerText;
								var elem = document.createElement(\"textarea\");
								document.body.appendChild(elem);
								elem.value = copyText;
								elem.select();
								elem.setSelectionRange(0, 99999); /* For mobile devices */
								navigator.clipboard.writeText(elem.value);
								alert(\"Copied Secret: \" + copyText+\"\\nPaste into your authenticator app.\");
								document.body.removeChild(elem);
							}
						</script>
						<br>2FA Secret: <b id=\"copy\">{$secret}</b>&nbsp;
						<button class = \"btn btn-outline-info btn-sm\" onclick=\"copySecret();\">📋</button>
						<br><br>
						<p>1. Copy and paste the code above or scan the QR code below in your authenticator app of choice.</p>
						<p>2. Keep this secret somewhere safe in case you lose access to your authenticator app.</p>
						<p>3. The one-time code will refresh every 30 seconds and will be required for future logins/password resets</p>
						<img class=\"center\" src = \"{$url}\" alt = \"QR Code\" />
					";
    }
    else if ($_POST['two_factor'] == "false" && $userResults["tfaen"] == 1)
    {
        ob_end_clean();
        $response = "<br>2FA Disabled.";
        $sql = "UPDATE users SET tfaen = 0 WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $userResults["username"];
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        $sql = "UPDATE users SET tfa = 0 WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $userResults["username"];
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    die($response);
}
//AJAX request to clear search history.
else if (isset($_POST['delete_searches']) && isset($_SESSION["username"]))
{
    $sql = "DELETE FROM searches WHERE username = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // Set parameters
        $param_username = $_SESSION['username'];
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    die;
}
?>