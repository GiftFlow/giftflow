<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*   Misc processing functions
*   prepares data for display 
*	
*
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
		$this->CI->load->library('UI/UI_results');
	}


   /**
    * Creates html for each result row  
    *  appends html to data and returns for display via ajax
    * @param type $results
    * @param type $sort
    * @return type 
    */
  function users_ajax($results, $sort)
  {
    if(!empty($results))
    {
      $include = array();

			
		if($sort == 'location_distance')
		{
			$include[] = 'location';
		}
		elseif($sort == 'U.created')
		{
			$include[] = 'created';
			$include[] = 'location';
		}
			
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


  /**
   *    same as above, creates html row for display
   * @param type $results
   * @param type $sort
   * @return type 
   */
	function goods_ajax($results, $sort)
	{
		if(!empty($results))
		{
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
					"row"=>TRUE,
					"size" => 'bricks'
				));
			}
		}
		return $results;
	}

         /**
          * Preps thankyou row for display
          * Does not involve ajax
          * @param type $results
          * @return type 
          */
	function thankyou($results) 
	{
		//set thanker's photo source
		foreach($results as $ty) {

			// set thanker photo
			if($ty->thanker_photo_source=="giftflow")
			{
				$ty->thanker_default_photo->thumb_url = base_url()."assets/images/user.png";
				$ty->thanker_default_photo->url = base_url()."assets/images/user.png";
				
				if(isset($ty->thanker_photo_id))
				{
					$ty->thanker_default_photo->id = $ty->thanker_photo_id;
					$ty->thanker_default_photo->url = base_url().$ty->thanker_photo_url;
					$ty->thanker_default_photo->thumb_url = base_url().$ty->thanker_photo_thumb_url;
				}
			} 
			elseif($ty->thanker_photo_source == "facebook" && !empty( $ty->thanker_facebook_id ))
			{
				$ty->thanker_default_photo->thumb_url = "http://graph.facebook.com/".$ty->thanker_facebook_id."/picture?type=square";
				$ty->thanker_default_photo->url = "http://graph.facebook.com/".$ty->thanker_facebook_id."/picture?type=square";
			}

			//set recipient photo
			if($ty->recipient_photo_source=="giftflow")
			{
				$ty->recipient_default_photo->thumb_url = base_url()."assets/images/user.png";
				$ty->recipient_default_photo->url = base_url()."assets/images/user.png";
				
				if(isset($ty->recipient_photo_id))
				{
					$ty->recipient_default_photo->id = $ty->recipient_photo_id;
					$ty->recipient_default_photo->url = base_url().$ty->recipient_photo_url;
					$ty->recipient_default_photo->thumb_url = base_url().$ty->recipient_photo_thumb_url;
				}
			} 
			elseif($ty->recipient_photo_source == "facebook" && !empty( $ty->recipient_facebook_id ))
			{
				$ty->recipient_default_photo->thumb_url = "http://graph.facebook.com/".$ty->recipient_facebook_id."/picture?type=square";
				$ty->recipient_default_photo->url = "http://graph.facebook.com/".$ty->recipient_facebook_id."/picture?type=square";
			}
			$ty->summary = '<a href="'.site_url("people/".$ty->recipient_id).'">'.$ty->recipient_screen_name.'</a> was thanked by <a href="'.site_url("people/".$ty->thanker_id).'">'.$ty->thanker_screen_name.'</a> for "'.$ty->gift_title.'"';
		}
		return $results;
	}

	public function groups_ajax($results)
	{
			foreach($results as $key=>$val)
			{
				$results[$key]->html = UI_Results::groups(array(
					"results"=>$val,
					"row"=>TRUE
				));
			}
			return $results;
	}


}
