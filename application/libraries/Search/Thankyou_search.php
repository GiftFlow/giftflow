<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Thankyou model for select operations
*	
*	@author Hans Schoenburg
*	@package Search
*/

class Thankyou_search extends Search
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	var $recipient_id;
		
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}


	function find($params) {

		if(!empty($params['recipient_id'])) {
			$this->basic_query();
			$this->CI->db->where('T.recipient_id', $params['recipient_id']);
		}

		$result = $this->CI->db->get()->result();

		return $result;
	}


	function basic_query() {
		$this->CI->db->select("T.id AS thankyou_id,
			T.thanker_id AS thanker_id,
			T.recipient_id AS recipient_id,
			T.gift_title AS gift_title,
			T.body AS body,
			T.created AS created,
			T.updated AS updated")
			->from('thankyous AS T');

	}




}
	
