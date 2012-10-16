<?php

class Goods extends CI_Controller {

	/**
	*	View data
	*
	*	@var array
	*/
	var $data;
	
	/**
	*	Good object
	*
	*	@var Object
	*/
	var $G;
	
	/**
	*	User object
	*
	*	@var Object
	*/
	var $U;
	
	/**
	*	Transaction object
	*
	*	@var Object
	*/
	var $T;
	
	/**
	*	Photo object
	*
	* @var Object
	*/
	var $P;
	
	/**
	*	Good ID / Second URL Segment
	*
	*	@var int
	*/
	var $good_id;
	
	/**
	*	Method name / Third URL Segment
	*
	*	@var string
	*/
	var $method;
	
	/**
	*	Extra parameter / Fourth URL Segment
	*
	*	@var string
	*/
	var $param;
		
	function __construct()
	{
		parent::__construct();
		
		Console::logSpeed('Goods::_construct()');

		// Load external classes
		$this->load->helper('elements');
		$this->hooks =& load_class('Hooks');		
		$this->load->library('Search/Good_search');
		$this->load->library('Event_logger');
	
		
		
		$this->util->config();
		$this->data = $this->util->parse_globals();

		// Set some class-wide variables
		$this->good_id = $this->uri->segment(2);
		$this->method = $this->uri->segment(3);
		$this->param = $this->uri->segment(4);
		
	}
	
	/**
	*	Index page. Redirects to find page, displays error.
	*/
	function index()
	{		
		// redirects back to either /gifts or /needs
		if(!$this->data['segment'][1] || $this->data['segment'][1]=="goods")
		{
			redirect('gifts');
		}
		else
		{
			redirect($this->data['segment'][1]);
		}
	}
	
	/**
	*	Routes requests to appropriate protected method
	*/
	function view()
	{
		if(!empty($_POST))
		{
			if($_POST['method'] == "demand")
			{
				$this->_demand();
			}
		}
		
		// Initialize Good
		if(!empty($this->good_id))
		{
			Console::logSpeed('loading the gift...');
			$Good_search = new Good_search;
			
			$this->G = $Good_search->get(array(
				"good_id"=>$this->good_id,
				"include_tags"=>TRUE,
				"include_photos"=>TRUE
				));
			
			
			Console::logSpeed('loading the gift...done.');


			//Load matches for sidebar

			$this->data['gifts'] = $Good_search->find(array(
				'keyword' => $this->G->title,
				'limit' => 5,
				'type' => 'gift',
				'exclude' => $this->good_id,
                                'status' => 'active'
			));
			$this->data['needs'] = $Good_search->find(array(
				'keyword' => $this->G->title,
				'limit' => 5,
				'type' => 'need',
				'exclude' => $this->good_id,
                                'status' => 'active'
			));
		} else {
			redirect('find/gifts');
		}
		
		// Parse global data
		
		// Do a few things that can only be done if a good is found
		if(!empty($this->G))
		{
			// Extend Open Graph Tags data
			$this->_extend_open_graph_tags();
			
			// Pass $this->G to view
			$this->data['G'] = $this->G;
		}
		Console::logSpeed("begin function routing");
		
		// Begin Routing
		$go = "_".$this->method;
		
		// If this auto-routed method exists, call it
		if( method_exists( __CLASS__, $go ) )
		{
			return $this->$go();
		}
		
		// If not, load detail view
		$this->_view();
	}
	
