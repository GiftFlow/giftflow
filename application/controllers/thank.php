<?php

/**
 * Handles all thank related functions
 *
 * @author Hans Schoenburg
 * @package Controllers
 *
 */

class Thank extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('notify');
		$this->load->library('event_logger');
		$this->load->library('datamapper');

		if(!empty($this->data['logged_in_user_id']))
		{
			$this->U = new User($this->data['logged_in_user_id']);
		}
	}


	/** 
	 *	Saves incoming form data from two places
	 *	addThank and profileThank funnel form data to be saved here
	 *	
	 */
	function _thank($form = NULL)
	{
		$this->auth->bouncer('1');

		if($form['recipient_id'] == $this->data['logged_in_user_id'])
		{
			$this->session->set_flashdata('error', 'You can not thank yourself!');
			redirect('');
		}

		$TY = new Thankyou();

		$TY->thanker_id = $this->data['logged_in_user_id'];
		$TY->recipient_id = $form['recipient_id'];
		$TY->gift_title = $form['gift'];
		$TY->body = $form['body'];
		$TY->status = 'pending';

		if(!$TY->save()) {
			show_error('Error saving Thankyou');
		} else {
			// Set flashdata & redirect
			$this->session->set_flashdata('success', 'Thank sent!');


				//Get filled out thankyou object from thankyouSearch 
			$newThank = new Thankyou_search();
			
			$hook_data = $newThank->get(array('id'=>$TY->id));
			$hook_data->return_url = site_url('you/view_thankyou/'.$TY->id);
			$hook_data->notify_id = $TY->recipient_id;

			//record event and send notification
			$E = new Event_logger();
			$E->basic('thankyou', $hook_data);

			$N = new Notify();
			$N->thankyou('thankyou', $hook_data);

			redirect('people/'.$form['recipient_id']);
	}
}

/**
 * Loads a form for adding a thank
 * User gets here from the Add menu
 */

	function addThankForm() 
	{
		$this->data['js'][] = 'GF.Users.js';
		$this->data['title'] = 'Add a thank!';
		$this->load->view('header', $this->data);

		$this->load->view('forms/addThankForm', $this->data);

		$this->load->view('footer', $this->data);
	}


	/**
	 * Handles incoming profile thankForm
	 * found in forms/thankform.php
	 */

	function profileThank()
	{
		if(!empty($_POST))
		{
			$form = $this->input->post();
			return $this->_thank($form);
		}
	}

	/**
	 * Handles incoming addThankForm and 
	 * gets extra data for thank function
	 * post from forms/addThankForm.php
	 */
	function addThank()
	{
		$data = array();

		if(!empty($_POST))
		{
			$post = $this->input->post();
			
			$U = new User();
			$U->where('email',$post['thankEmail']);
			$U->get();

			//if the recipient is already a user, call thank
			if($U->exists())
			{
				$data['recipient_id'] = $U->id;
				$data['gift'] = $post['gift'];
				$data['body'] = $post['body'];

				$this->_thank($data);

			//otherwise, save the thank with a status of 'invite'
				//and send email
			} else {
				//User does not exist in database - save thank with a status of 'invite'
				$TY = new Thankyou();
				
				//because the recipient is not yet a user, fudge in the senders id for now
				$TY->recipient_id = NULL;
				$TY->recipient_email = $post['thankEmail'];
				$TY->thanker_id = $this->U->id;
				$TY->gift_title = $post['gift'];
				$TY->body = $post['body'];
				$TY->status = 'invited';
				
				if(!$TY->save())
				{
					show_error('Error saving Thank invite');
				} else {
					$N = new Notify();
					
					$hook_data = array(
						'recipient_email' => $post['thankEmail'],
						'screen_name' => $this->U->screen_name,
						'gift_title' => $post['gift'],
						'body' => $post['body'],
						'subject'=> 'You have been thanked on GiftFlow!',
						'return_url' => site_url('register')
					);

					$N->thankInvite($hook_data);

					$E = new Event_logger();
					$E->basic('thank_invite', $hook_data);

					redirect('you');
				}
			}

		} else {
			redirect('thank/addThankForm');
		}
		
	}

}


			







