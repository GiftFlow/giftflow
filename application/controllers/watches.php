<?php

class Watches extends CI_Controller {

	/**
	*	View data
	*
	*	@var array
	*/
	var $data;
	
		
	function __construct()
	{
		parent::__construct();
		
		Console::logSpeed('Watches::_construct()');

		// Load external classes
		$this->load->library('datamapper');
		
		$this->util->config();
		$this->data = $this->util->parse_globals();
	}

	function index()
	{
		if (!$this->data['logged_in'])
			redirect('/');
		
		redirect('you/watches');
	}
	
	/**
	* Saves watch. Called from form.
	* 
	*/
	function add()
	{
		$this->auth->bouncer(1);
		
		if(!empty($_POST))
		{			
			$W = new Watch();
			$W->keyword = $this->db->escape_str($this->input->post('keyword'));
			$W->user_id = $this->data['logged_in_user_id'];
			
			if ( $W->save() )
			{
				$this->session->set_flashdata('success', 'Watch Added');			
			}
			else
			{
				$this->data['alert_error'] = $W->error->all;
			}
			
			redirect('you/watches');
		}
		show_error("Watch didn't save properly.");
		return FALSE;
	}
	
	/**
	 * Delete watch from database 
	 */
	function delete()
	{
		$this->auth->bouncer(1);
		
		$this->load->model('watch');
		
		$W = new Watch($this->uri->segment(2));
		
		$this->load->library('datamapper');
		
		if( $W->user_id == $this->data['logged_in_user_id'] )
		{
			$W->delete();
		}
		
		redirect('you/watches');
	}
  
}
