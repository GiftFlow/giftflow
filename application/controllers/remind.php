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


	/*
	 * generates email reminding user of active transactions
	 * references $this->users and $this->transactions
	 */

	function transactionReminder()
	{
		
		$this->get_users();
		echo count($this->users)." users selected<br/>";

		//$this->add_transactions();
		echo "Transaction data added<br/>";
		//print_r($this->users);

		//$this->send_reminders();
		echo "<br/>Reminders sent<br/>";
		

	}
	function get_users()
	{
		$this->db->select('DISTINCT(U.id),
			U.screen_name AS screen_name,
			U.email AS email')
		->from('transactions_users AS TU')
		->join('transactions AS T','TU.transaction_id=T.id AND T.status ="active"','inner')
		->join('users AS U', 'TU.user_id = U.id AND U.status = "active"', 'left');

		$users = $this->db->get()->result();

		foreach($users as $key=>$use) {
			if(!empty($use->id)) {
				$this->users->$key = $use;
			} 
		}
	}

	function add_transactions() 
	{
		$T = new Transaction_search();

		$G = new Good_search();

		foreach($this->users as $user) 
		{
			if(!empty($user->id)) {
				$this->db->select('TU.transaction_id AS transaction_id, TT.user_id AS other_user')
						->from('transactions_users AS TU')
						->join('transactions_users AS TT', 'TU.transaction_id = TT.transaction_id AND TT.user_id !='.$user->id, 'left')
						->join('transactions AS T', 'TU.transaction_id = T.id AND T.status IN ("pending","active")','inner')
						->where('TU.user_id', $user->id);

				$transactions = $this->db->get()->result();

				$content = "<h3>Hello ".$user->screen_name.", </p><p> Here is a list of your uncompleted interactions on GiftFlow. Click the links below to stay in touch with your fellow GiftFlowers.</h3>";
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

						$content .= "<div><img src='http://giftflow.org/assets/images/applegate/bluearrow1.png' style='width:20px; margin-right:10px; display:inline; vertical-align:middle;'/>";

						$content .= strip_tags(trim($fullTrans->language->$summary)).". ";

						$content .= "<br /><div style='color: #666; font-size: 13px; display:block; margin-left: 30px;'>Write <a href='#' style='font-size: 14px;'>".$other_user->screen_name."</a> a review or message.</div>";
						$content .= "</div></li></a>";
					}
				}
			
				$content .= "</ul>";

				$role = $user->role;	
				$location = $fullTrans->$role->location;

				$teaser_gifts = $G->find(array(
					'radius' => 1000,
					'order_by' => 'newest',
					'sort' => 'DESC',
					'limit' => '10'
				));

				$content .= "<br/><h3>Check out some of the latest gifts on GiftFlow</h3><p>";

				foreach($teaser_gifts as $gift)
				{
					$content .= " <a style='line-height: 23px; padding: 2px; font-size:14px;' href='".site_url('gifts/'.$gift->id)."'>".$gift->title."</a> ";
				
				}
				$content .= "</p>";


				$content .= "<p><a  href='".site_url('remind/unsubscribe/'.$user->id)."'>Unsubscribe</a></p>";

				$user->data = array(
					'body' => $content,
					'email' => $user->email,
					'screen_name' => $user->screen_name
				);
			}
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
		//$this->auth->bouncer(100);
		//get users and their goods
		$stack = $this->_buildUserStack();

		//populate with matching goods
		$matchStack = $this->_addMatches($stack);

		//add html strings for email
		$content = $this->_buildMatchEmail($matchStack);

		//Send emails!
		$this->_send_matches($content);
	}


	//collects list of all goods, gets user ids, uses array_unique() method to pluck out distinct user_ids
	//then collects lists of goods and needs matching each user
	private function _buildUserStack()
	{

		$this->db->select('G.id AS good_id, G.type AS good_type,G.title AS good_title, 
			G.location_id AS good_location_id, U.id AS user_id,
			U.screen_name AS screen_name, U.email AS user_email')
					->from('goods AS G')
					->join('users AS U','G.user_id = U.id','left')
					->where('G.status','active')
					->limit('10')
					->order_by('U.id');
		$raw = $this->db->get()->result();

		$users = array();
		foreach($raw as $val)
		{
			if(!empty($val->user_id)) {
				$users[] = $val->user_id;
			}
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

			$user->location = $L->get($location);

			if(!empty($user->gifts))
			{
				foreach($user->gifts as $good)
				{
					$good->matches = $G->find(array(
						'keyword' => $good->good_title,
						'limit' => 5,
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
						'limit' => 5,
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
			if(!empty($user->user_id)) {
				//build html for email

				$content = "<p style='color:#000; font-size:14px; font-weight:bold;'>Hello ".$user->screen_name.", </p><p> Here is a list of the gifts and needs that might match your own. We hope you find this helpful.</p>";
				$content .= "<ul style='list-style:none; margin-left:-20px'>";


				if(!empty($user->gifts)) {

					$content .= $this->buildRows($user->gifts);
				}
			
				if(!empty($user->needs)) 
				{
					$content .= $this->buildRows($user->needs);
				}
				$content .= "</ul>";
				$content .= "Thank you for using GiftFlow. <a href='".site_url('find/')."'>Check out new our Give and Get pages!</a>";
				$content .= "<p><a href='".site_url('remind/unsubscribe/'.$user->user_id)."'>Unsubscribe</a></p>";
				$user->body = $content;
			}
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
				$match_type = ($val->good_type == 'gift')? 'Needs' : 'Gifts';
				$row .=  "<li><p style ='padding-top:10px; font-size:14px;'><img src='http://giftflow.org/assets/images/categories/16.png' style='width:25px; margin-right:10px; display:inline; vertical-align:middle;'/>";
				$row .= $match_type." matching your ".$val->good_type.":<span style='font-weight:bold;'> ".$val->good_title."</span></p><ul>";

					foreach($val->matches as $match)
					{
						$row .= "<li style='padding: 5px;'><div>";
						$row .= "<a style='font-size:14px; font-weight:bold; color:#587498;' href='".site_url($match->type.'s/'.$match->id)."'>".$match->title."</a> <span style='font-size:12px; color:#666666;'>from: ".$match->user->screen_name;". </span></div></li>";
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

	function unsubscribe($id) 
	{
		if(!empty($id) && is_numeric($id)) {
			$id = (int)$id;
			$sql = "INSERT into unsubscribes (user_id) VALUES (".$id.")";
			if($this->db->query($sql)) {
				$this->session->set_flashdata('success', 'You have unsubscribed from the Match Email'); 
				redirect('welcome/home');
			}
		} else {
			echo "Error saving unsubscribe";
		}
	}


}
