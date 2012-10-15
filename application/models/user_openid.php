<?php
class User_openid extends DataMapperExtension {
	
	var $created_field = 'created';
	var $updated_field = 'updated';
		
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	var $validation = array(
		'openid' => array(
			'rules' => array(
				'required'
			),
			'label' => 'OpenID'
		),
	);
	
	var $has_one = array( 'user' );

    function __construct( $id = NULL )
	{
		parent::__construct( $id );
    }
}
?>
