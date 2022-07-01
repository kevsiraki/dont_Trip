<?php
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
$username = $password = $confirm_password = $email = $ans = "";
$username_err = $password_err = $confirm_password_err = $email_err = $captcha_err = $ans_err = $ques_err = "";
$rows = 0;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else if (count(array_count_values(str_split(trim($_POST["username"])))) == 1) {
        $username_err = "Username cannot contain all the same character.";
    } else if (strlen(trim($_POST["username"])) < 8 || strlen(trim($_POST["username"])) > 25) {
        $username_err = "Username must have atleast 8 characters and not exceed 25.";
    } else {
        // Prepare a select statement
        $sql = "SELECT email FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        //validate email
        $sql2 = "SELECT username FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql2)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameters
            $param_email = trim($_POST["email"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                    $token = md5($_POST["email"]) . rand(10, 9999);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else if ($stmt = mysqli_prepare($link, $sql2)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            // Set parameters
            $param_email = trim($_POST["email"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else if (!(preg_match('/[A-Za-z]/', trim($_POST["password"])) && preg_match('/[0-9]/', trim($_POST["password"])) && preg_match('/[A-Z]/', trim($_POST["password"])) && preg_match('/[a-z]/', trim($_POST["password"])))) {
        $password_err = 'Password must contain a lowercase letter, uppercase letter, and a number.';
    } else if (strlen(trim($_POST["password"])) < 8 || strlen(trim($_POST["password"])) > 25) {
        $password_err = "Password must have atleast 8 characters and not exceed 25.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && $password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }
    //validate question
    $aQuestions = $_POST['questions'];
    if (empty(trim($_POST["answer"])) || !isset($aQuestions)) {
        $ans_err = "Please enter question and answer.";
    } else if (strlen(trim($_POST["answer"])) < 5 || strlen(trim($_POST["answer"])) > 25) {
        $ans_err = "Answer must have at least 5 characters and not exceed 25.";
    } else {
        $ans = trim($_POST["answer"]);
    }
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($ans_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, email_verification_link, ans, ques) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_email, $token, password_hash(strtolower($ans), PASSWORD_DEFAULT), $aQuestions[0]);
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
        $result = mysqli_query($link, "SELECT * FROM users WHERE email= '" . $_POST["email"] . "'");
        $row = mysqli_num_rows($result);
        $rows = $row;
        if ($row == 1) {
            //sends email
            $link2 = "<a href='https://donttrip.technologists.cloud/donttrip/client/verify-email.php?key=" . $_POST["email"] . "&token=" . $token . "'>Verify</a>";
            require "phpmail/src/Exception.php";
            require "phpmail/src/PHPMailer.php";
            require "phpmail/src/SMTP.php";
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
                $mail->addAddress($email, "new_user");
                $mail->Subject = "Verify your E-mail";
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
                $mail->Body = " " . $greeting . " . Click On This Link to Verify Email: " . $link2 . ". (Note: If this isn't you, ignore this email).";
            }
            catch(phpmailerException $e) {
                echo $e->errorMessage();
            }
            catch(Exception $e) {
                mysqli_query($link, "DELETE FROM users WHERE username = '".$username."';");
                header("location: ../client/register.php"); //Boring error messages from anything else!
                
            }
            if ($mail->Send()) {
                header("location: ../login.php");
            } else {
                echo "Mail Error - >" . $mail->ErrorInfo;
            }
        } else {
            mysqli_query($link, "DELETE FROM users WHERE username = '$username';");
            header("location: register.php"); //Boring error messages from anything else!
            
        }
    }
    mysqli_close($link);
}
?>