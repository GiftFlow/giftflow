<?php
class Location extends DataMapperExtension {
	
	var $created_field = 'created';
	var $updated_field = 'updated';

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	// --------------------------------------------------------------------
	// Relationships
	//   Configure your relationships below
	// --------------------------------------------------------------------
	
	var $has_one = array(
		"user",
		"default_user"=>array(	
			'class'=>'user', 
			'other_field'=>'default_location'
		)
	);
	
	var $has_many = array( "good");
	
	// --------------------------------------------------------------------
	// Validation
	//   Add validation requirements, such as 'required', for your fields.
	// --------------------------------------------------------------------
	
	var $validation = array(
		'latitude' => array(
			'rules' => array (
				'geocode_location',
				'required'
			),
			'label' => 'Latitude'
		),
		'longitude' => array(
			'rules' => array (
				'geocode_location',
				'required'
			),
			'label' => 'Longitude'
		),
		'address' => array(
			'rules' => array(
				'duplicate_test',
				'required'
			),
			'label' => 'Full Address'
		),
		'street_address'=>array(
			'rules'=>array(
			),
			'label'=>'Street Address'
		),
		'city' => array(
			'rules' => array(
				'required'
			),
			'label' => 'City'
		),
		'state' => array(
			'rules' => array(
				'required'
			),
			'label'=>'State'
		)
		
	);

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		$this->CI =& get_instance();
	}
	
	function __toString()
	{
		return $this->title;
	}
	
	/**
	*	Calculates the distance between $this and another Location object
	*
	*	@param object $Location		The 2nd Location Object
	*	@param string $unit			Unit of Result ('m' [default], 'km')
	*	@return float
	*/
	function compare( $Location, $unit = 'm')
	{
		$this->CI->load->library('geo');
		return $this->CI->geo->distance($this->latitude, $this->longitude, $Location->latitude, $Location->longitude, $unit);
	}
	
	/**
	*	Convert an address into a lat/lng pair
	*/
	function geocode( $address )
	{
		$this->CI->load->library('geo');
		$result = $this->CI->geo->geocode($address);
		
		foreach ($result as $key=>$value) 
			$this->$key = $value;
		
	}
	
	/**
	 * Validation function
	 * @param type $field
	 * @return boolean 
	 */
	function _geocode_location($field)
	{
		if (empty($this->{$field}))
        {
			if(empty($this->street_address)&&empty($this->city)&&empty($this->state))
			{
				$address = $this->raw;
			}
			else
			{			
				$address = $this->street_address." ".$this->city.", ".$this->state;
			}
			$this->geocode($address);
			return true;
		}
		else 
		{
			return true;
		}
	}

	/**
	 * Validation function
	 * @param type $field
	 * @return boolean 
	 */
	function _duplicate_test($field)
	{
		$D = new Location();
		$D->where('address', $this->address)->where_related_user('id', $this->user_id)->get();
		if(count($D->all) == 1)
		{
			$this->duplicate_id = $D->id;
			return false;
		}
		$D->clear();
		if( empty($this->title) ) $this->title = $this->city.", ".$this->state;
		$D->like('title', $this->title, 'after')->where_related_user('id', $this->user_id)->get();
		if(count($D->all) > 0)
		{
			$this->title = $this->title." ".(count($D->all)+1);
		}		
	}
}
?>
