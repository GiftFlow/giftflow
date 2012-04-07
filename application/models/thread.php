<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thread extends DataMapperExtension
{
	
	 var $CI;
	
	
	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array();
	
	var $has_many = array( 
		"user",
		"message"
	);
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'subject' => array(
			'rules' => array(
				'required'
			),
			'label' => 'Subject'
		)
	);
	public function __construct( $id = NULL )
	{
		parent::__construct( $id );
	}
	
	/**
	*	Return array of related Users
	*	@return array
	*/
	public function get_users()
	{
		$U = new User();
		$U->where_related("thread","id",$this->id);
		$U->get();
		return $U->all;
	}
}
