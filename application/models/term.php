<?php
class Term extends DataMapperExtension {
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'name' => array(
			'rules' => array(
				'required',
				'unique',
				'trim'
			),
			'label' => 'Template Name'
		),
		'body' => array(
			'required'
		)
	);

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
	}

	/**
	*	Retrieve email templates
	*	Returns either object or FALSE if no match found
	*	@param string $name
	*	@param string $language
	*/
	function get_email_template($name, $language = 'en')
	{
		$T = new Term();
		$T->where('type', 'alert_template');
		$T->where('language', $language);
		$T->where('name', $name);
		$T->get();
		
		// If match found, return it
		if($T->exists())
		{
			return $T;
		}
		// If no match found, return FALSE
		else
		{
			return FALSE;
		}
	}
}
?>
