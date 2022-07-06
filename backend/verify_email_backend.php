<?php
include "config.php";
if ($_GET['key'] && $_GET['token']) {
    $email = $_GET['key'];
    $token = $_GET['token'];
    $query = mysqli_query($link, "SELECT * FROM users WHERE email_verification_link='" . $token . "' and email='" . $email . "';");
    $d = date('Y-m-d H:i:s');
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_array($query);
        if ($row['email_verified_at'] == NULL) {
            mysqli_query($link, "UPDATE users set email_verified_at ='" . $d . "' WHERE email='" . $email . "'");
            $msg = "Congratulations! Your email has been verified.";
        } else {
            $msg = "You have already verified your account with us.";
        }
    } else {
        $msg = "This email has not been registered with us.";
    }
} else {
    $msg = "UNKNOWN ERROR.";
}
?> 