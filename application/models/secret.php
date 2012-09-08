<?php

class Secret extends DataMapperExtension {

	/**
	 * CodeIgniter Super-object
	 * @var object 
	 */
	protected $CI;

	/**
	 * Database field for created
	 */
	var $created_field = 'created';

	/** 
	* Relationships
	 */

	var $has_one = array();

	var $has_many = array();
	

	function __construct($id = NULL)
	{
		parent::__construct($id);

		$this->CI =& get_instance();
	}

}
