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
	 * @param type $user_id The user to exclude from the watch search
	 * @param type $title
	 * @param type $description
	 * @return type 
	 */
	function match($user_id, $title, $description) {
		
		$query = $this->CI->db
			->select('*')
			->from('watches AS W')
			->join('users AS U', 'W.user_id=U.id')	// todo add location here
			->where('W.user_id !=', $user_id)
			->where('U.status', 'active')
			->get();
		
		$results = array();
		
		foreach($query->result() as $row)
			if (strpos($title,$row->keyword) !== false || strpos($description, $row->keyword) !== false) 
				$results[] = $row;
	
		return $results;
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
