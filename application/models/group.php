<?php

class Group extends DataMapperExtension {

	/**
	 * CodeIgniter super-object
	 * @var object 
	 */
	protected $CI;

	/**
	 * Database table field where created date is saved
	 * @var string
	 */
	var $created_field = 'created';


	/**
	 * update field
	 */
	var $updated_field = 'updated';

	/**
	 * Has-one relationships
	 */

	var $has_one = array(
		"location",
		"default_photo" => array(
			'class' => 'photo',
			'other_field' => 'default_group'
		));


	/**
	 * Has-many relationships
	 */
	var $has_many = array(
		"user",
		"good",
		"photo",
		"invite"
	);
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
