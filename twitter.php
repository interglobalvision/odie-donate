<?php
require_once('vendor/autoload.php');
require_once('secret-keys.php');

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

if (!$twitter->authenticate()) {
	die('Invalid name or password');
}

try {
	$statuses = $twitter->load(Twitter::ME);
  echo json_encode($statuses);
} catch (TwitterException $e) {
	echo "Error: ", $e->getMessage();
}
?>
