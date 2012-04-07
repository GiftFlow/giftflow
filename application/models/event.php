<?php
class Event extends DataMapperExtension {
	
	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	/**
	*	Database table field where created date automatically saved
	*	@var string
	*/
	var $created_field = 'created';
	
	/**
	*	Database table field where updated date automatically saved
	*	@var string
	*/
	var $updated_field = 'updated';
	
	/**
	*	Has-One Relationships
	*	@var array
	*/
	var $has_one = array();
	
	/**
	*	Has-Many Relationships
	*	@var array
	*/
	var $has_many = array();

	/**
	*	Validation rules
	*	@var array
	*/
	var $validation = array(
		'event_type_id' => array(
			'rules' => array(
				'set_type',
				'required'
			),
			'label' => 'Tag'
		)
	);
	
	/**
	*	Constructor
	*	If the $id paramter is provided, the object will automatically
	*	populate with the data from the database row with that ID.
	*	@var int $id
	*/
	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		
		// Populate CI object
		$this->CI =& get_instance();
	}
	
	/**
	*	Create notification for the user with the provided about this
	*	event.
	*
	*	@param int $user_id		User ID of person to notify
	*	@return boolean
	*/
	function notify_user($user_id)
	{
		$N = new Notification();
		$N->event_id = $this->id;
		$N->user_id = $user_id;
		return $N->save();
	}

	/**
	*	If $this->event_type_id isn't set but $this->type is, search
	*	the event_types table for the correct ID
	*/
    function _set_type($field)
    {
    	
        if (!empty($this->{$field}))
        {
        	return TRUE;
        }
        elseif(!empty($this->type))
        {
        	$row = $this->CI->db->select('id')
        		->where('title',$this->type)
        		->from('event_types')
        		->get()
        		->row();
        	
        	if($row)
        	{
        		settype($row->id, "int");
        		$this->{$field} = $row->id;
        		return TRUE;
        	}
        }
        return FALSE;
    }
}
?>
