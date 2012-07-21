<?php

class Thankyou extends DataMapperExtension {


	//database fields
	//thanker_id
	//recipient_id
	//gift_title
	//body
	//rating
	//updated
	//created

	/**
	 * Codeisniter super-object
	 * @var object
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
	var $has_many = array();

	/**
	*	Validation rules
	*	@var array
	*/
	var $validation = array();


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
		
	}
}
?>
