<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	Conversations object
*	A component of the Messaging library
*	
*	@author Brandon Jackson
*	@package Messaging
*/

class Factory
{	

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	/**
	*	Constructor
	*/
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	*	@todo make this its own class, then automatically pass
	*	function calls made to this object redirect to an instance
	*	of the Good_search class.
	*/
	public function good( $result )
	{
		Console::logSpeed('Factory::good()');
	
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
				elseif($object == 'photo' && isset($row->photo_url))
				{
					$item->photo->url = base_url($row->photo_url);
					$item->photo->thumb_url = base_url($row->photo_thumb_url);
				}
				elseif($object == 'user' && $key == 'photo_url' || $key == 'photo_thumb_url')
				{
					if(isset($row->user_photo_url))
					{
						$item->user->photo->url = base_url($row->user_photo_url);
						$item->user->photo->thumb_url = base_url($row->user_photo_thumb_url);
					}
				}
				else
				{
					$item->$object->$key = $val;
				}
				
			}
			
			// Use category photo or default photo if no category set
			if(!empty($item->category->id))
			{
				$item->default_photo->url = base_url()."assets/images/categories/".$item->category->id.".png";
			}
			else
			{
				$item->default_photo->url = base_url()."assets/images/categories/16.png";
			}
			
			//Tack on a default photo for the user
			$item->user->default_photo->url = base_url("assets/images/user.png");
			// Add new object to $product array
			$product[] = $item;
		}
		
		Console::logSpeed('Factory::good(): done.');
		return $product;
	}
	
	/**
	*	@todo make this its own class, then automatically pass
	*	function calls made to this object redirect to an instance
	*	of the Good_search class.
	*
	*/
	function user( $result )
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
				"default_photo"=>new stdClass
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
						$user->default_photo->thumb_url = base_url()."assets/images/user.png";
						$user->default_photo->url = base_url()."assets/images/user.png";
						
						if(isset($user->photo_id))
						{
							$user->photo->id = $user->photo_id;
							$user->photo->url = base_url().$row->photo_url;
							$user->photo->thumb_url = base_url().$row->photo_thumb_url;
						}
					} 
					elseif($user->photo_source == "facebook" && !empty( $user->facebook_id ))
					{
						$user->default_photo->thumb_url = "http://graph.facebook.com/".$user->facebook_id."/picture?type=square";
						$user->default_photo->url = "http://graph.facebook.com/".$user->facebook_id."/picture?type=large";
					}
					else
					{
					$user->photo->$key = $val;
					}
				}
				else
				{
					$user->$object->$key = $val;
				}
			}
			
			// Add new object to $users array
			$users[] = $user;
		}
		Console::logSpeed('Factory::user(): done.');
		return $users;
	}

  function users_ajax($results, $sort)
  {
    if(!empty($results))
    {
      $this->CI->load->library('UI/UI_results');
      $include = array('created', 'location');

      foreach($results as $key=>$val)
      {
        $results[$key]->html = UI_Results::users(array(
          "results" => $val,
          "include" => $include,
          "row" => TRUE
        ));
      }
    }
    return $results;
  }




	
	function goods_ajax($results, $sort)
	{
		if(!empty($results))
		{
			$this->CI->load->library("UI/UI_results");
			$include = array ("author");
			
			if($sort == 'location_distance')
			{
				$include[] = 'location';
			}
			elseif($sort == 'G.created')
			{
				$include[] = 'created';
				$include[] = 'location';
			}
			
			foreach($results as $key=>$val)
			{
				$results[$key]->html = UI_Results::goods(array(
					"results"=>$val,
					"include"=> $include,
					"row"=>TRUE
				));
			}
		}
		return $results;
	}

	function thankyou($results) 
	{
		//set thanker's photo source
		foreach($results as $ty) {

			if($ty->photo_source=="giftflow")
			{
				$ty->default_photo->thumb_url = base_url()."assets/images/user.png";
				$ty->default_photo->url = base_url()."assets/images/user.png";
				
				if(isset($ty->photo_id))
				{
					$ty->default_photo->id = $ty->photo_id;
					$ty->default_photo->url = base_url().$ty->photo_url;
					$ty->default_photo->thumb_url = base_url().$ty->photo_thumb_url;
				}
			} 
			elseif($ty->photo_source == "facebook" && !empty( $ty->facebook_id ))
			{
				$ty->default_photo->thumb_url = "http://graph.facebook.com/".$ty->facebook_id."/picture?type=square";
				$ty->default_photo->url = "http://graph.facebook.com/".$ty->facebook_id."/picture?type=large";
			}
			$ty->summary = '<a href="'.site_url("people/".$ty->thanker_id).'">'.$ty->screen_name.'</a> thanked <a href="'.site_url("people/".$ty->recipient_id).'">'.$ty->recipient_screen_name.'</a> for "'.$ty->gift_title.'"';
		}
		return $results;
	}


}
