<?php
class Watch extends DataMapperExtension {
	
	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;
	
	/**
	*	Has-One Relationships
	*	@var array
	*/
	var $has_one = array("user");
	
	/**
	*	Has-Many Relationships
	*	@var array
	*/
	var $has_many = array();

	/**
	*	Validation rules
	*	@var array
	*/
	var $validation = array(
		array(
			'field' => 'keyword',
			'label' => 'Keyword',
			'rules' => array('required', 'unique_pair' => 'user_id')
		)
	);
	
	/**
	*	Constructor
	*	If the $id paramter is provided, the object will automatically
	*	populate with the data from the database row with that ID.
	*	@var int $id
	*/
	function __construct($id = NULL) {
		parent::__construct($id);
		$this->CI =& get_instance();
	}
	
	/**
	 * Get a list of watches that match an item
	 * 
	 * @param type $Good object
	 * @param type $good_location Location of the new good
	 * @return type 
	 */
	function match($Good, $good_location) {
		
		// find all watches wich matching keyword that belong a user in the same city as the good in question

		//escape strings for database queries
		$title = $this->CI->db->escape_like_str($Good->title);
		$description = $this->CI->db->escape_like_str($Good->description);
	

		$query = $this->CI->db
			->select('*')
			->from('watches AS W')
			->join('users AS U', 'W.user_id=U.id')
			->join('locations as L', 'L.user_id = U.id')
			->where('W.user_id !=', $Good->user_id)
			->where('L.city', $good_location->city)
			->where('L.state', $good_location->state)
			->where('U.status', 'active')
			->where('("' . $title . '" LIKE concat("%",W.keyword,"%") OR "'. $description .'" LIKE concat("%",W.keyword,"%"))', NULL, false)
			->get();
		
		return $query->result();
	}
	
	/**
	 * Get the watches that belong to a user
	 * 
	 * @param type $user_id
	 * @return type 
	 */
	function get_mine($user_id) {
		
		$query = $this->CI->db
			->select('*')
			->from('watches')
			->where('user_id', $user_id)
			->get();
		
		return $query->result();
		
	}
	
}
?>
