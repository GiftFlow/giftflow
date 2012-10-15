<?php
class Category extends DataMapperExtension {
	
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array();
	
	var $has_many = array( 
		"good"
	);
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'title' => array(
			'rules' => array (
				'required'
			),
			'label' => 'Category'
		)		
	);

    	function __construct( $id = NULL )
	{
		parent::__construct( $id );
    	}
	
    	function __toString()
    	{
    		return $this->title;
    	}

}
?>
