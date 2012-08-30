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
