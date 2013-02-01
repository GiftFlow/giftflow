<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	The groups_manager library handles all group admin activity
*	
*	@author Hans Schoenburg
*/

class Groups_manager
{

	var $good_id;
	var $group_id;
	var $user_id;

	/**
	*	Constructor
	*/
	public function __construct()
	{
		// CodeIgniter instance
		$this->CI =& get_instance();
		
		// Load libraries
		$this->CI->load->library('datamapper');
		$this->data = $this->CI->util->parse_globals();
	}



	public function ready($level)
	{
		switch($level) {

			case 'user': 
				return (isset($this->group_id) && isset($this->user_id));
				break;
			case 'good':
				return(isset($this->good_id) && isset($this->group_id) && isset($this->user_id));
				break;
			default:
				return FALSE;
		}
	}

	/*
	 * Saves user to a group
	 * @param array $options
	 * $options['status'] = users status in the group = active/disabled
	 * $options['role'] = users permissions level = user/admin
	 */

	public function add_user($invite_user_id)
	{
		if($this->ready('user')) {

				if($this->user_role() == 'admin') {
					$options = array(
						'user_id' => $invite_user_id,
						'group_id' => $this->group_id,
						'status' => 'active',
						'role' => 'member'
					);
					if(!$this->CI->db->insert('groups_users', $options)) {
						show_error("Error saving user");
					} else {
						return TRUE;
					}
			} else {
				show_error("You do not have permission to add users");
			}
		}
	}

	public function join_group()
	{
		if($this->ready('user')) {
			$G = new Group($this->group_id);

			$status = $this->user_in_group();
			
			if($G->admission == 'open' && !$status['in_group']) {

				$options = array(
					'user_id' => $this->user_id,
					'group_id' => $this->group_id,
					'status' => 'active',
					'role' => 'member'
				);
				if(!$this->CI->db->insert('groups_users', $options)) {
					show_error("Error saving user");
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				show_error("user already in group");
				return FALSE;
			}
		}
	}

			

	public function user_role()
	{
		if($this->ready('user')) {

			$role = $this->CI->db->select('GU.user_id, GU.group_id, GU.role AS user_role')
						->from('groups_users AS GU')
						->where('GU.group_id',$this->group_id)
						->where('GU.user_id',$this->user_id)
						->where('GU.status', 'active')
						->get()->result();
			if(count($role) > 0 && count($role) < 2) {
				return $role[0]->user_role;
			} else {
				return '';
			}
		} else {
			show_error('Error! insufficient params for Groups manager');
		}
	}

	public function add_good()
	{
		if($this->ready('good')) {
			$options = array(
				'good_id'=>$this->good_id,
				'group_id' => $this->group_id
			);

			if(!$this->CI->db->insert('groups_goods', $options)) {
				show_error("Error saving good");
			} else {
				return TRUE;
			}
		} else {
			show_error("Insufficient data to add good to group");
			return FALSE;
		}
	}
	public function user_in_group()
	{
		$test = $this->CI->db->select('GU.user_id, GU.group_id, GU.status')
							->from('groups_users AS GU')
							->where('group_id',$this->group_id)
							->where('user_id', $this->user_id)
							->get()->result();

		//if active and in_group - in_group = TRUE
		$response = array();
		if(count($test) > 0)
		{
			if($test[0]->status == 'active') {
				$response['in_group'] = TRUE;
			} else {
				$response['in_group'] = FALSE;
			}
			$response['status'] = $test[0]->status;
		} else {
			$response['in_group'] = FALSE;
			$response['status'] = NULL;
		}
		return $response;
	}

	public function remove_good($options)
	{
		$success = $this->CI->db->where('group_id', $options['group_id'])
								->where('good_id', $options['good_id'])
								->delete('groups_goods');	
		if(!$success){
			show_error('Error removing good from group');
			return FALSE;
		} else {
			return TRUE;
		}
	}


	public function remove_user($remove_user)
	{
		if($this->ready('user') && $this->user_role() == 'admin') {

			$update = array('status' => 'disabled');
			$success = $this->CI->db->where('user_id',$remove_user)
						->where('group_id', $this->group_id)
						->update('groups_users', $update);
			if(!$success) {
				show_error("Error removing user from group");
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			show_error("Error, you do not have permission to remove this user");
		}
	}

	public function owns_good()
	{
		if($this->ready('good')) {
			$this->CI->load->library('datamapper');
			$G = new Good($this->good_id);
			$U = new User($this->user_id);

			if($G->user_id == $U->id) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

}







