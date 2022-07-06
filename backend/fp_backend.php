<?php
//ini_set('display_errors', '1');
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    header('location: ../login.php');
    exit;
}

// Include config file
require_once "config.php";
require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Define variables and initialize with empty values
$username = $email = $new_password = $confirm_password = $ans = "";
$new_password_err = $confirm_password_err = $email_err = $username_err = $ans_err = "";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is valid
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
        $query = mysqli_query($link, "SELECT * FROM users WHERE email='" . $email . "'");
        if (!$query) {
            die('Error: ' . mysqli_error($link));
        }
        if (mysqli_num_rows($query) == 0) {
            $email_err = "Email not found.";
        } else {
            $email = trim($_POST["email"]);
        }
    }
    // Check if email is validated
    $sql3 = "SELECT * FROM users WHERE email = '" . $email . "' ";
    $result3 = mysqli_query($link, $sql3);
    $basics = mysqli_fetch_assoc($result3);
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
            $mail->addAddress($_POST["email"], "user");
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
            $mail->Body = " " . $greeting . ". Click On This Link to Reset Password: " . $link2 . ". 
				(Note: If this isn't you, ignore this email). This link auto-expires in 24 hours.";
        }
        catch(phpmailerException $e) {
            echo $e->errorMessage();
        }
        catch(Exception $e) {
            header("location: https://donttrip.technologists.cloud/donttrip/"); //Boring error messages from anything else!
        }
        if ($mail->Send()) {
            mysqli_query($link, "DELETE FROM password_reset_temp WHERE email = '" . $email . "' ");
            $sql = "INSERT INTO password_reset_temp (email, keyTo, expD) VALUES (?,?,?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_key, $param_expDate);
                // Set parameters
                $param_email = $email;
                $param_key = $key;
                $param_expDate = $expDate;
                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
            mysqli_close($link);
            header("location: ../login.php");
        } else {
            echo "Mail Error - >" . $mail->ErrorInfo;
        }
    }  
}
?>