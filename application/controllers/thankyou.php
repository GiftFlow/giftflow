<?php

class Thankyou extends CI_Controller {

	/**
	 * Here are some variables that I might need
	 */

	//data returned from thankyou form
	var $info;

	//Good being thanked for
	var $G;

	//Recipient of the Thankyou
	var $U;

	//New Transaction created by thankyou
	var $T;

	//New Review created by thankyou
	var $R;


	function __construct()
	{
		parent::__construct();

		Console::logSpeed('Thankyou::construct()');
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('datamapper');
		$this->load->library('Search/Review_search');
		$this->load->library('Search/Transaction_search');
		$this->load->library('market');
	}

	function index()
	{
		if(!$this->data['segment'][1] || $this->data['segment'][1] == 'send')
		{
			redirect('send');
		}
	}

	/** 
	 * The thank you function is for the Thank you button on the user profile
	 * The idea is to enable users to write quick reviews for one another without
	 * needing to go through the whole transaction process
	 */
	function create() 
	{
		
		$this->auth->bouncer('1');
		if(!empty($_POST))
		{
			$this->info = $this->input->post(NULL,TRUE);
			$this->G = new Good();
			$this->G->title = $this->info['thankyou_gift'];

			//description field is not included in the thankyou form for brevity's sake
			$this->G->description = $this->info['thankyou_gift'];
			
			//to prevent the good from showing up on their profile		
			$this->G->status ='disabled';
			$this->G->user_id = $this->info['reviewed_id'];

			if(!$this->G->save())
			{
				show_error('Error saving Good from thankyou note');
			}
			//Now populate parameters to send to market library
			//note this structure is maintained to allow for multiple demands to one transaction
			$trans_options = array (
				"demands" => array (
					array(
						"user_id" => $this->data['logged_in_user_id'],
						"good_id" => $this->G->id,
						"type" => "take",
						"note" => $this->info['body'],
					)
				),
				"decider_id" => $this->info['reviewed_id'],
				'hook' => 'thankyou'

			);

			//create_transaction returns the transaction_id, unless there is an error, then it returns 0
			$new_trans_id = $this->market->create_transaction($trans_options);

			if(!$new_trans_id > 0 )
			{
				show_error('Error creating transaction'.$new_trans_id);
			}

			$this->T = new Transaction($new_trans_id);
			$this->T->status = 'pending';
			if(!$this->T->save())
			{
				show_error('Error saving transaction status');
			}
			

			//create options array for new review
			$rev_options = array (
				'transaction_id' => $new_trans_id,
				'message' => '',
				'body' => $this->info['body'],
				'rating' => $this->info['rating_select'],
				'reviewer_id' => $this->data['logged_in_user_id'],
				'reviewed_id' => $this->info['reviewed_id'],
				'hook' => 'thankyou'
			);


			if(!$this->market->review($rev_options))
			{
				show_error('Error saving thankyou as review');
			} else {
				$this->output->set_output('Success, review saved!');
			}

			$this->send();

			//Send accept/decline email with special hash

		} else {
			$this->output->set_output('Error, no data returned');
		}

	}

	function send()
	{
		$this->U = new User($this->info['reviewed_id']);

		$secret = sha1('$'.$this->U->ip_address.'$'.microtime(TRUE));

		$email_data = array(
			'email' => $this->U->email,
			'reviewed_screen_name' => $this->U->screen_name,
			'body' => $this->info['body'],
			'rating' => $this->info['rating_select'],
			'reviewer_screen_name' => $this->session->userdata['screen_name'],
			'secret' => $secret,
			'gift' => $this->G->title
		);


		//To Do!
		//Save thankyou in a table with the transaction id and the secret code
		
		$S = new Secret();
		$S->sender_id = $this->data['logged_in_user_id'];
		$S->recipient_id = $this->U->id;
		$S->code = $secret;
		$S->transaction_id = $this->T->id;
		$S->type = 'thankyou';

		if(!$S->save())
		{
			show_error('Error saving Secret');
		}

		$this->load->library('Notify');
		$N = new Notify();

		$N->thankyou('thankyou',$email_data);
	}


	//Function takes incoming URL from users email, sets transaction status based on which link they click
	function decide()
	{

		$decision = $this->data['segment'][3];

		if($decision != 'yes' && $decision != 'no')
		{
			show_error('thankyou::decide Error parsing URL, decision unclear');
		}

		$code = $this->data['segment'][4];


		if(isset($code) && isset($decision))
		{
			$S = new Secret();
			$S->where('code',$code)->get();

			if($S->exists())
			{
				$T = new Transaction($S->transaction_id);

				if($decision == 'yes')
				{
					$status = 'completed';
					$news = 'has accepted your Thanks';
				} else {
					$status = 'cancelled';
					$news = 'has declined your Thanks';
				}
						

				$T->status = $status;

				if(!$T->save())
				{
					show_error('thankyou::decide Error saving thankyou accept');
				}

				$S->code = NULL;
				if(!$S->save())
				{
					show_error('thankyou::decide Error saving old secret');
				}


				//Assemble data to send email back to original sender of the thanks, informing them 
				//of the recipient's decision
				$U = new User($S->sender_id);
				$R = new User($S->recipient_id);
				
				$email_data = array(
					'reviewer_screen_name' => $U->screen_name,
					'reviewed_screen_name' => $R->screen_name,
					'reviewed_id' => $R->id,
					'subject' => $R->screen_name.' '.$news,
					'email' => $S->email,
					'news'=> $news
				);

				$this->load->library('Notify');
				$N = new Notify();

				$N->thankYouResponse('thankyouResponse',$email_data);

				$this->auth->manual_login($R, FALSE);
				redirect('people/'.$R->id);
					
				
			} else {
				show_error('thankyou::decide Problem finding secret code');
			}
		} else {
			show_error('Thankyou::accept code missing');
		}
	}


	function form()
	{
		$options = array();

		if(!empty($_REQUEST['recipient_name']))
		{
			$options['recipient_name'] = $_REQUEST['recipient_name'];
		}

		$form = $this->load->view('forms/thankyou',$options);
		return $form;
	}
}

