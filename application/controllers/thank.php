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
		$this->load->library('Search/Thankyou_search');

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
			$hook_data->thanker_screen_name = $this->data['logged_in_screen_name'];

			//record event and send notification
			$this->event_logger->basic('thankyou', $hook_data);

			$this->notify->thankyou($hook_data);

			redirect('people/'.$form['recipient_id']);
	}
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
	function add_thank()
	{
		$data = array();

		if(!empty($_POST))
		{
			$post = $this->input->post();
			
			if(!empty($post['user_id'])) {
				$U = new User();
				$U->where('id',$post['user_id']);
				$U->get();

				//if the recipient is already a user, call thank
				if($U->exists()) {
					$data['recipient_id'] = $U->id;
					$data['gift'] = $post['gift'];
					$data['body'] = $post['body'];
					$this->_thank($data);
				}

			} else if(is_int(strrpos($post['thank_name'], '@'))) {

				//user entered email of invited person
				$TY = new Thankyou();

				//because the recipient is not yet a user, fudge in the senders id for now
				$TY->recipient_id = NULL;
				$TY->recipient_email = $post['thank_name'];
				$TY->thanker_id = $this->data['logged_in_user_id'];
				$TY->gift_title = $post['gift'];
				$TY->body = $post['body'];
				$TY->status = 'invited';
				
				if(!$TY->save())
				{
					show_error('Error saving Thank invite');
				} else {
					$N = new Notify();
					
					$hook_data = array(
						'recipient_email' => $post['thank_name'],
						'thanker_screen_name' => $this->data['logged_in_screen_name'],
						'gift_title' => $post['gift'],
						'body' => $post['body'],
						'subject'=> 'You have been thanked on GiftFlow!',
						'return_url' => site_url('register')
					);

					$N->thank_invite($hook_data);

					$E = new Event_logger();
					$E->basic('thank_invite', $hook_data);

					$this->session->set_flashdata('success', 'Thank sent to '.$post['thank_name']);

					redirect('you/index');
				}
			} else {

				$this->session->set_flashdata('error', 'Please either choose an existing user or provide an email address.');
				redirect('you/add_thank');
			}
		} else {
			redirect('you/add_thank');
		}
	}
}



			







