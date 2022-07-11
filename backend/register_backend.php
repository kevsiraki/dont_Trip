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
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
$row = 0;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Can only contain letters, numbers, and underscores.";
    } else if (count(array_count_values(str_split(trim($_POST["username"])))) == 1) {
        $username_err = "Cannot contain all the same character.";
    } else if (strlen(trim($_POST["username"])) < 8 || strlen(trim($_POST["username"])) > 25) {
        $username_err = "Must have atleast 8 characters and not exceed 25.";
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
	//Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
		// Prepare a select statement
        $sql = "SELECT username FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
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
                } 
				else if(!valid_email(trim($_POST["email"]))) {
					$email_err = "E-mail Address Invalid.";
				}
				else {
                    $email = trim($_POST["email"]);
                    $token = md5($_POST["email"]) . rand(10, 9999);
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
        $password_err = 'Must contain a lowercase letter, uppercase letter, and a number.';
    } else if (strlen(trim($_POST["password"])) < 8 || strlen(trim($_POST["password"])) > 25) {
        $password_err = "Must have atleast 8 characters and not exceed 25.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        if (empty($password_err) && $password != trim($_POST["confirm_password"])) {
            $confirm_password_err = "Password did not match.";
        }
		else if (empty($password_err)) {
			$confirm_password = trim($_POST["confirm_password"]);
		}
    }
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, email_verification_link) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_email, $param_token);
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_email = $email;
			$param_token = $token;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {} 
			else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
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
			$row = mysqli_num_rows($result);
			mysqli_stmt_close($stmt);
		}
        if ($row == 1) {
            //sends email
            $link2 = "<a href='https://donttrip.technologists.cloud/donttrip/client/verify-email.php?key=" . $_POST["email"] . "&token=" . $token . "'>Verify</a>";
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
                $mail->addAddress($email, $username);
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
                $mail->Body = $greeting . ".  Verify Your Email: " . $link2 . ".";
            }
            catch(phpmailerException $e) {
                echo $e->errorMessage();
            }
            catch(Exception $e) {
				$sql = "DELETE FROM users WHERE username = ? ;";
				if ($stmt = mysqli_prepare($link, $sql))
				{
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($stmt, "s", $param_usernamel);
					// Set parameters
					$param_username = $username;
					// Attempt to execute the prepared statement
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
                header("location: ../client/register.php"); //Boring error messages from anything else!
            }
            if ($mail->Send()) {
                header("location: ../login.php");
            } else {
                echo "Mail Error ->" . $mail->ErrorInfo;
            }
        } else {
            $sql = "DELETE FROM users WHERE username = ? ;";
			if ($stmt = mysqli_prepare($link, $sql))
			{
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "s", $param_usernamel);
				// Set parameters
				$param_username = $username;
				// Attempt to execute the prepared statement
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
            header("location: ../client/register.php"); //Boring error messages from anything else! 
        }
    }
    mysqli_close($link);
}
function valid_email($email) 
{
    if(is_array($email) || is_numeric($email) || is_bool($email) || is_float($email) || is_file($email) || is_dir($email) || is_int($email))
        return false;
    else
    {
        $email=trim(strtolower($email));
        if(filter_var($email, FILTER_VALIDATE_EMAIL)!==false) return $email;
        else
        {
            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
            return (preg_match($pattern, $email) === 1) ? $email : false;
        }
    }
}
?>