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

		$this->basic_query();

		if(!empty($params['id'])) {
			$this->CI->db->where('T.id', $params['id']);
		}

		if(!empty($params['recipient_id'])) {
			$this->CI->db->where('T.recipient_id', $params['recipient_id']);
		}

		if(!empty($params['status'])) {
			$this->CI->db->where_in('T.status',$params['status']);
		}

		$result = $this->CI->db->get()->result();

		return Factory::thankyou($result);
	}


	function basic_query() {
		$this->CI->db->select("T.id AS id,
			T.thanker_id AS thanker_id,
			T.recipient_id AS recipient_id,
			T.gift_title AS gift_title,
			T.body AS body,
			T.status AS status,
			T.created AS created,
			T.updated AS updated,
			TU.screen_name AS screen_name,
			TU.photo_source AS photo_source,
			TU.default_photo_id AS photo_id,
			TU.facebook_id AS facebook_id,
			U.screen_name AS recipient_screen_name,
			L.city AS city,
			L.state AS state,
			P.id AS photo_id,
			P.url AS photo_url,
			P.thumb_url AS photo_thumb_url")
			->from('thankyous AS T')
			->join('users AS TU', 'T.thanker_id = TU.id', 'left')
			->join('users AS U', 'T.recipient_id = U.id', 'left')
			->join('photos AS P', 'U.default_photo_id = P.id AND U.default_photo_id IS NOT NULL', 'left')
			->join('locations AS L', 'U.default_location_id = L.id', 'left');
	}




}
	