	/**
	* Saves gift or need added by user
	* called from you::add_gift form
	* @toDo add validation to check for empty fields (server and client side)
	*/
	function add()
	{
		$this->auth->bouncer(1);
		$this->load->library('datamapper');
		$U = new User($this->data['logged_in_user_id']);
		
		if(!empty($_POST))
		{
			// Create location object and then try to save it

			$this->load->library('geo');
			$Geo = new Geo();

			$L = new Location();
			
			$new_location = $Geo->geocode($this->input->post('location'));

			if(empty($new_location))
			{
				$new_location = $Geo->geocode_ip();
			}

			foreach($new_location as $key=>$val)
				$L->$key = $val;

			$L->user_id = $this->data['logged_in_user_id'];
			$L->validate();
			if(!empty($L->duplicate_id))
			{
				$L = new Location($L->duplicate_id);
			}
			elseif(!$L->save())
			{
				echo $L->error->string;
			}
			
			
			// Create Good object
			$this->G = new Good();
			$this->G->title = $this->input->post("title");
			$this->G->category_id = $this->input->post("category");
			$this->G->type = $this->input->post("type");
			$this->G->save();
			if(!empty($_POST['description']))
			{
				$this->G->description = $this->input->post('description');
			}
			
			// If location and user successfully saved to good, save
			// some more relationships
			if ( $this->G->save( array( $L, $U ) ) )
			{
				// Save location object to user and gift
				$U->save_location($L);
				$U->default_location->get();
				if(!$U->default_location->exists())
				{
					$U->save_default_location($L);
				}
				
				// Save tags
				$tags = explode(",", $_POST['tags']);
				foreach ( $tags as $tag )
				{
					$this->G->add_tag( trim($tag) );
				}
				
				// Hook: 'good_new'
				$hook_data = array(
					"good_id" => $this->G->id,
					"user_id" => $U->id
					);
				$E = new Event_logger();
				$E->basic('good_new',$hook_data);
        
				// scan the watch list to see if anyone should get notified
				
				$this->load->model('watch');
				$watches = $this->watch->match($this->G, $L);
				
				$this->load->library('notify');
				
				foreach ($watches as $thiswatch) {
					$this->notify->alert_user_watch_match($thiswatch, $this->G);
				}

				// Set flashdata
				$flash = ($this->G->type == 'gift') ? 'Gift Saved!' : 'Need Saved!';
				$this->session->set_flashdata('success',$flash);			
			}
			else
			{
				$this->data['alert_error'] = $this->G->error->all;
			}
			$where = 'you/list_goods/?type='.$this->G->type.'&id='.$this->G->id;

			redirect($where);
		}
		show_error("Good didn't save properly.");
		return FALSE;
	}

	
	/**
	*	Main "View Gift" or "View Need" page
	*/
	function _view()
	{
		// Prepare output
		Console::logSpeed('Load gift view...');
		
		//Set flag for disabled account
		$this->data['active'] = ($this->G->status == 'disabled' ? FALSE : TRUE);
				
		// Is this the good's owner? And is it a gift?
		$this->data['is_owner'] = $this->_restrict(FALSE);
		$this->data['is_gift'] = ($this->G->type=="gift");
		
		// Set default value of requested flag, will be updated below
		$this->data['requested'] = FALSE;
		
		if(!empty($this->data['logged_in_user_id']))
		{
			// Set user_id to pass to transactions search function
			// For non-owners, results are filtered by user
			$user_id = ($this->_restrict(FALSE)) ? NULL : $this->data['logged_in_user_id'];

			// Search for transactions
			$G = new Good_search;

/* DEPRECATED NOT USED
			$this->data['transactions'] = array(
				"pending" => $G->pending_transactions($user_id),
				"active" => $G->active_transactions($user_id),
				"completed" => $G->completed_transactions($user_id),
				"declined" => $G->declined_transactions($user_id),
				"cancelled" => $G->cancelled_transactions($user_id)
			);
			
			// For non-owners, set $requested flag to true if user
			// has already requested at least once
			if(!$this->_restrict(FALSE))
			{
				foreach($this->data['transactions'] as $val)
				{
					if(count($val)>0)
					{
						$this->data['requested'] = TRUE;
						break;
					}
				}
			}
 */
		}
			$other_goods = '';
			//Load matches for sidebar
			if($this->data['is_owner'])
			{
				$other_goods = ($this->data['is_gift'])? 'need' :'gift';
			} else {
				$other_goods = ($this->data['is_gift'])? 'gift' : 'need';
			}

			$this->data['other_goods'] = $G->find(array(
				'keyword' => $this->G->title,
				'limit' => 5,
				'type' => $other_goods,
				'exclude' => $this->good_id,
                'status' => 'active'
			));
			
			//load goods even if there are no keyword matches
			if(empty($this->data['other_goods']))
			{
				$this->data['other_goods'] = $G->find(array(
					'limit' => 5,
					'type' => $other_goods,
					'exclude' => $this->good_id,
					'status' => 'active'
				));
			}


		$this->data['othergoods_type'] = ucfirst($other_goods).'s';

		// Title
		$this->data['title'] = $this->G->title." | A ".ucfirst($this->G->type)." from ".$this->G->user->screen_name;
		
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			"title"=>ucfirst($this->G->type)."s", 
			"href"=>site_url($this->G->type."s")
		);
		
