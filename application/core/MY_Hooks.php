<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter MY_Hooks Class
 *
 * Provides a mechanism to extend the base plugin system.  This class
 * allows other plugin hooks to be created and called without messing
 * with the core system hooks.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		David @ <xeoncross.com>
 * @link		http://codeigniter.com/forums/viewthread/67697/
 */
class MY_Hooks extends CI_Hooks {

	//Added - an array of objects that can be re-used
	var $objects		= array();

	var $data;

	/**
	 * Call Hook
	 *
	 * Calls a particular hook that return output
	 * call() is prettier than _call_hook()
	 *
	 * @access	public
	 * @param	string	the hook name
	 * @return	void
	 */
	function call($which = '', $data=null) {
		if(!empty($data)) $this->data = $data;
		parent::_call_hook($which);
	}
	
	
	/**
	 * Filter Hook
	 *
	 * Similar to call() but alows data to be filtered
	 * by multible hooks and the result is returned.
	 *
	 * @access	public
	 * @param	string	the hook name
	 * @param	mixed	the data to filter
	 * @return	mixed
	 */
	function filter($which = '', $data=null)
	{
		if (!$this->enabled OR !isset($this->hooks[$which])) {
			return FALSE;
		}
	
		if (isset($this->hooks[$which][0]) AND is_array($this->hooks[$which][0])) {
			
			//For each registered hook...
			foreach ($this->hooks[$which] as $val) {
				//Ask that hook to filter the data
				$data = $this->_run_hook($val, $data);
			}
			
			//return the result
			return $data;
		
		} else {
			//return the result since there is only one hook
			return $this->_run_hook($this->hooks[$which], $data);
		}
		
	}

	
	
	// --------------------------------------------------------------------

	/**
	 * Run Hook
	 *
	 * Runs a particular hook
	 *
	 * @access	private
	 * @param	array	the hook details
	 * @return	bool
	 */
	function _run_hook($hook, $data=null)
	{
		if(!empty($this->data)&&empty($data))
			$data = $this->data;
			
		if (!is_array($hook)) {
			return FALSE;
		}
		
		// -----------------------------------
		// Safety - Prevents run-away loops
		// -----------------------------------
	
		// If the script being called happens to have the same
		// hook call within it a loop can happen
		
		if ($this->in_progress == TRUE) { 
			return; 
		}

		// -----------------------------------
		// Set file path
		// -----------------------------------
		
		if (!isset($hook['filename'])) {
			return FALSE;
		}
	
		//If the hook file path is NOT set - default to "hooks"
		if(!isset($hook['filepath'])) {
			$hook['filepath'] = 'hooks';
		}
		
		$filepath = APPPATH.$hook['filepath'].'/'.$hook['filename'];
	
		if (!file_exists($filepath)){
			return FALSE;
		}
		
		// -----------------------------------
		// Set class/function name
		// -----------------------------------
		
		$class		= FALSE;
		$function	= FALSE;
		$params		= '';
		
		if (isset($hook['class']) AND $hook['class'] != '') {
			$class = $hook['class'];
		}

		if (isset($hook['function'])) {
			$function = $hook['function'];
		}

		if (isset($hook['params'])) {
			$params = $hook['params'];
		}
		
		if ($class === FALSE AND $function === FALSE) {
			return FALSE;
		}
		
		// -----------------------------------
		// Set the in_progress flag
		// -----------------------------------

		$this->in_progress = TRUE;
		
		// -----------------------------------
		// Call the requested class and/or function
		// -----------------------------------
		
		//Changed to support multible hooks
		
		if ($class !== FALSE) {
			
			if (!class_exists($class)) {
				require($filepath);
			}
			
			if(!isset($this->objects[$class]) || !is_object($this->objects[$class])) {
				$this->objects[$class] = new $class;
			}
			
			$output = $this->objects[$class]->$function($params, $data);
		
			//$HOOK = new $class;
			//$HOOK->$function($params);
		} else {
			if (!function_exists($function)) {
				require($filepath);
			}
			
			$output = $function($params, $data);
		}
	
		$this->in_progress = FALSE;
		return ($output ? $output : TRUE);
	}
	

	/**
	 * Remove Hook
	 *
	 * remove a particular function from the given hook
	 *
	 * @access	private
	 * @param	string	the hook name
	 * @param	string	the function name
	 * @return	void
	 */
	function remove($hook_name = '', $function='') {
		foreach($this->hooks[$hook_name] as $key => $hook) {
			if($hook['function'] == $function) {
				unset($this->hooks[$hook_name][$key]);
				return;
			}
		}
	}
	
}

// END MY_Hooks class

/* End of file Hooks.php */
/* Location: application/libraries/MY_Hooks.php */