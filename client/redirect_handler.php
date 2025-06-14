<?php
require_once '../backend/redirect_backend.php';
header('Location: ' . filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL)); 
?>