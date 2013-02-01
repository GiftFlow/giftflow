<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Group search library
 *
 * @author Hans Schoenburg
 * @package Search
 */

class Group_search extends Search
{


	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	/**
	*	Constructor
	*/
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}


	public function find($options = array())
	{
		Console::logSpeed("Group_search::find()");
		$this->basic_query();

		if(!empty($options['group_id'])) {
			$this->CI->db->where('G.id',$options['group_id']);
		}

		$groups = $this->CI->db->get()->result();
		$groups = $this->get_users($groups);
		$groups = $this->get_goods($groups);

		return $groups;


	}


	public function get_users($groups)
	{
		foreach($groups as $g) {
			$g_u = $this->CI->db->select('GU.user_id, GU.status AS user_status, GU.role AS user_role,
									U.screen_name')
						->from('groups_users AS GU')
						->join('users AS U', 'GU.user_id = U.id')
						->where('GU.group_id',$g->id)
						->where('GU.status', 'active')
						->get()->result();
			$g->users = $g_u;
		}
		return $groups;
		
	}

	public function get_goods($groups)
	{
		foreach($groups as $g) {
			$g_g = $this->CI->db->select("GG.good_id AS good_id, G.title")
								->from("groups_goods AS GG")
								->join("goods AS G", "GG.good_id = G.id")
								->where('GG.group_id', $g->id)
								->get()->result();
			$g->goods = $g_g;
		}
		return $groups;
	}

	public function find_by_user($options)
	{
		if(!empty($options['user_id'])) {

			$groups = $this->CI->db->select("GU.group_id AS group_id,
						GU.role AS user_role,
						GU.status AS user_status,
						G.name AS group_name,
						G.description AS group_description,
						G.privacy AS group_privacy,
						G.members_can_invite AS members_can_invite,
						G.location_id,
						L.city")
						->from("groups_users AS GU")
						->join("groups As G", "GU.group_id = G.id")
						->join("locations AS L", "G.location_id = L.id")
						->where("GU.user_id",$options["user_id"])
						->where("GU.status", "active")
						->get()->result();
			return $groups;

		}
	}

	public function get($options)
	{
		$result = $this->find($options);
		if(count($result) > 0) {
			return $result[0];
		} else {
			return array();
		}
	}
		


	public function basic_query() 
	{

		$this->CI->db->select('G.id as id, 
								G.name AS name, 
								G.description AS description,
								G.location_id AS location_id, 
								G.privacy AS privacy, 
								G.members_can_invite AS members_can_invite,
								G.admission AS admission,
								L.city AS location_city, L.longitude AS location_longitude, L.latitude AS location_latitude')
						->from('groups AS G')
						->join('locations AS L', 'G.location_id = L.id', 'left');
	}


}	
