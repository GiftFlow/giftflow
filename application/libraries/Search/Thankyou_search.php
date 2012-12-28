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

	function get($params) {

		$params['limit'] = 1;

		$result = $this->find($params);

		if(count($result) > 0)
		{
			return $result[0];
		}
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
		if(!empty($params['limit'])) {
			$this->CI->db->limit($params['limit']);
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
			RU.screen_name AS recipient_screen_name,
			RU.email AS recipient_email,
			RU.photo_source AS recipient_photo_source,
			RU.default_photo_id AS recipient_photo_id,
			RU.facebook_id AS recipient_facebook_id,
			TU.screen_name AS thanker_screen_name,
			TU.email AS thanker_email,
			TU.photo_source AS thanker_photo_source,
			TU.default_photo_id AS thanker_photo_id,
			TU.facebook_id as thanker_facebook_id,
			L.city AS city,
			L.state AS state,

			TP.id AS thanker_photo_id,
			TP.url AS thanker_photo_url,
			TP.thumb_url AS thanker_photo_thumb_url,

			RP.id AS recipient_photo_id,
			RP.url AS recipient_photo_url,
			RP.thumb_url AS recipient_photo_thumb_url")

			->from('thankyous AS T')
			->join('users AS TU', 'T.thanker_id = TU.id', 'left')
			->join('users AS RU', 'T.recipient_id = RU.id', 'left')
			->join('photos AS TP', 'TU.default_photo_id = TP.id AND TU.default_photo_id IS NOT NULL', 'left')
			->join('photos AS RP', 'RU.default_photo_id = RP.id AND RU.default_photo_id IS NOT NULL', 'left')
			->join('locations AS L', 'RU.default_location_id = L.id', 'left');
	}




}
	
