<?php
class Review extends DataMapperExtension {

	var $created_field = 'created';
	var $updated_field = 'updated';
	
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array(
		"reviewer"=>array(
			"class"=>"user",
			"other_field"=>"review_written"
		),
		"reviewed"=>array(
			"class"=>"user",
			"other_field"=>"review_about"
		)
	);
	
	var $has_many = array(
		"good"
	);
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'rating'=> array(
			'rules'=> array(
				'required'
			),
		),
		'body' => array(
			'rules' => array(
				'required'
			),
		),
		'transaction_id' => array(
			'rules' => array(
				'required'
			),
			
		),
		'reviewer_id' => array(
			'rules' => array(
				'required'
			),
			
		),
		'reviewed_id' => array(
			'rules' => array(
				'required'
			)
		)
	);

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		$this->CI = '';
		$this->CI =& get_instance();
	}
}
?>
