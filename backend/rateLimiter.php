<?php
require_once 'helpers.php';
if (!isset($_SESSION))
{
    session_start();
}
if (isset($_SESSION['LAST_CALL']))
{
    $last = $_SESSION['LAST_CALL'];
    $curr = date("Y-m-d h:i:s.u");
    if (compareMilliseconds($last, $curr, 250))
    {
        die(json_encode(["message" => "Wait..."]));
    }
}
$_SESSION['LAST_CALL'] = date('Y-m-d h:i:s.u');
?>