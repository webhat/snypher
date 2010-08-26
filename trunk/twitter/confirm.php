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

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret);

try {
	$twitterObj->setToken($_GET['oauth_token'],$_GET['oauth_token_secret']);
//	echo $_GET['oauth_token']. "--". $_GET['oauth_token_secret'] ."<br>";
	$token = $twitterObj->getAccessToken();
	//$token = $twitterObj->getRequestToken();
	$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
//	echo $token->oauth_token ."--". $token->oauth_token_secret ."<br>";
} catch( Exception $e ) {
	echo "Error: ". $e->getMessage();
	exit;
}

// save to cookies
//setcookie('oauth_token', $token->oauth_token);
//setcookie('oauth_token_secret', $token->oauth_token_secret);
// remove from cookies
//setcookie('oauth_token', "", time() - 3600);
//setcookie('oauth_token_secret', "", time() - 3600);


//echo "XXX: ". $_GET['oauth_token'] ." === ". $token->oauth_token ."\r\n<br/>";

#$ret = $twitterObj->post_statusesUpdate(array('status' => 'Playing with my application...'));

$twitterInfo= $twitterObj->get_accountVerify_credentials();
//echo "<h1>Your twitter username is {$twitterInfo->screen_name} and your profile picture is <img src=\"{$twitterInfo->profile_image_url}\"></h1><p><a href=\"random.php\">Go to another page and load your friends list from your cookie</p>";


include_once("../database.php");

$sql = "SELECT id FROM `socialmedia` WHERE `photographers_id` = '". $_SESSION['photog_id'] ."'";
if(!$req_res_pi = mysql_query($sql, $db)) {
	echo "E1: ". mysql_errno($db) . ": " . mysql_error($db). "\r\n<br\>";
}

$res_pi = mysql_fetch_array($req_res_pi);

if($res_pi != NULL) {
//	$sql = "UPDATE socialmedia SET `username`='{$twitterInfo->screen_name}', `oauth_token`='".$_GET['oauth_token']."', `oauth_token_secret`='".$_GET['oauth_token_secret']."' WHERE `photographers_id`='". $_SESSION['photog_id'] ."'";
	$sql = "UPDATE socialmedia SET `username`='{$twitterInfo->screen_name}', `oauth_token`='{$token->oauth_token}', `oauth_token_secret`='{$token->oauth_token_secret}' WHERE `photographers_id`='". $_SESSION['photog_id'] ."'";
} else {
//	$sql = "INSERT INTO socialmedia (service,photographers_id,username,oauth_token,oauth_token_secret) VALUES ('twitter','". $_SESSION['photog_id'] ."','{$twitterInfo->screen_name}','".$_GET['oauth_token']."','".$_GET['oauth_token_secret']."')";
	$sql = "INSERT INTO socialmedia (service,photographers_id,username,oauth_token,oauth_token_secret) VALUES ('twitter','". $_SESSION['photog_id'] ."','{$twitterInfo->screen_name}','{$token->oauth_token}','{$token->oauth_token_secret}')";
}

if(!$req_res_pi = mysql_query($sql, $db)) {
	echo "E2: ". mysql_errno($db) . ": " . mysql_error($db). "\r\n<br\>";
}

//echo var_export($twitterObj,true) ."<br/>";
//echo var_export($ret,true) ."<br/>";

if($db != ""){
	mysql_close($db);
}

//echo var_export($token,true);

//echo "HEADER:\r\n<br\>";

//echo("location: {$ret_loc}");
header("location: {$ret_loc}");
exit;


?>
