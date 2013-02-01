<?php

class Groups extends CI_Controller {

	/*
	 * View data
	 * @var array
	 */
	var $data;


	/*
	 * Group object
	 * @var object
	 */
	var $GP;



	function __construct()
	{
		parent::__construct();
		
		Console::logSpeed('Groups::_construct()');

		// Load external classes
		$this->load->helper('elements');
		$this->load->library('Search/Group_search');
		$this->load->library('datamapper');
		$this->load->library('groups_manager');
		
		$this->util->config();
		$this->data = $this->util->parse_globals();
		
	}

	function index()
	{


		$GS = new Group_search();


		$options = array();

		$this->data['groups'] = $GS->find($options);

		$this->data['invite_form'] = $this->load->view('groups/invite_form', $this->data, TRUE);

		$this->data['title'] = "GRoUPS!";
		$this->load->view('header', $this->data);
		$this->load->view('groups/dashboard',$this->data);
		$this->load->view('footer',$this->data);


	}

	function view($group_id = NULL)
	{
		if(empty($group_id)) {
			show_error('Error no group selected');
			redirect('groups');
		}

		$GS = new Group_search();

		$group  = $GS->get(array(
			'group_id' => $group_id,
			'include_goods' => TRUE,
			'include_users' => TRUE
		));

		if(!isset($group->id)) {
			show_error('Error finding group');
		} else {

			//Prep group manager
			$GM = new Groups_manager();
			$GM->user_id = $this->data['logged_in_user_id'];
			$GM->group_id = $group->id;

			$membership = $GM->user_in_group();
			
			//redirect users not allowed to be here
			if(!$membership['in_group'] && $group->privacy == 'findable') {
					//user not allowed to see group
					$this->session->set_flashdata('error', 'Sorry you do not have access to this group');
					redirect('groups');
			}

			$this->data['in_group'] = $membership['in_group'];

			$this->data['group'] = $group;

			$this->load->library('Search/Good_search');
			$G = new Good_search;
			$this->data['goods'] = $G->find(array(
				"user_id" => $this->data['logged_in_user_id'],
				'status' => 'active'
			));

			$GM = new Groups_manager();
			$GM->user_id = $this->data['logged_in_user_id'];
			$GM->group_id = $group->id;

			$this->data['user_role'] = $GM->user_role();

			//Set can_invite flag depending on role and group settings
			if($this->data['user_role'] == 'admin') {
				$this->data['can_invite'] = TRUE;
			} else {
				if($group->members_can_invite) {
					$this->data['can_invite'] = TRUE;
				} else {
					$this->data['can_invite'] = FALSE;
				}
			}

			

			$this->data['js'][] = 'GF.Users.js';
			$this->data['invite_form'] = $this->load->view('groups/invite_form', $this->data, TRUE);

			$this->data['title'] = "Group: ".$group->name;
			$this->load->view('header', $this->data);
			$this->load->view('groups/view',$this->data);
			$this->load->view('footer',$this->data);
		}
	}


	function create()
	{

		if(!empty($_POST)) {

			$post = $this->input->post();

			$GP = new Group();

			$GP->name = $post['group_name'];
			$GP->description = $post['group_description'];
			$GP->privacy = $post['group_privacy'];
			$GP->members_can_invite = $post['invite'];

			if(!$GP->save()) {
				show_error("Error saving group");
			}

			//Locations are optional for groups
			if(!empty($post['group_location'])) {

				$this->load->library('geo');
				$Geo = new Geo();
				$L = new Location();

				$new_location = $Geo->geocode($post['group_location']);

				foreach($new_location as $key=>$val)
					$L->$key = $val;

				$L->validate();
				if(!empty($L->duplicate_id))
				{
					$L = new Location($L->duplicate_id);
				}
				elseif(!$L->save())
				{
					echo $L->error->string;
				}

				$GP->save($L);
			}

			$UG = new Groups_manager();

			//Array to be inserted directly into table
			$options = array(
				'group_id' => $GP->id,
				'user_id' => $this->data['logged_in_user_id'],
				'role' => 'admin',
				'status' => 'active'
			);

			if(!$UG->add_user($options)) {
				show_error("Error Adding user to group");
			}

			$this->session->set_flashdata('success', 'Group created!');
			$this->index();

		} else {


		$this->data['title'] = "GRoUPS! CREATE";
		$this->load->view('header', $this->data);
		$this->load->view('groups/create',$this->data);
		$this->load->view('footer',$this->data);
		}
	}

	function add_user()
	{
		if(!empty($_POST)) {
			$post = $_POST;

			if(!empty($post['invite_user_id'])) {

				$GM = new Groups_manager();
				$GM->user_id = $this->data['logged_in_user_id'];
				$GM->group_id = $post['invite_group_id'];
				$GM->add_user($post['invite_user_id']);

				$this->session->set_flashdata('success', "User added to group!");
			} else {
				show_error('Error, stuff missing');
			}
		}

		return $this->view($post['invite_group_id']);
	}

	function join_group($group_id)
	{
		$GM = new Groups_manager();
		$GM->group_id = $group_id;
		$GM->user_id = $this->data['logged_in_user_id'];

		if(!$GM->join_group()) {
			$this->session->set_flashdata('error', "Error joining group");
			$this->view($group_id);
		} else {
			$this->view($group_id);
		}
	}


	function add_good($group_id, $good_id) 
	{

		$GM = new Groups_manager();
		$GM->group_id = $group_id;
		$GM->user_id = $this->data['logged_in_user_id'];
		$GM->good_id = $good_id;

		if($GM->owns_good())
		{
			$user_status = $GM->user_in_group();

			if($user_status['in_group'] && $user_status['status'] == 'active')
			{
				//user can add the good!
				if(!$GM->add_good()) {
					show_error("Error adding good to group");
				} else {
					$this->session->set_flashdata('success', 'Added to group!');
					$this->view($group_id);
				}
			} else {
				show_error('You are not in that group!');
			} 
			
		} else {
				show_error('That does not belong to you!');
		}
	}



	function remove_user($group_id, $remove_user)
	{
		$GM = new Groups_manager();

		if(is_numeric($group_id) && is_numeric($remove_user)) {

				$GM->group_id = $group_id;
				$GM->user_id = $this->data['logged_in_user_id'];
				$GM->remove_user($remove_user);
				return $this->view($group_id);
		} else {
			$this->session->set_flashdata('Error', 'Error removing user from group');
			return $this->index();
		}

	}

	function remove_good($group_id, $good_id) 
	{

		//Good can only be removed by owner of the good. 

		$GM = new Groups_manager();

		if($GM->owns_good(array('user_id'=>$this->data['logged_in_user_id'], 'good_id' => $good_id))) {
			if($GM->remove_good(array('group_id' => $group_id, 'good_id' => $group_id))) {
				$this->session->set_flashdata('success', 'Successfully removed from group');
			}
			
		} else {
			$this->session->set_flashdata('error','Sorry, you do not have permission to perform this action');
		}
			$this->view($group_id);
	}

		



}