		$this->data['breadcrumbs'][] = array (
			"title"=>$this->G->title
		);
		
		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('goods/view', $this->data);
		$this->load->view("footer", $this->data);
	}
	
	function _edit()
	{
		$this->_restrict();

		// Save edits if POST present
		if(!empty($_POST))
		{
			return $this->_edit_save();
		}
		
		// Display editing form

		Console::logSpeed('Load edit gift view...');
		
		// Load User
		$this->load->library('datamapper');
		$this->data['U'] = new User($this->session->userdata('user_id'));
		$this->data['U']->location->get();
		
		//load datamapper object of Good
		$G_dmz = new Good();
		$G_dmz->get_where(array('id' => $this->G->id)); 
		$G_dmz->default_photo->get();
		$G_dmz->photos->get();
		
		// Add category image to the photos array
		$this->data['photos'][] = (object) array(
			"id"=>NULL,
			"caption"=>"Category Icon",
			"url"=> base_url("assets/images/categories/".$this->G->category->id.".png"),
			"thumb_url"=> base_url("assets/images/categories/".$this->G->category->id.".png"),
			"default"=>($G_dmz->default_photo->id==NULL)
		);
			
		// add other images to the photos array
		foreach($G_dmz->photos->all as $pho)
		{
			$data = (object) array (
				"id" => $pho->id,
				"caption" => $pho->caption,
				"url" => base_url($pho->url),
				"thumb_url" => base_url($pho->thumb_url),
				"default" => ($G_dmz->default_photo->id == $pho->id)
			);
			
			$this->data['photos'][] = $data;
		}
		
		// Tells view to display editing mode, not adding mode
		$this->data['add'] = FALSE;
		
		// Load categories
		$this->data['categories'] = $this->db->order_by("name","ASC")
			->get("categories")
			->result();
		
		$this->data['user_default_location'] = $this->data['userdata']['location']->address;
		
		$this->data['breadcrumbs'][] = array (
			"title"=>$this->G->title,
			"href"=>site_url($this->G->type."s/".$this->G->id)
		);
		$this->data['breadcrumbs'][] = array(
			"title"=>"Edit"
		);
		
		// Load Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['js'][] = 'GF.Tags.js';
		
		// Load views
		if($this->data['is_ajax'])
		{
			$this->load->view('you/includes/add_good_form', $this->data);
		}
		else
		{
			$this->data['form'] = $this->load->view('you/includes/add_good_form',$this->data,TRUE);
			$this->load->view('header', $this->data);
			$this->load->view('you/includes/header',$this->data);
			$this->load->view('you/add_good', $this->data);
			$this->load->view('footer', $this->data);
		}
	}
	
	/**
	* User uploads a photo of the good
	*
	*/
	function _photos()
	{
		      
		$this->auth->bouncer(1);

		//load datamapper object of Good
		$this->load->library('datamapper');
		$G_dmz = new Good();
		$G_dmz->get_where(array('id' => $this->G->id)); 
		$G_dmz->default_photo->get();
		$G_dmz->photos->get();

		// Add category image to the photos array
		$this->data['photos'][] = (object) array(
			"id"=>NULL,
			"caption"=>"Category Icon",
			"url"=> base_url("assets/images/categories/".$this->G->category->id.".png"),
			"thumb_url"=> base_url("assets/images/categories/".$this->G->category->id.".png"),
			"default"=>($G_dmz->default_photo==NULL || $G_dmz->default_photo->id==NULL)
		);
					
		// Add other images to the photos array
		foreach($G_dmz->photos->all as $pho)
		{
			$data = (object) array (
				"id" => $pho->id,
				"caption" => $pho->caption,
				"url" => base_url($pho->url),
				"thumb_url" => base_url($pho->thumb_url),
				"default" => ($G_dmz->default_photo->id == $pho->id)
			);
			
			$this->data['photos'][] = $data;
		}

			
		//Save Photo
			if(!empty($_FILES))
			{
				$this->P = new Photo();
				
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '500';
			
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('photo'))
				{					
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('success', $error);
					redirect($this->G->type.'s/'.$this->G->id."/photos");
				}
				$data = $this->upload->data();
				
			//Photo CANNOT have a good_id AND a user_id
				$this->P->good_id = $this->G->id;
				if(!empty($_POST['caption']))
				{
					$this->P->caption = $_POST['caption'];
				}
				else
				{
					$this->P->caption = $this->G->title;
				}
				
				try {
					$this->P->add($data);
				} catch (Exception $e) {
					$this->session->set_flashdata('error', $e->getMessage());
				}
				
				if(!$G_dmz->default_photo->exists())
				{
					$G_dmz->save_default_photo($this->P);
				}
				if(!$G_dmz->save($this->P))
				{
					$this->session->set_flashdata('error', $G_dmz->error->string);
					redirect($this->G->type.'s/'.$this->G->id."/photos");
				}
				
				redirect($this->G->type.'s/'.$this->G->id."/photos");
			}		
				
		$this->data['G'] = $this->G;
		
		// Title
		$this->data['title'] = "Manage Photos | ".$this->G->title;
		
		// Load Menu
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Load views
		$this->load->view('header', $this->data);
		$this->load->view('you/includes/header',$this->data);
		$this->load->view('goods/photos', $this->data);
		$this->load->view("footer", $this->data);	
	
	}
	
	/**
	*	delete Photo for given good
	*	good_id passed in url, photo_id passed in url as param
	*
	*/
	
	function _photo_delete()
	{      
		$this->load->library('datamapper');
		$G = new Good;
		$P = new Photo;
		
		$G->where('id', $this->good_id)->get();
		$P->where('id', $this->param)->get();
		
		$P->delete();
		$G->delete($P);
		
		redirect($G->type.'s/'.$G->id.'/photos');
	
	}
	
	/**
	*	select default photo
	*
	*	good_id passed in url, photo id passed in url as param
	*/
	function _photo_default()
	{
	
		$this->load->library('datamapper');
		$G = new Good;
		$P = new Photo;
		$P_d = new Photo;
		
		
		$G->where('id', $this->good_id)->get();
		$P->where('id', $this->param)->get();
		
		$P_d = $G->default_photo->get();
		
		$G->delete_default_photo($P_d);
		
		$G->save_default_photo($P);
		
		redirect($G->type.'s/'.$G->id.'/photos');
		
	
	}
	
	/**
	*	User makes a demand
	*/
	function _demand()
	{
		$input = $this->input->post();
	
		// Restrict access to logged in users
		$redirect = $input['type'].'/'.$this->G->id;

		$this->auth->bouncer('1', $redirect);
		
		$this->load->library('market');
		
		$this->good_id = $input['good_id'];
				
		// Arguments to send to Market::create_transaction()
		$options = array(
			"demands" => array(
				array (
					"user_id" => $this->data["logged_in_user_id"],
					"good_id" => $input['good_id'],
					"type" => $input['type'],
					"note" => $input['note']
				)
			),
			"decider_id" => $input['decider_id'],
			'hook' => 'transaction_new'
		);
		
		// Make request, function returns the transaction id
		if($this->market->create_transaction($options) == 0 )
		{
			// @todo handle request failure
			return FALSE;
		}
		
		
		$type = $input['type'];
		
		// Set flashdata & redirect
		if($type =='give') 
		{
			$this->session->set_flashdata('success', 'Offer sent!');
			redirect('needs/'. $this->good_id );
		}
		elseif($type =='take') 
		{
			$this->session->set_flashdata('success', 'Gift requested!');
			redirect('gifts/'. $this->good_id );
		}
		// @todo figure out flashdata for the rest of the usecases
	}

	function _edit_save()
	{
		$this->_restrict();
	
		$this->load->library('datamapper');
		
		// Create User object
		$U = new User($this->data['logged_in_user_id']);
		
		// Create new Good object
		$this->G = new Good($this->good_id);
		
		// Set basic data
		$this->G->title = $_POST["title"];
		$this->G->description = $_POST["description"];
		if(!empty($_POST['category']))
		{
			$this->G->category_id = $_POST["category"];
		}
		else
		{
			$this->G->category_id = NULL;
		}
		
		$this->G->save();
		
		// Save locaiton
		
		//Create location object
		$L = new Location();
		$L->raw = $this->input->post('location');
		$L->user_id = $this->data['logged_in_user_id'];
		
		//Validate it to make sure this location doesn't already exist
		$L->validate();
		
		// If it does already exist, load existing location
		if(!empty($L->duplicate_id))
		{
			$L = new Location($L->duplicate_id);
		}
		
		// If it doesn't already exist, save new lcoation
		else
		{
			$L->save();
		}
		
		// Save location object to user and gift
		$U->save($L);
		$this->G->save($L);
		
		
		// Save tags
		
		// Tags are passed as a comma delimited list. Explode this list into an array.
		if(!empty($_POST['tags']))
		{
			$New_Tags = explode(",", $_POST['tags']);

			// Trim tags
			foreach($New_Tags as $key=>$val){
				$New_Tags[$key] = trim($val);
			}
			
			// Load and delete existing tags
			$Old_Tags = $this->G->tag->get();
			foreach($Old_Tags->all as $Old_Tag)
			{
				// Existing tag not found in new tag list, so we delete it
				if(!in_array($Old_Tag->name,$New_Tags))
				{
					$this->G->remove_tag($Old_Tag);
				}
			}
			
			// Add new tags (and reload existing tags)
			foreach ($New_Tags as $tag)
			{
				$this->G->add_tag( $tag );
			}
		}
		
		// Save relationship to User
		$U->save_good($this->G);
		
		// Hook: 'good_edited'
		$hook_data = array(
			"good_id" => $this->G->id,
			"user_id" => $U->id
			);
		$E = new Event_logger();
		$E->basic('good_edited',$hook_data);

		// Set flashdata
		$this->session->set_flashdata('success','Changes saved successfully.');
		redirect('gifts/'.$this->G->id);

	}
	
	function _disable()
	{
		$this->auth->bouncer(1);
		$this->load->library('datamapper');
		if( $this->G->user->id == $this->data['logged_in_user_id'] )
		{
			
				//delete all associated transactions
				$this->load->library('Search/Transaction_search');
				$T = new Transaction_search();
				$transactions = $T->find($options = array('good_id' => $this->good_id));
				if(!empty($transactions))
				{
					foreach($transactions as $row)
					{
						if($row->status != 'completed')
						{
							$GT = new Transaction();
							$GT->where('id', $row->id)->get();
							$GT->status = 'disabled';
							if(!$GT->save())
							{
								die('FAIL');
							}
						}
					}
				}
			$G = new Good($this->good_id);
			$G->status='disabled';
			
			if(!$G->save())
			{
				$this->session->set_flashdata('error', $this->G->title." was not deleted");
				redirect("you/".$this->G->type."s");
			}
			else
			{
				$this->session->set_flashdata('success', $this->G->title." was deleted successfully."); 
				// Hook: 'good_deleted'
				//$this->hooks->call('good_deleted', $this);
				
				redirect("you/".$this->G->type."s");
			}
			
		}
		redirect('gifts/'.$this->good_id);
	}
	
	/*
	*	Tests to see if the current logged in user is also the author of the 
	*	current good. If yes, then it returns TRUE. If not, then it can do one 
	*	of two things. 
	*
	* 	If $redirect is unspecified or set to TRUE, the user is redirect to the 
	*	default view gift page.
	*
	*	If $redirect is set to FALSE, the function returns FALSE.
	*	
	*	@param bool redirect
	*	@returns bool OR null + redirect (see above for details)
	*/
	function _restrict( $redirect = TRUE )
	{
		if(!empty($this->data['logged_in_user_id']) && $this->G->user->id==$this->data['logged_in_user_id'] )
		{
			return true;
		}
		else
		{
			if($redirect)
			{
				redirect('gifts/'.$this->good_id);
			}
			else
			{
				return false;
			}
		}
	}
	
	/**
	*	Adds gift/need-specific <META> tags used by Facebook's "Open Graph" to
	*	$this->data['open_graph_tags'], which is then output by the header view
	*/
	function _extend_open_graph_tags()
	{
		@$extension = array(
			'og:image' => $this->G->default_photo->thumb_url,
			'og:title' => $this->G->title,
			'og:description' => $this->G->description,
			'og:type' => "product",
			'og:latitude' => $this->G->location->latitude,
			'og:longitude' => $this->G->location->longitude,
			'og:street-address' => $this->G->location->street_address,
			'og:locality' => $this->G->location->city,
			'og:region' => $this->G->location->state,
			'og:postal-code' => $this->G->location->postal_code,
			'og:country-name' => $this->G->location->country
		);
		$this->data['open_graph_tags'] = array_merge($this->data['open_graph_tags'], $extension);
	}
}
