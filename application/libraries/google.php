<?php
if (!defined('BASEPATH'))
      exit('No direct script access allowed');
/**
 * 	Google Library
 *
 * 	@author Brandon Jackson
 */
class Google {
	
	/**
	*	CodeIgniter super object
	*	@var object
	*/
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		
		require_once $this->CI->config->item('base_path').'application/libraries/Google/apiClient.php';
	}
	
	/**
	*	Authenticate user with Google
	*/
	function link()
	{
		$apiClient = new apiClient();
		$apiClient->setScopes(array("https://www.google.com/m8/feeds"));
		$apiClient->authenticate();
	}
	
	/**
	*	Loads Google Data returning SimpleXMLElement
	*	Note: removes gd namespace
	*
	*	@param string $url
	*	@param array $params
	*	@return SimpleXMLElement
	*/
	function load($url,$params)
	{
		$url = $url ."?" . http_build_query($params);
		$string = file_get_contents($url);
		
		// Remove gd namespace
		$string = str_replace("gd:","",$string);
		
		// Create SimpleXMLElement
		$xml = new SimpleXMLElement($string);
		return $xml;
	}

	/**
	*	Get access token for use with authenticated API calls
	*/
	function fetch_access_token()
	{
		global $apiConfig;
		
		$refresh_token = $this->get_user_refresh_token();
		
		if(!$refresh_token)
		{
			redirect("account/link/google");
		}
		
		$params = array(
			"client_id"=>$apiConfig['oauth2_client_id'],
			"client_secret"=>$apiConfig['oauth2_client_secret'],
			"refresh_token"=>$refresh_token,
			"grant_type"=>"refresh_token"
		);
		$refresh_url = "https://accounts.google.com/o/oauth2/token";
		
		try
		{
			$conn = curl_init();
			curl_setopt($conn,CURLOPT_URL,$refresh_url);
			curl_setopt($conn,CURLOPT_POST,TRUE);
			curl_setopt($conn,CURLOPT_POSTFIELDS,http_build_query($params));
			curl_setopt($conn,CURLOPT_RETURNTRANSFER,TRUE);
			$result = curl_exec($conn);
			curl_close($conn);
		}
		catch(Exception $e)
		{
			show_error($e->getMessage());
		}

		$accessTokenObject = (json_decode($result));
		
		return $accessTokenObject->access_token;
	}
	
	/**
	*	Get fresh access token for use in authenticated API calls
	*/
	function get_user_refresh_token()
	{
		if($this->CI->session->userdata('user_id'))
		{
			$this->CI->load->library('datamapper');
			$U = new User($this->CI->session->userdata('user_id'));
		}
		
		if($U->google_token)
		{
			return $U->google_token;
		}
		return FALSE;
	}
	
	/**
	*	Get array of user's contacts' email addresses
	*	@return array
	*/
	function contacts_email_list()
	{
		// Load XML from API
		$base_url = "https://www.google.com/m8/feeds/contacts/default/full";
		$params = array(
			'max-results'=>1000,
			'v'=>'3.0',
			'access_token'=>$this->fetch_access_token()
		);
		$xml = $this->load($base_url,$params);

		// Array to store list of email addresses
		$list = array();
		
		// Iterate over the XML, adding each email address to $list
      	foreach( $xml->entry as $entry )
		{
			$list[] = (string) $entry->email["address"];
		}
		
		Console::logSpeed("Openauth::google_contacts_emails(): done.");
		return $list;
	}
}
?>
