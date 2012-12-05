<?php
/**
 *Account deals with all the sub menu options under the You/Acount section
 * of the you/includes/menu.php
 *  
 */
class Account extends CI_Controller {

	var $U;
	var $data;
	var $P;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('parser');
		$this->load->library('datamapper');
		$this->data = $this->util->parse_globals();
		$this->util->config();
		
		if( !empty($this->session->userdata['user_id']))
		{
			$this->U = new User($this->session->userdata('user_id'));
		}
		//to prevent welcome page from opening when editing account
		$this->data['welcome'] = FALSE;
		//for Inbox new transaction flag
		$this->data['trans_check'] = FALSE;
		
	}

	/**
	 *  defaults to user profile 
	 */
	function index()
	{
		$this->profile();
	}

	/**
	* This function handles the edit profile form  
	*/
	function profile()
	{
		$this->auth->bouncer(1);
		$input = $this->input->post();

		// Save changes
		if(!empty($input))
		{
			foreach($input as $key=>$val)
			{
				$this->U->{$key} = $this->db->escape($val);
			}
			if ( $this->U->save() )
			{
				$this->session->set_flashdata('success', 'Your profile has been updated.');
				redirect('account/profile');
			}
			else
			{
				$this->data['alert_error'] = $this->U->error->string;
				$this->_profile_edit();
			}
		}
		else
		{
			$this->_profile_edit();
		}

	}
	
	/**
	 * Function routes to the various location related functions
	 * list, add, default, delete
	 * 
	 * @param type $segment
	 * @param type $action
	 */
	function locations( $segment = NULL, $action = NULL  )
	{
	
		$this->auth->bouncer(1);
		switch( $segment )
		{
			// Empty 3rd segment. List locations.
			case FALSE:
				$this->_locations_list();
				break;
				
			// Add new location
			case 'add':
				$this->_locations_add();
				break;
				
			// Edit location of ID passed in 3rd segment
			default:
				if($action==='delete')
				{
					$this->_locations_delete( $segment );
				}
				elseif($action==='default')
				{
					$this->_locations_default( $segment );
				}
				break;
		}
	}
	
	/**
	*  This function routes to the different photo related functions
	* list, add, default, delete and edit
	* @param type $segment
	* @param type $param 
	*/
	function photos( $segment = FALSE, $param = FALSE )
	{
		$this->auth->bouncer(1);

		switch( $segment )
		{
			// Empty 3rd segment. List photos.
			case FALSE:
				$this->_photos_list();
				break;
				
			// Add new photo
			case 'add':
				$this->_photos_add();
				break;
				
			//Choose profile photo source
			case 'default_photo':
				$this->_default_photo($param);
				break;

			case 'photo_delete':
				$this->_photo_delete($param);
				break;	
				
			// Edit photo of ID passed in 3rd segment
			default:
				$this->_photos_edit($segment, $param);
				break;
		}
	}

	/**
	 *   Loads settings form
	 *   routes to process_settings upon form submission 
	 */
	function settings()
	{
		$this->auth->bouncer(1);

		if(!empty($_POST))
		{
			$this->_process_settings();
		}
		else
		{			
			$this->data['timezone_list'] = array(
				'Kwajalein' => '(GMT-12:00) International Date Line West',
				'Pacific/Midway' => '(GMT-11:00) Midway Island',
				'Pacific/Samoa' => '(GMT-11:00) Samoa',
				'Pacific/Honolulu' => '(GMT-10:00) Hawaii',
				'America/Anchorage' => '(GMT-09:00) Alaska',
				'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
				'America/Tijuana' => '(GMT-08:00) Tijuana, Baja California',
				'America/Denver' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
				'America/Chihuahua' => '(GMT-07:00) Chihuahua',
				'America/Mazatlan' => '(GMT-07:00) Mazatlan',
				'America/Phoenix' => '(GMT-07:00) Arizona',
				'America/Regina' => '(GMT-06:00) Saskatchewan',
				'America/Tegucigalpa' => '(GMT-06:00) Central America',
				'America/Chicago' => '(GMT-06:00) Central Time (US &amp; Canada)',
				'America/Mexico_City' => '(GMT-06:00) Mexico City',
				'America/Monterrey' => '(GMT-06:00) Monterrey',
				'America/New_York' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
				'America/Bogota' => '(GMT-05:00) Bogota',
				'America/Lima' => '(GMT-05:00) Lima',
				'America/Rio_Branco' => '(GMT-05:00) Rio Branco',
				'America/Indiana/Indianapolis' => '(GMT-05:00) Indiana (East)',
				'America/Caracas' => '(GMT-04:30) Caracas',
				'America/Halifax' => '(GMT-04:00) Atlantic Time (Canada)',
				'America/Manaus' => '(GMT-04:00) Manaus',
				'America/Santiago' => '(GMT-04:00) Santiago',
				'America/La_Paz' => '(GMT-04:00) La Paz',
				'America/St_Johns' => '(GMT-03:30) Newfoundland',
				'America/Argentina/Buenos_Aires' => '(GMT-03:00) Georgetown',
				'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
				'America/Godthab' => '(GMT-03:00) Greenland',
				'America/Montevideo' => '(GMT-03:00) Montevideo',
				'Atlantic/South_Georgia' => '(GMT-02:00) Mid-Atlantic',
				'Atlantic/Azores' => '(GMT-01:00) Azores',
				'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
				'Europe/Dublin' => '(GMT) Dublin',
				'Europe/Lisbon' => '(GMT) Lisbon',
				'Europe/London' => '(GMT) London',
				'Africa/Monrovia' => '(GMT) Monrovia',
				'Atlantic/Reykjavik' => '(GMT) Reykjavik',
				'Africa/Casablanca' => '(GMT) Casablanca',
				'Europe/Belgrade' => '(GMT+01:00) Belgrade',
				'Europe/Bratislava' => '(GMT+01:00) Bratislava',
				'Europe/Budapest' => '(GMT+01:00) Budapest',
				'Europe/Ljubljana' => '(GMT+01:00) Ljubljana',
				'Europe/Prague' => '(GMT+01:00) Prague',
				'Europe/Sarajevo' => '(GMT+01:00) Sarajevo',
				'Europe/Skopje' => '(GMT+01:00) Skopje',
				'Europe/Warsaw' => '(GMT+01:00) Warsaw',
				'Europe/Zagreb' => '(GMT+01:00) Zagreb',
				'Europe/Brussels' => '(GMT+01:00) Brussels',
				'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
				'Europe/Madrid' => '(GMT+01:00) Madrid',
				'Europe/Paris' => '(GMT+01:00) Paris',
				'Africa/Algiers' => '(GMT+01:00) West Central Africa',
				'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
				'Europe/Berlin' => '(GMT+01:00) Berlin',
				'Europe/Rome' => '(GMT+01:00) Rome',
				'Europe/Stockholm' => '(GMT+01:00) Stockholm',
				'Europe/Vienna' => '(GMT+01:00) Vienna',
				'Europe/Minsk' => '(GMT+02:00) Minsk',
				'Africa/Cairo' => '(GMT+02:00) Cairo',
				'Europe/Helsinki' => '(GMT+02:00) Helsinki',
				'Europe/Riga' => '(GMT+02:00) Riga',
				'Europe/Sofia' => '(GMT+02:00) Sofia',
				'Europe/Tallinn' => '(GMT+02:00) Tallinn',
				'Europe/Vilnius' => '(GMT+02:00) Vilnius',
				'Europe/Athens' => '(GMT+02:00) Athens',
				'Europe/Bucharest' => '(GMT+02:00) Bucharest',
				'Europe/Istanbul' => '(GMT+02:00) Istanbul',
				'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
				'Asia/Amman' => '(GMT+02:00) Amman',
				'Asia/Beirut' => '(GMT+02:00) Beirut',
				'Africa/Windhoek' => '(GMT+02:00) Windhoek',
				'Africa/Harare' => '(GMT+02:00) Harare',
				'Asia/Kuwait' => '(GMT+03:00) Kuwait',
				'Asia/Riyadh' => '(GMT+03:00) Riyadh',
				'Asia/Baghdad' => '(GMT+03:00) Baghdad',
				'Africa/Nairobi' => '(GMT+03:00) Nairobi',
				'Asia/Tbilisi' => '(GMT+03:00) Tbilisi',
				'Europe/Moscow' => '(GMT+03:00) Moscow',
				'Europe/Volgograd' => '(GMT+03:00) Volgograd',
				'Asia/Tehran' => '(GMT+03:30) Tehran',
				'Asia/Muscat' => '(GMT+04:00) Muscat',
				'Asia/Baku' => '(GMT+04:00) Baku',
				'Asia/Yerevan' => '(GMT+04:00) Yerevan',
				'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
				'Asia/Karachi' => '(GMT+05:00) Karachi',
				'Asia/Tashkent' => '(GMT+05:00) Tashkent',
				'Asia/Kolkata' => '(GMT+05:30) Calcutta',
				'Asia/Colombo' => '(GMT+05:30) Sri Jayawardenepura',
				'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
				'Asia/Dhaka' => '(GMT+06:00) Dhaka',
				'Asia/Almaty' => '(GMT+06:00) Almaty',
				'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
				'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
				'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
				'Asia/Bangkok' => '(GMT+07:00) Bangkok',
				'Asia/Jakarta' => '(GMT+07:00) Jakarta',
				'Asia/Brunei' => '(GMT+08:00) Beijing',
				'Asia/Chongqing' => '(GMT+08:00) Chongqing',
				'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
				'Asia/Urumqi' => '(GMT+08:00) Urumqi',
				'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
				'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaan Bataar',
				'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
				'Asia/Singapore' => '(GMT+08:00) Singapore',
				'Asia/Taipei' => '(GMT+08:00) Taipei',
				'Australia/Perth' => '(GMT+08:00) Perth',
				'Asia/Seoul' => '(GMT+09:00) Seoul',
				'Asia/Tokyo' => '(GMT+09:00) Tokyo',
				'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
				'Australia/Darwin' => '(GMT+09:30) Darwin',
				'Australia/Adelaide' => '(GMT+09:30) Adelaide',
				'Australia/Canberra' => '(GMT+10:00) Canberra',
				'Australia/Melbourne' => '(GMT+10:00) Melbourne',
				'Australia/Sydney' => '(GMT+10:00) Sydney',
				'Australia/Brisbane' => '(GMT+10:00) Brisbane',
				'Australia/Hobart' => '(GMT+10:00) Hobart',
				'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
				'Pacific/Guam' => '(GMT+10:00) Guam',
				'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
				'Asia/Magadan' => '(GMT+11:00) Magadan',
				'Pacific/Fiji' => '(GMT+12:00) Fiji',
				'Asia/Kamchatka' => '(GMT+12:00) Kamchatka',
				'Pacific/Auckland' => '(GMT+12:00) Auckland',
				'Pacific/Tongatapu' => '(GMT+13:00) Nukualofa'
			);
			
			$this->data['language_list'] = array(
				'en'=>'English',
				'es'=>'Español',
				'fr'=>'Français',
				'de'=>'Deutsch',
				'it'=>'Italiano',
				'nl'=>'Nederlands',
				'sv'=>'Svenska',
				'no'=>'Norske',
				'da'=>'Danske',
				'fi'=>'Suomalainen',
				'is'=>'Íslenska',
				'ru'=>'русский',
				'et'=>'Eesti',
				'lv'=>'Latvietis',
				'pl'=>'Polski',
				'pt'=>'Português',
				'ja'=>'日本'
			);
				
			// Configure timezone
			//	$offset = (get_timezone_offset($this->data['userdata']['timezone'], "UTC")/360);
			
			// Set view variables
			$this->data['title'] = 'Your Account';
			$this->data['menu'] = $this->load->view('you/includes/menu', $this->data, TRUE);
			$this->data['active_link'] = 'settings';
			$this->data['email'] = $this->data['userdata']['email'];
			
			// Breadcrumbs
			$this->data['breadcrumbs'][] = array(
				'title'=>'You', 
				'href'=>site_url('you')
			);
			$this->data['breadcrumbs'][] = array (
				'title'=>'Your Account',
				'href'=>site_url('account')
			);
			$this->data['breadcrumbs'][] = array (
				'title'=>'Settings'
			);
			
			// Load views
			$this->load->view('header', $this->data);
			$this->load->view('account/settings', $this->data);
			$this->load->view('footer', $this->data);
		}
	}

	/**
	*	Manage linked accounts such as Facebook
	*/
	function links()
	{
		$this->auth->bouncer(1);

		$this->data['title'] = 'Manage Linked Accounts';
		$this->data['active_link'] = 'links';


		if( !empty( $this->U->facebook_id ) )
		{
			$this->data['links']['facebook']['enabled'] = TRUE;
			$this->data['links']['facebook']['id'] = $this->U->facebook_id;
		}
		else
		{
			$this->data['links']['facebook']['enabled'] = FALSE;
		}
		
		if( !empty( $this->U->google_token))
		{
			$this->data['links']['google']['enabled'] = TRUE;
		}
		else
		{
			$this->data['links']['google']['enabled'] = FALSE;
		}
			
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			'title'=>'You', 
			'href'=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Your Account',
			'href'=>site_url('account')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Linked Accounts'
		);

		$this->load->view('header', $this->data);
		$this->load->view('account/links', $this->data);
		$this->load->view('footer', $this->data);
	}

	/**
	*	Links GiftFlow and Third-Party accounts
	*
	*	@param string $service	The service to link (eg facebook, google)
	*/
	function link( $service, $step = NULL )
	{
		if( $service === 'facebook' )
		{
			redirect('member/facebook');
		}
		elseif( $service === "google" )
		{
			$this->load->helper('url');
			
			$this->load->library('google');

			// Create the apiClient and perform authentication
			$apiClient = new apiClient();
			$apiClient->setScopes(array("https://www.google.com/m8/feeds"));
			$result = $apiClient->authenticate();
			
			// Decode result
			$accessTokenObject = json_decode($result);
			
			// Save refresh token to database for later use
			$this->U->google_token = $accessTokenObject->refresh_token;
			$this->U->save();
			
			// Set flashdata and redirect
			if($step===2)
			{
				$this->session->set_flashdata('success', 'Google account now linked with GiftFlow');
				redirect('account/links');
			}
		}
	}
	
	/**
	*	Unlinks GiftFlow and Third-Party accounts
	*
	*	@param string $service	The name of the service to unlink, ie facebook or google
	*/
	function unlink( $service )
	{
		if( $service === 'facebook')
		{
			if ($this->U->facebook_unlink())
			{
				$this->session->set_flashdata('success', 'Facebook account no longer linked with GiftFlow');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error unlinking Facebook account');
			}
			redirect('account/links');
		}
		elseif( $service === 'google' )
		{
			if ($this->U->google_unlink()) {
				$this->session->set_flashdata('success', 'Google account no longer linked with GiftFlow');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error unlinking Google account');
			}
			redirect('account/links');
		}
	}

	/**
	*  Load proflie edit form 
	*/
	function _profile_edit()
	{
		// Load the htmlform extension, so we can generate the form.
		$this->data['U'] = $this->U;
		$this->data['individual'] = ($this->U->type === 'individual') ? TRUE : FALSE;

		// Set view variables
		$this->data['title'] = 'Edit Profile';
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			'title'=>'You', 
			'href'=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Your Account',
			'href'=>site_url('account')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Edit Profile'
		);
		
		// Load Views
		$this->load->view('header', $this->data );
		$this->load->view('account/profile', $this->data);
		$this->load->view('footer', $this->data );
	}

	/**
	 *  List locations
	 */
	function _locations_list()
	{
		$this->data['js'][] = 'jquery-validate.php';
		$this->data['googlemaps'] = TRUE;
		$this->U->location->get();
		$this->U->default_location->get();
		$this->data['locations'] = array();
		foreach ( $this->U->location->all as $loc)
		{
			$data = array (
				'id' => $loc->id,
				'address' => $loc->address,
				'city' => $loc->city,
				'state' => $loc->state,
				'title' => $loc->title,
				'default'=>FALSE,
				'latitude'=>$loc->latitude,
				'longitude'=>$loc->longitude
				);
			if($this->U->default_location->id === $loc->id)
			{
				$data['default'] = TRUE;
			}
			$this->data['locations'][] = $data;
		}
		$this->data['title'] = 'Edit Locations';
		$this->data['menu'] = $this->load->view('you/includes/menu',$this->data, TRUE);
		
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			'title'=>'You', 
			'href'=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Your Account',
			'href'=>site_url('account')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>"Locations"
		);
		
		$this->load->view('header', $this->data);
		$this->load->view('account/locations');
		$this->load->view('footer', $this->data);
	}
	
	protected function _locations_add()
	{
		if(!empty($_POST))
		{
			$location= new Location();
			$this->load->library('geo');
			$Geo = new geo();
			$full_location = $Geo->geocode($this->input->post('location'));
			
			foreach($full_location as $key=>$val)
			{
				$location->$key = $val;
			}
			
			$location->user_id = $this->data['logged_in_user_id'];
			$location->validate();
			if(!empty($location->duplicate_id))
			{
				$location = new Location($location->duplicate_id);
			}
			elseif(!$location->save())
			{
				echo $location->error->string;
			}
			else
			{
				$this->U->save($location);
			}
		}
		if($this->input->is_ajax_request())
		{
			echo 'location added';
		}
		else
		{
			
			$this->load->library('auth');	// TODO: move this into local constructor
			$Auth = new Auth();
			$Auth->new_session();
			
			redirect('account/locations');
		}
	}
	
	function _locations_delete( $id )
	{
		$L = new Location( $id );
		$L->user_id = NULL;
		$L->save();
		$this->session->set_flashdata('success', 'Location deleted!');
		redirect('account/locations');
	}
	
	function _locations_default( $id )
	{
		$L = new Location( $id );
		$U = new User($this->data['logged_in_user_id']);
		$U->save_default_location($L);
		$this->session->set_flashdata('success', 'Location made default.');
		
		$this->load->library('auth');
		$this->auth->new_session();
			
		redirect('account/locations');
	}

	/**
	 * Load the photo view and all a users photos 
	 * redirects to account/photos when called by form submission
	 */
	function _photos_list()
	{

		// Handle POST data
		if(!empty($_POST) && $_POST['form_type'] === 'choose')
		{
			
			if(isset($_POST['source']))
			{
				$this->U->photo_source = $_POST['source'];
			}
			elseif(isset($_POST['default_photo_id']))
			{
				$this->U->default_photo_id = $_POST['default_photo_id'];
			}
			
			if($this->U->save())
			{
				$this->load->library('auth');
				$this->auth->new_session();
				$this->session->set_flashdata('success', 'Photo settings saved successfully.');
				redirect('account/photos');
			}
		}
		
		$this->U->photos->get();
		
		foreach($this->U->photos as $val)
		{
			$val->thumb_url = base_url($val->thumb_url);
			$val->url = base_url($val->url);
		}
		
		$this->data['num_photos'] = $this->U->photos->count();
		
		
		$this->data['photos'] = $this->U->photo->all;
		
		// Is this person connected via facebook
		if(!empty($this->U->facebook_id))
		{
			$this->data['facebook_connected'] = TRUE;
		}
		else
		{
			$this->data['facebook_connected'] = FALSE;
		}
		
		$this->data['U'] = $this->U;
		
		// Set view variables
		$this->data['title'] = 'Photos';
		$this->data['menu'] = $this->load->view('you/includes/menu', $this->data, TRUE);
		
		// Breadcrumbs
		$this->data['breadcrumbs'][] = array(
			'title'=>'You', 
			'href'=>site_url('you')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Your Account',
			'href'=>site_url('account')
		);
		$this->data['breadcrumbs'][] = array (
			'title'=>'Photos'
		);
		
		// Load Views
		$this->load->view('header', $this->data);
		//$this->parser->parse('account/photos/list', $this->data);
		$this->load->view('account/photos', $this->data);
		$this->load->view('footer', $this->data);
	}
	
	/**
	* Delete a photo and its association to the user
	* @param type $param 
	*/
	function _photo_delete($param)
	{

		$P = new Photo;
		$P->where('id',$param)->get();

		$P->delete();
		$this->U->delete($P);

		redirect('account/photos');
	}
	
	/**
	 *  Add a photo and associate it with the user 
	 */
	function _photos_add()
	{
		//Save Photo
		if(!empty($_POST))
		{
			$input = $this->input->post();
			$this->P = new Photo();
			
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '500';
			
			$this->load->library('upload', $config);
			
			// If upload fails, display error flashdata
			if ( !$this->upload->do_upload('photo'))
			{
				$error = $this->upload->display_errors();
				$this->session->set_flashdata('success', $error);
				redirect('account/photos');
			}
			
			
			// Upload successful, Saving Photo object
			$this->P->user_id = $this->U->id;
			if(!empty($input['caption']))
			{
				$this->P->caption = $input['caption'];
			}
			else
			{
				$this->P->caption = $this->U->screen_name;
			}
			
			$data = $this->upload->data();
			$this->P->add($data);
			
			//	If errors while saving, set flashdata and redirect
			if(!$this->P->save())
			{
				$this->session->set_flashdata('error', $this->P->error->string);
				redirect('account/photos');
			}
			if(!$this->U->save($this->P))
			{
				$this->session->set_flashdata('error', $this->U->error->string);
				redirect('account/photos');
			}
			
			redirect('account/photos');
		}
		else
		{
			redirect('account/photos');
		}
	}
	
	/**
	 *  Set which photo should be used as the users profile or default picture
	 * @param int - the id of the photo chosen
	 */
	protected function _default_photo($param)
	{
		if($param !== 'facebook') {
			$this->U->photo_source = 'giftflow';
			$P = new Photo();
			$P->where('id', $param)->get();
			$this->U->save_default_photo($P);
		}
		else
		{
			$this->U->photo_source = 'facebook';
			$this->U->default_photo_id = NULL;
		}

		if(!$this->U->save())
		{
			show_error('Error saving photo source');
		} else {
			$this->load->library('auth');
			$this->auth->new_session();
		}

		redirect('account/photos');
	}

	/**
	 *  This function saves changes to a users profile 
	 */
	function _process_settings()
	{
		if(!empty($_POST['email']))
		{
			$this->U->email = $_POST['email'];
		}
		
		if(!empty($_POST['new_password']))
		{
			$this->U->password = $_POST['new_password'];
		}
		
		if(!empty($_POST['confirm_new_password']))
		{
			$this->U->confirm_password = $_POST['confirm_new_password'];
		}
		
		if(!empty($_POST['timezone']))
		{
			$this->U->timezone = $_POST['timezone'];
		}
		
		if(!empty($_POST['language']))
		{
			$this->U->language = $_POST['language'];
		}
		
		if($this->U->save())
		{
			$this->load->library('auth');
			$this->auth->new_session();

			$this->session->set_flashdata('success', 'Settings saved successfully.');
		}
		else
		{
			show_error($this->U->error->string);
			$this->session->set_flashdata('error', $this->U->error->string);
		}
		
		redirect('account/settings');
	}
	
	/**
	*   Loads the delete user view then redirects to logout upon form submission	
	*   Set's user status to 'disabled'
	*   Disables all the user's goods and every uncompleted transactions
	*/
	function delete_user() 
	{
	
		$this->auth->bouncer(1);
		
		if(!empty($_POST))
		{
			$new_email = sha1('~'.$this->U->email.'~'.microtime(TRUE));
			$this->U->status = 'disabled';
			$this->U->email =  $new_email.'@disabled.com';
			$this->U->password = '1';
			$this->U->ip_address = '2';
			$this->U->facebook_id = '3';
			$this->U->facebook_token = '4';
			$this->U->google_token  = '5';
			$this->U->google_token_secret = '6';
			

			
			//Disable all goods and uncompleted transactions
			$this->load->library('datamapper');
			$this->load->library('Search/Transaction_search');
			$this->load->library('Search/Good_search');
			
			//disable all the user's goods
			$G = new Good_search();
			$goods = $G->find($options = array('user_id' => $this->U->id));

			if(!empty($goods)) 
			{
				foreach($goods as $goodbye)
				{
					$G_bye = new Good($goodbye->id);
					$G_bye->status = 'disabled';
					if(!$G_bye->save())
					{
						$this->session->set_flashdata('error', 'Encountered problems deleting your account. Please try again.');
						redirect("you");
					}
				}
			}
			//disable all uncompleted transaction
			$T = new Transaction_search();
			$transactions = $T->find($options = array('user_id' => $this->U->id));
				if(!empty($transactions))
				{
					foreach($transactions as $row)
					{
						if($row->status !== 'completed')
						{
							$GT = new Transaction();
							$GT->where('id', $row->id)->get();
							$GT->status = 'disabled';
							if(!$GT->save())
							{
								$this->session->set_flashdata('error', 'Encountered problems deleting your account. Please try again.');
								redirect('you');
							}
						}
					}
				}
		
			if(!$this->U->save())
			{
				echo $this->U->error->string;
				$this->session->set_flashdata('error', 'We are sorry. An error has occured. Please try again');
				redirect('you');
			}
			else
			{	
				$this->session->set_flashdata('success', 'Account deleted');
				redirect('logout');
			}
		}
		else
		{
			$this->data['title'] = 'Delete Account';
			$this->data['menu'] = $this->load->view('you/includes/menu', $this->data, TRUE);
			$this->load->view('header', $this->data);
			$this->load->view('account/delete', $this->data);
			$this->load->view('footer', $this->data);
		}
	
	}


	/*
	 * called by header location form
	 * passed a POST array of user-submitted string and a redirect url
	 * gets new location, updates session data and redirects back to page
	 */ 
	function relocate() 
	{
		if(!empty($_POST)) {
			$location = $this->input->post('header_relocation');
			$redirect = $this->input->post('relocate_redirect');

			$redirect = (!empty($redirect))? $redirect : 'welcome/home';

			
			// Geocode user input
			$this->load->library('geo');				
			$location = $this->geo->geocode($location);
			
			if(empty($location))
			{
				redirect($redirect);		
			} else {
				// Update user's session with new location
				$this->auth->update_session_location($location);
			}
			redirect($redirect);
		} else {
			redirect("welcome/home");
		}
	}

}

/* End of file account.php */
/* Location: ./controllers/account.php */
