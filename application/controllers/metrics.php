<?php 

class Metrics extends CI_Controller {

var $month_name=array( 
		1=> "January",
		2=> "February",
		3=> "March",
		4=> "April",
		5=> "May",
		6=> "June",
		7=> "July",
		8=> "August",
		9=> "September",
		10=> "October",
		11=> "November",
		12=> "December"
		);

	function __construct()
	{
		parent::__construct();
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->auth->bouncer(100);
	}

	function index()
	{

		$this->data['montly_needs'] = $this->monthly_goods('need');
		$this->data['montly_transactions'] = $this->monthly_transactions();
		$this->data['montly_reviews'] = $this->monthly_reviews();
		$this->data['montly_messages'] = $this->monthly_messages();
		$this->data['montly_thankyous'] = $this->monthly_thankyous();

		$this->data['calendar']= $this->month_name;

		$user_options = array(
			'type' => 'Users',
			'monthly' => $this->monthly_users(),
			'calendar' => $this->month_name
		);
		$this->data['user_mod'] = $this->load->view('metrics/module', $user_options, TRUE);


		$gift_options = array(
			'type' => 'Gifts',
			'monthly' => $this->monthly_goods('gift'),
			'calendar' => $this->month_name
		);
		$this->data['gift_mod'] = $this->load->view('metrics/module', $gift_options, TRUE);


		$need_options = array(
			'type' => 'Needs',
			'monthly' => $this->monthly_goods('need'),
			'calendar' => $this->month_name
		);
		$this->data['need_mod'] = $this->load->view('metrics/module', $need_options, TRUE);

		$review_options = array(
			'type' => 'Reviews',
			'monthly' => $this->monthly_reviews(),
			'calendar' => $this->month_name
		);
		$this->data['review_mod'] = $this->load->view('metrics/module', $review_options, TRUE);


		$this->data['title'] = 'Metrics!';
		
		$this->load->view('header', $this->data);
		$this->load->view('metrics/index', $this->data);
		$this->load->view('footer', $this->data);
	}


	function monthly_users()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$month = $this->db->select('U.created')
						->from('users AS U')
						->where('U.created >', "20".$x."-".$i."-01 12:00:00")
						->where('U.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($month);
			}
		}
			
		return $results;
		//$this->data['monthly_users'] = $results;

	//	$this->load->view('header', $this->data);
	//	$this->load->view('metrics/monthly_users', $this->data);
	//	$this->load->view('footer', $this->data);
	}

	function monthly_goods($type)
	{
		$this->data['x']= $this->month_name;
		$gift_results = array();
		$need_results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				//Gifts
				$gifts = $this->db->select ('G.created')
						->from('goods AS G')
						->where('G.created >', "20".$x."-".$i."-01 12:00:00")
						->where('G.created <', "20".$x."-".$y."-01 12:00:00")
						->where('G.type =',$type)
						->get()
						->result();
				$gift_results[$x][$i] = count($gifts);
			}
		}
		return $gift_results;
	}

	function monthly_transactions()
	{
		$this->data['x']= $this->month_name;

		$new_transactions_results = array();
		$completed_transactions_results = array();
		$active_transactions_results = array();
		$cancelled_transactions_results = array();
		$declined_transactions_results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

								//completed transactions each month
				$completed = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','completed')
						->get()
						->result();
				$active = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','active')
						->get()
						->result();
				$cancelled = $this->db->select ('T.created')
						->from('transactions as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->where('T.status =','cancelled')
						->get()
						->result();

			$completed_transactions[$x][$i]= count($completed);
			$active_transactions[$x][$i]= count($active);
			$cancelled_transactions[$x][$i]= count($cancelled);
			
			}
		}

			$transactions = array(
				'completed' => $completed_transactions,
				'active' => $active_transactions,
				'cancelled' => $cancelled_transactions
			);

			return $transactions;

	}

	function monthly_reviews()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<14; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$reviews = $this->db->select('R.created')
						->from('reviews AS R')
						->where('R.created >', "20".$x."-".$i."-01 12:00:00")
						->where('R.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($reviews);
			}
		}

		return $results;

	}

	function monthly_thankyous()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<13; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$thankyous = $this->db->select('T.created')
						->from('thankyous as T')
						->where('T.created >', "20".$x."-".$i."-01 12:00:00")
						->where('T.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();

				$results[$x][$i] = count($thankyous);
			}
		}

		return $results;

	}

	function monthly_messages()
	{
		$this->data['x']= $this->month_name;
		$results = array();


		for($x=10; $x<13; $x++)
		{
			for($i=1; $i<13; $i++)
			{
				$y = $i + 1;

				$messages = $this->db->select('M.created')
						->from('messages AS M')
						->where('M.created >', "20".$x."-".$i."-01 12:00:00")
						->where('M.created <', "20".$x."-".$y."-01 12:00:00")
						->get()
						->result();



				$results[$x][$i] = count($messages);
			}
		}

		return $results;

	}


}
