<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "config.php";
require_once "middleware.php";

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->type) && !empty($data))
{
    $what = $data->type;
    $exactly = $data->id;

    /* Deletes 1 destination search from the user's history by id. */
    if ($what == "destination")
    {
        $sql = "UPDATE searches SET destination = null WHERE id = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $exactly;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        die(json_encode(["message" => "Destination Deleted"]));
    } /* Deletes 1 destination keyword from the user's history by id. */
    elseif ($what == "keyword")
    {
        $sql = "UPDATE searches SET keyword = null WHERE id = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $exactly;
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        die(json_encode(["message" => "Keyword Deleted"]));
    } /* Deletes all searches from the user's history. */
    elseif ($what == "nuke")
    {
        $sql = "SELECT * FROM searches WHERE username = ? ;";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && !isset($_SESSION["userid"]))
            {
                $param_username = $_SESSION["username"];
            }
            else if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["userid"]))
            {
                $param_username = $_SESSION["userid"];
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $searches = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
        }
        if (!empty($searches))
        {
            $sql = "DELETE FROM searches WHERE username = ? ;";
            if ($stmt = mysqli_prepare($link, $sql))
            {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && !isset($_SESSION["userid"]))
                {
                    $param_username = $_SESSION["username"];
                }
                else if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["userid"]))
                {
                    $param_username = $_SESSION["userid"];
                }
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            die(json_encode(["message" => "Search History Cleared"]));
        }
        else
        {
            die(json_encode(["message" => "Search History Already Empty"]));
        }
    }
}
?>
