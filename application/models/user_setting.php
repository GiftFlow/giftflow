<?php
class User_setting extends DataMapperExtension {
	
	var $updated_field = 'updated';
	
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array("user");
	
	var $has_many = array();
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array();

    	function __construct( $id = NULL )
	{
		parent::__construct( $id );
    	}
}
?>
