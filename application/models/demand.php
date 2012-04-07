<?php
class Demand extends DataMapperExtension {
	
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array(
		"transaction",
		"user",
		"good"
	);
	
	var $has_many = array();
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
	);

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
	}
}
?>
