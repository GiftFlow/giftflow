<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	The Messaging library
*	
*	@author Brandon Jackson
*	@package Libraries
*/

class Messaging
{
	var $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		
		// Load Conversation class
            require_once "Conversation.php";
	}
	
	function get_conversation( $type, $id, $user_id = NULL )
	{
		
		$C = new Conversation;
		$C->type = $type;
		$C->id = $id;
		
		if(!empty($user_id))
		{
			$C->user_id = $user_id;
		}
		else if($this->CI->auth->is_logged_in())
		{
			$C->user_id = $this->CI->session->userdata('user_id');
		}
		else
		{
			return false;
		}
		
		return $C->get();
	}

	
}
