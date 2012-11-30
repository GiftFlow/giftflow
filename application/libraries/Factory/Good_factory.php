<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Good Factory
 *	Combines various data objects to hydrate a good object
 *	Customize the shapre using $options
 *	For usage see Good_search::find()
 *
 *	@copycat Hans Schoenburg
 */


Class Good_factory {

	var $Result = array();

	var $Photos = array();

	protected $CI;

	function __construct()
	{
		$this->CI =& get_instance();
	}

	public function build_goods($options,$result )
	{
		Console::logSpeed('Good_factory::build_goods()');
		// Stores formatted result objects
		$product = array();
		// Loop over results
		foreach((array) $result as $row )
		{
			// Create object and its child objects
			$item = (object) array(
				"user"=>new stdClass,
				"location"=>new stdClass,
				"default_photo"=>new stdClass,
				"category"=>new stdClass,
				"transaction"=>new StdClass
			);
	
			// Loop over row columns
			foreach( $row as $key=>$val )
			{
				$object = substr($key, 0, strpos($key, "_"));
				$key = substr(strstr($key, "_"), 1);		
				// Copy Good properties to main object
			
				if( $object == "good")
				{
					$item->$key = $val;
				}				
				// Copy other properties to their child objects
				
				elseif($object=="tags"&&$key=="list")
				{
					// Explode comma delimited list of tags into array
					$item->tags = explode(",",$val);
				}
				elseif($object == 'photo')
				{
					$item->default_photo->class = '';
					$item->default_photo->thumb_class = '';
					$item->default_photo->mini_class = '';


					if(isset($row->photo_url))
					{
						$item->default_photo->url = base_url($row->photo_url);
						$item->default_photo->thumb_url = base_url($row->photo_thumb_url);
					} else {

						if($row->user_photo_source == "facebook" && !empty($row->user_facebook_id ))
						{
							$item->default_photo->thumb_url = "http://graph.facebook.com/".$row->user_facebook_id."/picture?type=square";
							$item->default_photo->url = "http://graph.facebook.com/".$row->user_facebook_id."/picture?type=large";

						} elseif(!empty($row->category_id)) {
							$item->default_photo->thumb_url = NULL;
							$item->default_photo->url = NULL;
							$item->default_photo->mini_class = 'mini-'.$row->category_id;
							$item->default_photo->thumb_class = 'medium-'.$row->category_id;
							$item->default_photo->class = 'large-'.$row->category_id;
						} else {
							$item->default_photo->thumb_url = NULL;
							$item->default_photo->url = NULL;
							$item->default_photo->mini_class = 'mini-16';
							$item->default_photo->thumb_class = 'medium-16';
							$item->default_photo->class = 'large-16';
						}
					}
				}
				elseif($object == 'user' && $key == 'photo_url' || $key == 'photo_thumb_url')
				{
					if (!isset($item->user->default_photo))
					{
						$item->user->default_photo = new stdClass();
					}
					
					if(isset($row->user_photo_url))
					{
						$item->user->default_photo->url = base_url($row->user_photo_url);
						$item->user->default_photo->thumb_url = base_url($row->user_photo_thumb_url);
					} else if($row->user_photo_source == "facebook" && !empty($row->user_facebook_id ))
					{
						$item->user->default_photo->thumb_url = "http://graph.facebook.com/".$row->user_facebook_id."/picture?type=square";
						$item->user->default_photo->url = "http://graph.facebook.com/".$row->user_facebook_id."/picture?type=large";

					} else {

						$item->user->default_photo->url = base_url()."assets/images/user.png";
						$item->user->default_photo->thumb_url = base_url()."assets/images/user.png";
					}
				}
				else
				{
					$item->$object->$key = $val;
				}
				
				if(isset($options->include_photos)) {
					if($options->include_photos) {
						$item->photos = $this->load_photos($item->id);
					}
				}				
			}
			
			// Add new object to $product array
			$product[] = $item;
		}
		Console::logSpeed('Factory::good(): done.');
		return $product;
	}


	function load_photos($good_id)
	{
		//query database for extra photos
		$photos = $this->CI->db->select('P.id AS id, P.good_id AS good_id, P.url AS url, P.thumb_url AS thumb_url, P.caption AS caption, P.created AS created')
					->from('photos AS P')
					->where('P.good_id', $good_id)
					->get()
					->result();
		return $photos;
	}
}
