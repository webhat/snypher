<?php

session_start();

if( $_SESSION['photog_id'] == 0) {
	header("location: /snypherlogin.php");
	exit;
}

$ret_loc = $_SESSION['url'];

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';
include 'secret.php';
include_once("../database.php");

$sql = "SELECT oauth_token,oauth_token_secret FROM `socialmedia` WHERE `photographers_id` = '". $_SESSION['photog_id'] ."'";
if(!$req_res_pi = mysql_query($sql, $db)) {
	echo "E1: ". mysql_errno($db) . ": " . mysql_error($db). "\r\n<br\>";
}
$res_pi = mysql_fetch_array($req_res_pi);

$connection = new EpiTwitter($consumer_key, $consumer_secret, $res_pi['oauth_token'], $res_pi['oauth_token_secret']);

$twitterInfo= $connection->get_statusesFriends();
echo "<h1>Your friends are:</h1><ul>";
foreach($twitterInfo as $friend) {
  echo "<li><img src=\"{$friend->profile_image_url}\" hspace=\"4\">{$friend->screen_name}</li>";
}
echo "</ul>";
?>
