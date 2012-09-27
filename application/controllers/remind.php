<?php 

class Remind extends CI_Controller {


	var $users;

	var $transactions;


	function __construct()
	{
		parent::__construct();

		Console::logSpeed('Remind::__construct');
		$this->load->library('Search/Transaction_search');
		$this->load->library('notify');
		$this->load->library('Search/Good_search');

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
		->join('users AS U', 'TU.user_id = U.id', 'left')
		->where('TU.user_id',36);

		$this->users = $this->db->get()->result();
	}

	function add_transactions() 
	{
		$T = new Transaction_search();

		$G = new Good_search();

		foreach($this->users as $user) 
		{
			$this->db->select('TU.transaction_id AS transaction, TT.user_id AS other_user')
					->from('transactions_users AS TU')
					->join('transactions_users AS TT', 'TU.transaction_id = TT.transaction_id AND TT.user_id !='.$user->id, 'left')
					->join('transactions AS T', 'TU.transaction_id = T.id AND T.status IN ("pending","active")','inner')
					->where('TU.user_id', $user->id)
					->limit(5);

			$transactions = $this->db->get()->result();

			$content = "<span style='font-weight:bold;'><p>Hello ".$user->screen_name.", </p><p> Here is a list of your pending and active gifts on GiftFlow. Log in and stay in touch with your fellow GiftFlowers.
							Help us build a community of giving one click at a time.</p></span>";
			$content .= "<ul style='list-style:none;'>";


			//Build list of users active and pending transactions	
			foreach($transactions as $big)
			{
				$fullTrans = $T->get(array('transaction_id' => $big->transaction));

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
					$content .= "<span class='instructions'>Has the gift happened yet? If so, write a review. If not, write ".$other_user->screen_name." a message.</span>";
				} else if ($fullTrans->status == 'pending') { 
					if($user->role == 'decider')
					{
						$content .= "<span class='instructions'>You need to accept or decline ".$other_user->screen_name."'s request.</span>";
					} else {
						$content .= "<span class='instructions'> Send ".$other_user->screen_name." a message to remind them to reply.</span>";
					}
				}
				$content .= "</div></li></a>";
			}
		
			$content .= "</ul>";

			$role = $user->role;	
			$teaser_gifts = $G->find(array(
				'location' => $fullTrans->$role->location,
				'sort' => 'newest',
				'limit' => '10'
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
			$N->remind('transaction_reminder',$user->data);
			echo 'reminder sent to '.$user->data['screen_name'];
		}
		
	}

			
}
