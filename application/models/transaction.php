<?php
class Transaction extends DataMapperExtension {
	
	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	/**
	*	Database table field where created date automatically saved
	*	@var string
	*/
	var $created_field = 'created';
	
	/**
	*	Database table field where updated date automatically saved
	*	@var string
	*/
	var $updated_field = 'updated';
	
	/**
	*	Has-One Relationships
	*	@var array
	*/
	var $has_one = array();
	
	/**
	*	Has-Many Relationships
	*	@var array
	*/
	var $has_many = array(
		"message",
		"demand",
		"user"
	);

	/**
	*	Validation rules
	*	@var array
	*/
	var $validation = array(
		'type' => array(
			'rules' => array(
				'required'
			),
			'label' => 'Tag'
		)
	);
	
	/**
	*	Status of transaction. 
	*	Stored in database as a ENUM field. Possible values: 
	*	'pending','declined','cancelled','disabled','active','completed'
	*	@var string
	*/
	var $status;	

	/**
	*	Constructor
	*	If the $id paramter is provided, the object will automatically
	*	populate with the data from the database row with that ID.
	*	@var int $id
	*/
	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		
		// Populate CI object
		$this->CI =& get_instance();
		
		// Set default type
		if(empty($this->type))
		{
			$this->type = "gift";
		}
		
		// Set default status
		if(empty($this->status))
		{
			$this->status = "pending";
		}
	}
    	
	/**
	*	Load Users participating in this transaction.
	*	Used in the Conversation library
	*	@return array	Array of User objects
	*/
	function get_users()
	{
		$this->user->get();
		return $this->user->all;  	
	}

	/**
	*	Determines if both the reviews about the current transaction
	*	have been written.
	*	@return boolean
	*/
	function has_both_reviews()
    {
    	// Get count of all related reviews
    	$query = $this->db
    		->select('id')
			->from('reviews AS R')
			->where('R.transaction_id',$this->id)
			->get();
    	$count = $query->num_rows();
    	
    	// Return boolean based on number of reviews
    	return ($count >= 2);
    }
    
    /**
    *	Determines if a given user has written a review for the current
    *	transaction
    *	@param int $user_id
    *	@return boolean
    */
    function has_review_by_user($user_id)
    {
    	// Get count of all related reviews by specified user
    	$query = $this->db
    			->select('id')
    			->from('reviews')
    			->where('transaction_id',$this->id)
    			->where('reviewer_id',$user_id)
    			->get();
    	$count = $query->num_rows();
    	
    	// Return boolean based on number of reviews
    	return ($count == 1);
    }
}
?>
