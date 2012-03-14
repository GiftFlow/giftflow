<?php
/**
*	Comment object
*/
class Comment extends DataMapperExtension {

	var $created_field = 'created';

	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array(
		"user",
		"good"
	);
	
	var $has_many = array();
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'body' => array(
			'rules' => array(
				'required',
				'local_url',
				'is_photo'
			),
			'label' => 'Comment'
		)
	);

    	function __construct( $id = NULL )
	{
		parent::__construct( $id );
    	}
	
}
?>
