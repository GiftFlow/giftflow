<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*	The UI library is used in views to help facilitate the design
*	of modular page structures
*	
*	@author Brandon Jackson
*	@package Libraries
*/

class UI
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library("UI/UI_results");
	}
	
	/**
	*	Helper that generates tag search URLs so that if we change the way
	*	these links are structured all tag clouds can be updated in one place.
	*	@param string $tag
	*	@param string $type
	*	@return string $url
	*/
	public function tag_url($tag, $type)
	{
		return site_url('find/'.$type.'/?q='.urlencode(trim($tag)));
	}
}