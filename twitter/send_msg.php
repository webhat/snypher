<?php

session_start();

if( $_SESSION['photog_id'] == 0) {
	header("location: /snypherlogin.php");
	exit;
}

$ret_loc = $_SESSION['url'];

if(!empty($_POST['socmed_msg'])) {
	$socmed_msg = $_POST['socmed_msg'];
}
if(!empty($_GET['socmed_msg'])) {
	$socmed_msg = $_GET['socmed_msg'];
}
if(empty($socmed_msg)) {
//echo "Error: redirecting to /";
	header("location: /");
	exit;
}

include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';
include 'secret.php';
if(file_exists("../database.php"))
				include_once("../database.php");
if(file_exists("./database.php"))
				include_once("./database.php");

$sql = "SELECT oauth_token,oauth_token_secret FROM `socialmedia` WHERE `photographers_id` = '". $_SESSION['photog_id'] ."'";
if(!$req_res_pi = mysql_query($sql, $db)) {
	echo "E1: ". mysql_errno($db) . ": " . mysql_error($db). "\r\n<br\>";
}
$res_pi = mysql_fetch_array($req_res_pi);

$connection = new EpiTwitter($consumer_key, $consumer_secret);
try {
	$connection->setToken($res_pi['oauth_token'], $res_pi['oauth_token_secret']);
//	echo $res_pi['oauth_token']. "--". $res_pi['oauth_token_secret'] ."<br>";
//	$token = $connection->getAccessToken();
//	echo $token->oauth_token ."--". $token->oauth_token_secret ."<br>";
//	$connection->setToken($token->oauth_token, $token->oauth_token_secret);
} catch( Exception $e ) {
	echo "Error: ". $e->getMessage();
	exit;
}

//echo "'$socmed_msg'";
//$userInfo = $connection->get_accountVerify_credentials();

try {
	$ret = $connection->post_statusesUpdate(array('status' => $socmed_msg));
} catch( Exception $e ) {
//	echo "Error: ". $e->getMessage();
}

//header("location: {$ret_loc}");
//exit;

?>
