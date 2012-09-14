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
			$this->threads = $this->CI->db->select('T.id AS thread_id,
				TU.user_id AS thread_pair_user')
						->from('threads_users AS T')
						->join('threads_users AS TU','T.thread_id = TU.thread_id AND TU.user_id != '.$options["user_id"])
						->where('T.user_id',$options['user_id'])
						->get()
						->result();

			return $this->threads;


			//$this->thread_ids = array_map( function ($thread) { return $thread->thread_id; }, $this->threads);
		}
		

			
	}

	function get_messages($thread_id)
	{
		if(!empty($thread_id)) 
		{
			$messages = $this->CI->db->select('M.id AS message_id, 
				M.thread_id AS thread_id,
				M.body AS message_body,
				M.user_id AS user_id,
				M.created AS message_created,
				U.id AS user_id,
				U.email AS user_email,
				U.screen_name AS user_screen_name,
				U.first_name AS user_first_name,
				U.last_name AS user_last_name,
				U.photo_source AS user_photo_source,
				U.default_photo_id AS user_photo_id,
				U.facebook_id AS user_facebook_id,
				U.status AS user_status,
				U.created AS user_created,
				P.id AS photo_id,
				P.url AS photo_url,
				P.thumb_url AS photo_thumb_url')
				->from("messages AS M")
				->join('users AS U','M.user_id = U.id')
				->join("photos AS P ","U.default_photo_id = P.id AND U.default_photo_id IS NOT NULL","left")
				->where('M.thread_id',$thread_id)
				->get()
				->result();
			return $messages;
		}
	}



}
				
