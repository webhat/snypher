<?php
include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';
include 'secret.php';

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);

echo '<a href="' . $twitterObj->getAuthenticateUrl() . '">Authenticate with Twitter</a><BR/>';
echo '<a href="' . $twitterObj->getAuthorizationUrl() . '">Authorize with Twitter</a>';
?>

