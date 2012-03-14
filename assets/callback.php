<?php
/*
* This function grabs the $_GET data that Facebook Connect returns 
* when a user starts to log in via Facebook. Since CodeIgniter can't 
* handle $_GET data, this function takes the variables from Facebook
* and returns them to Codeigniter as segments.
*
* Facebook Connect overview:
* 1) User is sent to Facebook page which ensures they've authorized
*     GiftFlow to use their data. They're automatically redirected here.
* 2) When redirected here, Facebook attaches a code as $_GET data.
* 3) This code and a secret key are passed back to Facebook.
* 4) This script reads Facebook's response which most importantly
*     includes an authorization code and then sends it in the form of 
*     segments back to CodeIgniter.
*/
// If the code variable is set...
if(!empty($_GET['consumer_key']))
{
	// special code
	$key = $_GET['consumer_key'];
	$user_id = $_GET['usr_id'];
	$oauthtoken = str_replace("/","_",$_GET['oauth_token']);
	// Sets the URL
	//echo $url = 'http://localhost/giftflow2/assets/callback.php?consumer_key='.rawurlencode($key).'&usr_id='.intval($user_id).'&oauth_token='.$oauthtoken;die;
	// Sends the data to Facebook, saves output, parses output into array
	//parse_str(file_get_contents($url), $out);
	//$data = '';
	// Cycles through the entries in the data array and encodes them into segment string
	//foreach($out as $key=>$val)
		//$data .= $key.'/'.$val.'/';
	// Attaches segment string to URL
	// Redirects to CodeIgniter
	header("Location: http://localhost/giftflow/index.php/oauth/callback/$key/$user_id/$oauthtoken");
}
else
{
	echo "Invalid parameters.";
}