<?php
require_once('vendor/autoload.php');
require_once('secret-keys.php');

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$statuses = $twitter->load(Twitter::ME);
echo $statuses;
?>
