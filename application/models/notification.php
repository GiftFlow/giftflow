<?php
class Notification extends DataMapperExtension {
	
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
	*	Has-One Relationships
	*	@var array
	*/
	var $has_one = array("user","event");
	
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
	function __construct()
	{
		parent::__construct();
	}
}
?>
