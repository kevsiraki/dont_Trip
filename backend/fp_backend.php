<?php
// Include config file
require_once "config.php";
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Define variables and initialize with empty values
$username = $email = $new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = $email_err = $username_err = "";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function imageUrl()
{
    return "https://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "../") + 1) . "donttrip/icons/dont_Trip.png";
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is valid
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an e-mail.";
		echo $email_err;
    } else {
        $email = trim($_POST["email"]);
		$sql = "SELECT * FROM users WHERE email = ? ;";
		if ($stmt = mysqli_prepare($link, $sql))
		{
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_email);
			// Set parameters
			$param_email = $email;
			// Attempt to execute the prepared statement
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$userResults = mysqli_fetch_assoc($result);
			mysqli_stmt_close($stmt);
		}
        if (mysqli_num_rows($result) == 0) {
            $email_err = "E-mail address invalid.";
			echo $email_err;
        } else {
            $email = trim($_POST["email"]);
        }
    }
    // Check if email is validated
    $expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
    $expDate = date("Y-m-d H:i:s", $expFormat);
    $key = md5(2418 * 2 + $email);
    $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
    $key = $key . $addKey;
    if (empty($email_err)) {
        $link2 = "<a href='https://donttrip.technologists.cloud/donttrip/client/forgot-password.php?key=" . $_POST["email"] . "&token=" . $key . "'>Reset Password</a>";
        require_once "phpmail/src/Exception.php";
        require_once "phpmail/src/PHPMailer.php";
        require_once "phpmail/src/SMTP.php";
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = "utf-8";
            $mail->IsSMTP();
			$mail->IsHTML();
            // enable SMTP authentication
            $mail->SMTPAuth = true;
            // email username
            $mail->Username = $_ENV['email'];
            // email password
            $mail->Password = $_ENV['password'];
            $mail->SMTPSecure = $_ENV['encryption'];
            // sets XXX as the SMTP server
            $mail->Host = $_ENV['host'];
            // set the SMTP port for the XXX server
            $mail->Port = $_ENV['port'];
            $mail->From = $_ENV['email']; 
            $mail->FromName = "WebMaster";
            $mail->addAddress($_POST["email"], $userResults["username"]);
            $mail->Subject = "Reset your Password";
            $mail->IsHTML(true);
            date_default_timezone_set("America/Los_Angeles");
            $date = date("Y-m-d H:i:s");
            $greeting = "";
            if (date('H') < 12) {
                $greeting = "Good morning";
            } else if (date('H') >= 12 && date('H') < 18) {
                $greeting = "Good afternoon";
            } else if (date('H') >= 18) {
                $greeting = "Good evening";
            }
			$html = file_get_contents('../email_templates/forgot_password.html');
			$html =  str_replace("{{USERNAME}}",$userResults["username"],$html);
			$html =  str_replace("{{IMGICON}}",imageUrl(),$html);
			$html =  str_replace("{{LINK}}","https://donttrip.technologists.cloud/donttrip/client/forgot-password.php?key=".$_POST["email"]."&token=".$key."",$html);
			$html =  str_replace("{{GREETING}}",$greeting,$html);
			$mail->Body = $html;
			//$mail->Body = " " . $greeting . ". Click On This Link to Reset Password: " . $link2 . ".  This link auto-expires in 24 hours.";
        }
        catch(phpmailerException $e) {
            echo $e->errorMessage();
        }
        catch(Exception $e) {
            echo 404; //Boring error messages from anything else!
        }
        if ($mail->Send()) {
			$sql = "DELETE FROM password_reset_temp WHERE email = ? ;";
			if ($stmt = mysqli_prepare($link, $sql))
			{
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_email);
				// Set parameters
				$param_email = $email;
				// Attempt to execute the prepared statement
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				$userResults = mysqli_fetch_assoc($result);
				mysqli_stmt_close($stmt);
			}
            $sql = "INSERT INTO password_reset_temp (email, keyTo, expD) VALUES (?,?,?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_key, $param_expDate);
                // Set parameters
                $param_email = $email;
                $param_key = $key;
                $param_expDate = $expDate;
                // Attempt to execute the prepared statement
                mysqli_stmt_execute($stmt);
                // Close statement
                mysqli_stmt_close($stmt);
            }
            mysqli_close($link);
            echo 1;
        } else {
            echo "Mail Error - >" . $mail->ErrorInfo;
        }
    }  
}
?>