<?php
class Message extends DataMapperExtension {
	
	var $created_field = 'created';
	
	var $updated_field = 'updated';
	
	var $recipients = array();
	
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array(
		'user',
		'thread',
		'good',
		'transaction'
	);
	
	var $has_many = array();
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'body' => array(
			'rules' => array(
				'required'
			),
			'label' => 'Message'
		),
		'user_id' => array(
			'rules' => array(
				'required'
			),
			'label' => "Author"
		)
	);
	
	var $CI;
	
	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		$this->CI =& get_instance();
	}
}
?>
