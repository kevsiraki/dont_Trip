<?php
//Callback Functions for Facebook API Requests
session_start();

require_once "config.php";

$fb = new Facebook\Facebook(['app_id' => $_ENV['app_id'], 'app_secret' => $_ENV['app_secret'], 'default_graph_version' => 'v2.5', ]);

$helper = $fb->getRedirectLoginHelper();
if (isset($_GET["error"]))
{
    header("location: ../login");
    die;
}

try
{
    $accessToken = $helper->getAccessToken();
}
catch(Facebook\Exceptions\FacebookResponseException $e)
{
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    header("location: ../login");
    die;
}
catch(Facebook\Exceptions\FacebookSDKException $e)
{
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    header("location: ../login");
    die;
}

try
{
    // Get the Facebook\GraphNodes\GraphUser object for the current user.
    $response = $fb->get('/me?fields=id,name,email,first_name,last_name,picture.width(800).height(800)&access_token=' . $accessToken->getValue() . '', $accessToken->getValue());
}
catch(Facebook\Exceptions\FacebookResponseException $e)
{
    // When Graph returns an error
    echo 'ERROR: Graph ' . $e->getMessage();
    header("location: ../login");
    die;
}
catch(Facebook\Exceptions\FacebookSDKException $e)
{
    // When validation fails or other local issues
    echo 'ERROR: validation fails ' . $e->getMessage();
    header("location: ../login");
    die;
}

$me = $response->getGraphUser();

$_SESSION["username"] = $me->getProperty('name') . " (Facebook)[" . $me->getProperty('id') . "]";
$_SESSION["loggedin"] = true;
$_SESSION["fbAvatar"] = $me->getProperty('picture');
$_SESSION['loginTime'] = time();
session_regenerate_id(true);

header("location: ../client/dt");
?>