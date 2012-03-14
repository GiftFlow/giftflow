<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	The Facebook library assists in interactions with the Facebook API
*
*	@author Brandon Jackson
* 	@package Libraries
*/

class Facebook
{
	/**
	*	@var CodeIgniter object
	*/
	protected $CI;
	
	/**
	*	GiftFlow's client id / API key
	*	@var string
	*/
	protected $client_id = "111637438874755";
	
	/**
	*	Secret key
	*	@var string
	*/
	protected $client_secret = "797a827203a1ad62cace9fa429100567";
	
	/**
	*	@var User Object
	*/
	protected $U;
	
	/**
	*	Empty friends array
	*	@var array
	*/
	protected $friends = array();
	
	/**
	*	Constructor
	*/
	public function __construct()
	{
		// Get instance of CodeIgniter
		$this->CI =& get_instance();
	}
	
	/**
	*  	Generic function to handle Facebook Graph data requests
	*
	* 	@param int $user_id
	* 	@param string $field	used to load related Facebook connect 		
						objects like photos and friends
	*	@return JSON
	*/
	function graph( $user_id, $field = NULL )
	{
		Console::logSpeed("Facebook::graph()");
		
		// Create new user
		$this->U = new User( $user_id );
		
		// If this user isn't connected via Facebook, return false;
		if(empty($this->U->facebook_id)||empty($this->U->facebook_token))
		{
			return false;
		}
		
		// Prep URL for API call
		$url = "https://graph.facebook.com/".$this->U->facebook_id;
		
		if(!empty($field))
		{
			$url .= "/".$field;
		}
		
		$url .= "?access_token=".$this->U->facebook_token;
		
		// GET file
		Console::logSpeed("Facebook::graph(): querying API. URL: ".$url);
		$json = @file_get_contents($url);
		if(empty($json))
		{
			Console::logSpeed("Facebook::graph(): JSON response empty.");
			return FALSE;
		}
		
		// Decode the JSON object Facebook returned
		$data = json_decode($json);
		
		// Return decoded data
		Console::logSpeed("Facebook::graph(): done.");
		return $data;
	}
	
	/**
	* 	Get the IDs of a user's Facebook friends
	*	@param int $user_id
	*	@return array
	*/
	public function friend_ids( $user_id )
	{
		Console::logSpeed("Facebook::friend_ids()");
		
		// Make API call
		if( $graph = $this->graph( $user_id, "friends") )
		{
			// Foreach friend, add user_id to $this->friends array
			foreach($graph->data as $val)
			{
				$this->friends[] = $val->id;
			}
			if(!empty($this->friends))
			{
				return $this->friends;
			}
			
		}
		
		return FALSE;
	}
}