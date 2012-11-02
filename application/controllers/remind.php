<?php 

class Remind extends CI_Controller {


	var $users;

	var $transactions;


	function __construct()
	{
		parent::__construct();

		Console::logSpeed('Remind::__construct');
		$this->util->config();
		$this->data = $this->util->parse_globals();
		$this->load->library('Search/Transaction_search');
		$this->load->library('notify');
		$this->load->library('Search/Good_search');
		$this->load->library('Search/Location_search');

		$this->auth->bouncer(100);

	}



	function transactionReminder()
	{
		
		$this->get_users();
		echo count($this->users)." users selected<br/>";

		$this->add_transactions();
		echo "Transaction data added<br/>";

		$this->send_reminders();
		echo "<br/>Reminders sent<br/>";
		

	}
	function get_users()
	{
		$this->db->select('DISTINCT(U.id),
			U.screen_name AS screen_name,
			U.email AS email')
		->from('transactions_users AS TU')
		->join('transactions AS T','TU.transaction_id=T.id AND T.status IN ("pending", "active")','inner')
		->join('users AS U', 'TU.user_id = U.id', 'left');

		$this->users = $this->db->get()->result();
	}

	function add_transactions() 
	{
		$T = new Transaction_search();

		$G = new Good_search();

		foreach($this->users as $user) 
		{
			$this->db->select('TU.transaction_id AS transaction_id, TT.user_id AS other_user')
					->from('transactions_users AS TU')
					->join('transactions_users AS TT', 'TU.transaction_id = TT.transaction_id AND TT.user_id !='.$user->id, 'left')
					->join('transactions AS T', 'TU.transaction_id = T.id AND T.status IN ("pending","active")','inner')
					->where('TU.user_id', $user->id)
					->limit(10);

			$transactions = $this->db->get()->result();

			$content = "<span style='font-weight:bold;'><p>Hello ".$user->screen_name.", </p><p> Here is a list of your pending and active gifts on GiftFlow. Log in and stay in touch with your fellow GiftFlowers.
							Help us build a community of giving one click at a time.</p></span>";
			$content .= "<ul style='list-style:none;'>";


			//Build list of users active and pending transactions	
			foreach($transactions as $big)
			{
				$fullTrans = $T->get(array('transaction_id' => $big->transaction_id));
				if(!empty($fullTrans))
				{

					$user->role = ($fullTrans->demander->id == $user->id ? 'demander' : 'decider');
					$other_user = ($fullTrans->demander->id == $user->id ? $fullTrans->decider : $fullTrans->demander);
									
					$summary = $user->role."_summary";

					$content .= "<a href='".site_url('you/view_transaction/'.$fullTrans->id)."' style='text-decoration:none; color:black;'>".
							"<li> <p style ='font-weight:bold; padding-top:10px;'><img src='http://giftflow.org/assets/images/categories/16.png' style='width:25px; display:inline; vertical-align:middle;'/>
							".strip_tags($fullTrans->demands[0]->good->title)."</p>";

					$content .= "<div style='margin-left:25px;'><img src='http://giftflow.org/assets/images/applegate/bluearrow1.png' style='width:20px; margin-right:10px; display:inline; vertical-align:middle;'/>";

					$content .= strip_tags(trim($fullTrans->language->$summary)).". ";

					if($fullTrans->status == 'active')
					{
						$content .= "<span class='instructions'>Has the gift happened yet? If so, write a review. Otherwise, you should write ".$other_user->screen_name." a message.</span>";
					} else if ($fullTrans->status == 'pending') { 
						if($user->role == 'decider')
						{
							$content .= "<span class='instructions'>You should accept or decline ".$other_user->screen_name."'s request.</span>";
						} else {
							$content .= "<span class='instructions'> Send ".$other_user->screen_name." a message to remind them to reply.</span>";
						}
					}
					$content .= "</div></li></a>";
				}
			}
		
			$content .= "</ul>";

			$role = $user->role;	
			$teaser_gifts = $G->find(array(
				'location' => $fullTrans->$role->location,
				'sort' => 'newest',
				'limit' => '10',
				'radius' => '1000',
				'status' => 'active'
			));

			$content .= "<br/><h3>Check out some of the latest Gifts</h3><p>";

			foreach($teaser_gifts as $gift)
			{
				$content .= " <a href='".site_url('gifts/'.$gift->id)."'>".$gift->title."</a>, ";
			}

			$user->content = $content;


			$user->data = array(
				'body' => $user->content,
				'email' => 'hans@giftflow.org',
				'screen_name' => $user->screen_name
			);
		}
	}

	function send_reminders()
	{
		$N = new Notify();
		foreach($this->users as $user)
		{
			$N->remind($user->data);
			echo 'reminder sent to '.$user->data['screen_name'].'<br/>';
		}
		
	}


	/*
	 * Goods matching system
	 * Sends an email to those users with goods
	 * informing them of potential matches in the database
	 * @author Hans Schoenburg
	 *
	 * matchGoods is the umbrella function
	 */


	function matchGoods()
	{
		$this->auth->bouncer(100);
		//get users and their goods
		$stack = $this->_buildUserStack();

		//populate with matching goods
		$matchStack = $this->_addMatches($stack);

		//add html strings for email
		$content = $this->_buildMatchEmail($matchStack);

		//Send emails!
		$this->_send_matches($content);
	}

	private function _buildUserStack()
	{

		$this->db->select('G.id AS good_id, G.type AS good_type,G.title AS good_title, 
			G.location_id AS good_location_id, U.id AS user_id,
			U.screen_name AS screen_name, U.email AS user_email')
					->from('goods AS G')
					->join('users AS U','G.user_id = U.id','inner')
					->where('G.status','active')
					->where('U.id !=','36')
					->order_by('U.id')
					->limit(10);
		$raw = $this->db->get()->result();
		$users = array();
		foreach($raw as $val)
		{
			$users[] = $val->user_id;
		}
	
		$user_ids =	array_unique($users);

		//process list, grouping goods by user
		
		$stack = array();
		
		foreach($user_ids as $user)
		{
			$me = new stdClass();
			$me->user_id = $user;

			//add goods to user object

			foreach($raw as $val)
			{
				if($val->user_id == $user)
				{
					$me->screen_name = $val->screen_name;
					$me->email = $val->user_email;

					if($val->good_type == 'gift')
					{
						$me->gifts[] = $val;
					} else {
						$me->needs[] = $val;
					}
				}
			}
			$stack[] = $me;
		}
		return $stack;
	}

	private function _addMatches($stack)
	{
		
		$L = new Location_search();
		$G = new Good_search();

		foreach($stack as $user) 
		{

			if(!empty($user->gifts))
			{
				$location = $user->gifts[0]->good_location_id;
			} else {
				$location = $user->needs[0]->good_location_id;
			}

			$user->location = $L->get(array('location_id' => $location));

			if(!empty($user->gifts))
			{
				foreach($user->gifts as $good)
				{
					$good->matches = $G->find(array(
						'keyword' => $good->good_title,
						'location' => $user->location,
						'radius' => 1000,
						'limit' => 10,
						'exclude' => $good->good_id,
						'type' => 'need',
                                                'status' => 'active'
					));
				}
			}

			if(!empty($user->needs))
			{
				foreach($user->needs as $good)
				{

					$G = new Good_search();

					$good->matches = $G->find(array(
						'keyword' => $good->good_title,
						'location' => $user->location,
						'radius' => 1000,
						'limit' => 10,
						'exclude' => $good->good_id,
						'type' => 'gift',
						'status'=> 'active'
					));
				}
			}


		}
		return $stack;

	}

	private function _buildMatchEmail($stack)
	{
		foreach($stack as $user)
		{
			//build html for email

			$content = "<span><p>Hello ".$user->screen_name.", </p><p> Here is a list of the gifts and needs that <i>might</i> match your own. OUr search alogrithms aren't perfect but we hope you find this helpful.
							Thank you for helping us build a community of giving one click at a time.</p></span>";
			$content .= "<ul style='list-style:none;'>";


			if(!empty($user->gifts)) {

				$content .= $this->buildRows($user->gifts);
			}
		
			if(!empty($user->needs)) 
			{
				$content .= $this->buildRows($user->needs);
			}
			$content .= "</ul>";
			$user->body = $content;
		}
		return $stack;

	}

	function buildRows($goods)
	{
		$row = '';
			
		foreach($goods as $val)
		{
			if(!empty($val->matches))
			{
				$row .=  "<li><p style ='padding-top:10px;'><img src='http://giftflow.org/assets/images/categories/16.png' style='width:25px; margin-right:10px; display:inline; vertical-align:middle;'/>";
				$row .= $val->good_title."</p><ul style='list-style:none;'>";

					foreach($val->matches as $match)
					{
					
						$row .= "<li><div style='margin-left:25px;'><img src='http://giftflow.org/assets/images/applegate/bluearrow1.png' style='width:20px; margin-right:10px; display:inline; vertical-align:middle;'/>";
						$row .= "<a href='".site_url($match->type.'s/'.$match->id)."'>".$match->title."</a></div></li>";
					}
				$row .= "</ul></li>";
			}
		}
		return $row;
	}

	private function _send_matches($stack)
	{
	
		$N = new Notify();
		foreach($stack as $user)
		{
			$N->send_matches($user);
			echo 'reminder sent to '.$user->screen_name.'<br/>';
		}
		
	}

}
