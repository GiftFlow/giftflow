<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Library for retrieving messages
*	
*	@author Hans Schoenburg
*	@package Search
*/

class Message_search extends Search
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	var $thread_id;

	var $threads;

	var $thread_ids;
		
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	


	function get_threads($options)
	{
		
		if(!empty($options['user_id']))
		{
			$this->CI->db->select('T.thread_id AS thread_id,
				TU.user_id AS other_user')
				->from('threads_users AS T')
				->join('threads_users AS TU','T.thread_id = TU.thread_id AND TU.user_id != '.$options["user_id"], 'left')
				->where('T.user_id',$options['user_id']);
		}

		if(!empty($options['thread_id']))
		{
			$this->CI->db->where('T.thread_id',$options['thread_id']);
		}	
		$this->threads = $this->CI->db->get()->result();

		$this->CI->load->library('Search/User_search');
		$U = new User_search();

		foreach($this->threads as $val) 
		{
			$val->other_user = $U->get(array('user_id'=>$val->other_user));
			$val->messages = $this->get_messages($val->thread_id);

			if(!empty($val->messages))
			{	
				$val->recent = end($val->messages);
			} 
		}

			return $this->threads;
	}
		

	function get_messages($thread_id)
	{
		$messages = $this->CI->db->select('M.id AS message_id, 
				M.thread_id AS thread_id,
				M.body AS message_body,
				M.user_id AS user_id,
				M.created AS message_created')
				->from("messages AS M")
				->where('M.thread_id',$thread_id)
				->order_by('M.created','DESC')
				->get()
				->result();
		
		return $messages;
	}



}
				
