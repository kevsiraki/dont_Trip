<?php
date_default_timezone_set('America/Los_Angeles');
$date = date("Y-m-d H:i:s");

$expired = 0;

if (isset($_GET["key"]) && isset($_GET["token"]))
{
    $sql = "SELECT * FROM users WHERE email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $_GET["key"];
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userResults = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
    $email = trim($_GET["key"]);
    $key = $_GET["token"];
    $curDate = $date;
    $sql = "SELECT * FROM password_reset_temp WHERE keyTO = ? AND email = ? ;";
    if ($stmt = mysqli_prepare($link, $sql))
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_key, $param_email);
        $param_key = $key;
        $param_email = $email;
        if (mysqli_stmt_execute($stmt))
        {
            $result = mysqli_stmt_get_result($stmt);
            $array = mysqli_fetch_assoc($result);
            $row = mysqli_num_rows($result);
            if ($row == "")
            {
                $expired = 1;
            }
            else
            {
                $row = $array;
                $expDate = $row['expD'];
                if ($expDate < $curDate)
                {
					$innerSql = "DELETE FROM password_reset_temp WHERE email = ?";
					if ($innerStmt = mysqli_prepare($link, $innerSql))
					{
						mysqli_stmt_bind_param($innerStmt, "s", $param_email);
						$param_email = $email;
						mysqli_stmt_execute($innerStmt);
						mysqli_stmt_close($innerStmt);
					}
                    $expired = 1;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}
else
{
    $expired = 1;
}
?>