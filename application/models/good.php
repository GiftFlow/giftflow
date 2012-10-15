<?php
/**
*	The Good object is used primarily to store two types of objects:
*	Gifts and Needs.
*
*	@author Brandon Jackson
*	@package App
*	@version 0.1
*/

class Good extends DataMapperExtension {

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;


	var $created_field = 'created';
	var $error_prefix = "<p class='alert_error'>";
	var $error_suffix = "</p>";
	var $active_transaction;
  
	/**
	*	Has-one relationships
	*/
	var $has_one = array(
		"user",
		"default_photo"=> array (
			'class' => 'photo',
			'other_field'=>'default_good'
		),
		"location"
		);
	
	var $has_many = array(
		"photo", 
		"tag"
	);
	
	/**
	* 	Validation rules
	*	
	*/
	var $validation = array(
		'title' => array(
			'rules' => array(
				'required'
			),
			'label' => 'Title'
		),
		'description' => array(
			'rules' => array(),
			'label' => 'Description',
			'type'=>'textarea'
		),
		'location' => array(
			"rules"=> array(),
			'label' => "Location",
			"type"=>"dropdown"
		)
	);
	
	/**
	*	@param int $id
	*/

	function __construct( $id = NULL )
	{
		parent::__construct( $id );
		$this->CI =& get_instance();
	}

	function get_htmlform_list( $object, $field )
	{
		$this->where('user_id', $this->session->userdata('user_id'))->get('locations');
	}
	
	/**
	*	Saves a tag relationship. Updates tag count.
	*	@param string/object $tag		either tag's name or Tag object
	*/
	function add_tag($tag)
	{
		Console::logSpeed("Good::add_tag()");
		
		// $tag is tag's name
		if(is_string($tag))
		{
			$tag_name = strtolower($tag);
			$T = new Tag();
			
			// Search for tags with this name
			$T->where('name', $tag_name)
				->get();
		}
		
		// $tag is a Tag object
		else
		{
			$T = $tag;
		}
		
		// If no matching tags already exist, create one
		if(count($T->all) == 0)
		{
			$New_Tag = new Tag();
			$New_Tag->name = $tag_name;
			$New_Tag->count = 1;
			$New_Tag->save();
			
			// Save relationship to this Good
			if(!$this->save($New_Tag))
			{
				echo $this->error->string;
			}
			else
			return TRUE;
		}
		
		// If matching tags exist, save relationship
		else
		{
			
			// See if relationship already exists
			$Good_Tag = new Tag();
			$Good_Tag->where_related('good','id', $this->id)
				->where('name',$tag_name )
				->get();
			
			// If relationship doesn't exist, create it
			if(count($Good_Tag->all)==0)
			{
				// Update the tag's count field
				$T->count = count($T->all)+1;
				$T->save();
				// Save relationship
				$this->save($T);
				return TRUE;
			}
			
			// Relationship already exists
			else
			{
				return FALSE;
			}
		}
	}
	
	/**
	*	Deletes a tag relationship. Updates tag count.
	*	@param string/object $tag		either tag's name or Tag object
	*/
	function remove_tag($tag)
	{
		Console::logSpeed("Good::add_tag()");
		$tag_name = is_string($tag) ? strtolower($tag) : $tag->name;
		
		// Search for tags with this name related to this good
		$T = new Tag();
		$T->where('name', $tag_name)
			->where_related_good('id',$this->id)
			->where_related_good('status','active')
			->get();
		
		// If tag found, begin removal process
		if(count($T->all)>0)
		{
			// Remove relationship
			$this->delete($T);

			// Decrement Tag's count field (use active record since count
			// is a reserved word in datamapper)
			if(($T->count - 1)>0)
			{
				$this->CI->db->where("id",$T->id)
					->update("tags",array("count"=>($T->count - 1)));
			}
			
			// @todo If tag not used by any other good, delete it
			else
			{
				// $T->delete();
			}
		}
	}
    	
	/**
	*	This function loads a list of requests related to this good object.
	*
	*	@param string $status 	(optional) filter by status
	*	@return array				array of Transaction objects
	*/
	function requests( $status = NULL )
	{
		// Create Transaction object
		$T = new Transaction();
		
		// Filter by status
		if( !empty( $status) )
		{	

			// Multiple statii provided to filter by
			if( is_array($status))
			{
				$T->where_in('status', $status);
			}
			
			// Single status provided to filter by
			else
			{
				$T->where('status', $status);
			}
		}
		
		// Execute query
		$T	->where_related_good('id', $this->id)
			->order_by('created', 'desc')
			->include_related('user', "*", TRUE, TRUE)
			->get();

		// Return result
		return $T->all;
    	}
    	
    	/**
    	*	@param int $user_id		ID of user whose request you're requesting
    	*	@return mixed
    	*/
    	function request_status( $user_id )
    	{
		$T = new Transaction();
		$T->where_related_user('id',$user_id)
			->where_related_good('id', $this->id)
			->get();
		if($T->exists())
		{
			return array(	"status"=>$T->all[0]->status,
							"request"=>$T->all[0]);
		}
		else
		{
			return "unrequested";
		}
    	}
    	/**
    	*	Loads the current active transaction for this gift.
    	*
    	*	@return Transaction
    	*	@return Array
    	*/
    	function active_transaction()
    	{
		$T = new Transaction();
		$T	->where_related_good('id', $this->id)
			->where_in('status',array('accepted', 'exchanged') )
			->order_by('created', 'desc')
			->include_related('user','*', FALSE, TRUE)
			->get();
		if(count($T->all)==1)
		{
			return $T->all[0];
		}
		else
		{
			return $T->all;
		}
    	}
}
?>
