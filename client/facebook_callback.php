<?php
session_start();

require_once "../backend/config.php";

$fb = new Facebook\Facebook(['app_id' => $_ENV['app_id'], 'app_secret' => $_ENV['app_secret'], 'default_graph_version' => 'v2.5', ]);

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET["error"]))
{
    header("location: ../login.php");
    exit;
}

try
{
    $accessToken = $helper->getAccessToken();
}
catch(Facebook\Exceptions\FacebookResponseException $e)
{
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    header("location: ../login.php");
    exit;
}
catch(Facebook\Exceptions\FacebookSDKException $e)
{
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    header("location: ../login.php");
    exit;
}

try
{
    // Get the Facebook\GraphNodes\GraphUser object for the current user.
    $response = $fb->get('/me?fields=id,name,email,first_name,last_name', $accessToken->getValue());

}
catch(Facebook\Exceptions\FacebookResponseException $e)
{
    // When Graph returns an error
    echo 'ERROR: Graph ' . $e->getMessage();
    header("location: ../login.php");
    exit;
}
catch(Facebook\Exceptions\FacebookSDKException $e)
{
    // When validation fails or other local issues
    echo 'ERROR: validation fails ' . $e->getMessage();
    header("location: ../login.php");
    exit;
}

$me = $response->getGraphUser();

//echo "Full Name: ".$me->getProperty('name')."<br>";
//echo "Email: ".$me->getProperty('email')."<br>";
//echo "Facebook ID: <a href='https://www.facebook.com/".$me->getProperty('id')."' target='_blank'>".$me->getProperty('id')."</a>";
$_SESSION["username"] = $me->getProperty('name') . "(FB)";
$_SESSION["loggedin"] = true;
header("location: dt");

?>
