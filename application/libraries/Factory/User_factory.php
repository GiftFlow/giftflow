<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	/**
	*	User Factory 
	*	Combines various data objects to hydrate a user object
	*	Customize the shape of your user object using $options
	*
	*	For usage example, See User_search::find()
	*
	*	@copycat Hans Schoenburg
	*
	*/

Class User_factory {

	/*
	 * The final result
	 * @var array
	 */
	var $Result = array();

	/*
	 * Array of photos 
	 * @var array
	 */
	var $Photos = array();

	protected $CI;


	function __construct()
	{
		$this->CI =& get_instance();
	}

	function build_users($options, $result)
	{

		Console::logSpeed('Factory::user()');
	
		// Stores formatted result objects
		$users = array();
	
		// Loop over results
		foreach( $result as $row )
		{
			// Create object and its child objects
			$user = (object) array(
				"location"=>new stdClass,
				'default_photo' => new stdClass,
				'photos' => new stdClass
			);

			// Loop over row columns
			foreach( $row as $key=>$val )
			{
				$object = substr($key, 0, strpos($key, "_"));
				$key = substr(strstr($key, "_"), 1);
				
				// Copy Good properties to main object
				if( $object == "user")
				{
					$user->$key = $val;
				}
				
				// Copy other properties to their child objects
				elseif($object == "photo")
				{
					// @todo define default user photo via config file
					if($user->photo_source=="giftflow")
					{
						
						if(isset($user->photo_id))
						{
							$user->default_photo->id = $user->photo_id;
							$user->default_photo->url = base_url().$row->photo_url;
							$user->default_photo->thumb_url = base_url().$row->photo_thumb_url;
						} else {
							$user->default_photo->thumb_url = base_url()."assets/images/user.png";
							$user->default_photo->url = base_url()."assets/images/user.png";
						}
						
					} 
					elseif($user->photo_source == "facebook" && !empty( $user->facebook_id ))
					{
						$user->default_photo->thumb_url = "http://graph.facebook.com/".$user->facebook_id."/picture?type=square";
						$user->default_photo->url = "http://graph.facebook.com/".$user->facebook_id."/picture?type=large";
					}
					else
					{
						$user->default_photo->$key = $val;
					}
				}
				else
				{
					$user->$object->$key = $val;
				}
			}

			if(isset($options->include_photos)) 
			{
				if($options->include_photos)
				{
					//query database for extra photos
					$user->photos = $this->load_photos($user->id);
				}
			}



			// Add new object to $users array
			$users[] = $user;
		}
		Console::logSpeed('Factory::user(): done.');
		return $users;
	}

	function load_photos($user_id)
	{
		//query database for extra photos
		$photos = $this->CI->db->select('P.id, P.user_id, P.url, P.thumb_url, P.caption, P.created')
					->from('photos AS P')
					->where('P.user_id', $user_id)
					->get()
					->result();
		return $photos;
	}
}

