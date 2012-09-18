<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*	The UI library is used in views to help facilitate the design
*	of modular page structures
*	
*	@author Brandon Jackson
*	@package Libraries
*/

class UI_results
{

	/**
	*	CodeIgniter super-object
	*	@var object
	*/
	protected $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	*	Returns formatted unordered list of user search results
	*	@param array $options				Array of UI configuration options
	*	@param array $options['results']	Array of User result objects
	*	@param boolean $options['mini']		Display minified list if true
	*	@param array $options['include']	Array of sections to include
	*	@param boolean $options['row']		Return single row instead of list
	*	@return string						HTML
	*/
	function users($options = array())
	{
		Console::logSpeed("UI_Results::users()");
		$CI =& get_instance();
		
		// Compile options
		$default_options = array(
			"results"=>array(),
			"mini"=>FALSE,
			"include"=>array("location","created"),
			"row"=>FALSE
		);
		$options = array_merge($default_options, $options);
		
		// If set to return single row, place in array so the view's foreach
		// loop iterates over it properly
		if($options['row'] && !is_array($options['results']))
		{
			$options['results'] = array($options['results']);
		}
		
		// Load View
		$view = $CI->load->view('people/includes/results',$options,TRUE);
		
		// Return HTML string
		Console::logSpeed("UI_Results::users(): done.");
		return $view;
	}
	
	/**
	*	Returns formatted unordered list of good search results
	*	@param array $options				Array of UI configuration options
	*	@param array $options['results']	Array of Good result objects
	*	@param boolean $options['mini']		Display minified list if true
	*	@param array $options['include']	Array of sections to include
	*	@param boolean $options['row']		Return single row instead of list
	*	@return string						HTML
	*/
	function goods($options = array())
	{
		Console::logSpeed("UI_Results::goods()");
		$CI =& get_instance();
		
		// Compile options
		$default_options = array(
			"results"=>array(),
			"mini"=>FALSE,
			"grid"=>FALSE,
			"include"=>array("location","created"),
			"row"=>FALSE,
			'sidebar' => FALSE
		);
		$options = array_merge($default_options, $options);
		
		// If set to return single row, place in array so the view's foreach
		// loop iterates over it properly
		if($options['row'] && !is_array($options['results']))
		{
			$options['results'] = array($options['results']);
		}
		
		// Load View
		$view = $CI->load->view('goods/includes/results',$options,TRUE);
		
		// Return HTML string
		Console::logSpeed("UI_Results::goods(): done.");
		return $view;
	}
	
	function reviews($options = array())
	{
		Console::logSpeed("UI_Results::reviews()");
		$CI =& get_instance();
		
		//Compile options
		$default_options= array (
		"results" => array(),
		"mini" => FALSE,
		"grid"=>FALSE,
		"include" => array ("rating", "created"),
		"row" => FALSE
		);
		
		$options = array_merge($default_options, $options);
		 // If set to return single row, place in array so the view's foreach
		// loop iterates over it properly
		if($options['row'] && !is_array($options['results']))
		{
			$options['results'] = array($options['results']);
		}
		
		// Load View
		$view = $CI->load->view('reviews/includes/results',$options,TRUE);
		
		// Return HTML string
		Console::logSpeed("UI_Results::reviews(): done.");
		return $view;	
	}
	
	function events($options = array())
	{
		Console::logspeed("UI_Results::events()");
		$CI =& get_instance();

		//Compile options
		$default_options = array(
			"results" => array(),
			"row" => FALSE
		);
		
		$options = array_merge($default_options, $options);

		//Load View
		$view = $CI->load->view('events/includes/results', $options, TRUE);

		//Return HTML string
		Console::logspeed("UI_Results::events() done.");
		return $view;	

	
	}
	function thanks($options = array())
	{
		Console::logspeed("UI_Results::thanks()");
		$CI =& get_instance();

		//Compile options
		$default_options = array(
			"results" => array(),
			"row" => FALSE
		);
		
		$options = array_merge($default_options, $options);

		//Load View
		$view = $CI->load->view('thanks/includes/results', $options, TRUE);

		//Return HTML string
		Console::logspeed("UI_Results::thanks() done.");
		return $view;	
	
	}
}
