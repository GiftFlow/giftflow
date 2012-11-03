<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="google-site-verification" content="2ILeElWPtRUAwihUuo_10yI5t1wSD-I4cKTXdapL7n8" />

	<?php 
	// Special meta tags used by the Facebook Open Graph.
	// Defaults defined in Util::parse_globals()
	foreach($open_graph_tags as $property=>$content)
	{
		if(!empty($content))
		{
			echo "<meta property='{$property}' content='{$content}' />\n";
		}
	}
	?>

	<title>GiftFlow | <?php echo $title; ?></title>
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.min.css' />
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/css/style.css' />
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/css/categorySprites.css' />
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/css/silver/jquery-ui.php' />
	
	<?php 
	// output rss feed links if provided, default links if not
	if(!empty($rss))
	{ 
		echo $rss; 
	} 
	else 
	{ 
		echo '<link rel="alternate" type="application/rss+xml" title="Latest Gifts" href="'.site_url('rss/gifts').'" /><link rel="alternate" type="application/rss+xml" title="Latest Needs" href="'.site_url('rss/needs').'" />'; 
	} 
	?>

<?php
if(isset($css))
{
	foreach($css as $val)
	{
		echo '<link rel="stylesheet" href="'.base_url('assets/css/'.$val).'" />';
	}
}
?>

<!-- jQuery -->
<?php if($localhost) { ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/jquery.js"></script>
<?php } else { ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php } ?>

<!-- GF Javascript namespace declaration -->
<script type="text/javascript">
	var GF = {};
	
	GF.siteURL = function(str){
		var base = "<?php echo site_url();?>";
		return base + str;
	};
</script>
		
</head>
<body>
<div id='header'>
	<div class='wrapper clearfix'>

<!--- NOTE MUCH OF THE JAVASCRIPT FOR THESE UI ELEMENTS SUCH AS THE DROPDOWN AND LOCATION BAR IS IN footer.php -->		

		<!-- Logo -->
		<a href="<?php echo site_url(); ?>" id='logo'>
			<img src="<?php echo base_url(); ?>assets/images/gift_flow_beta.png" />
		</a>
		<div id='session'>
			
			<?php if(!empty($logged_in) AND $logged_in){ ?>

				<div class='btn-group' id='header_actions'>
					<a href='#' data-toggle='modal' data-target='#addModal' class='btn btn-large btn-success'>
						<i class='icon-plus icon-white'></i>Add
					</a>

					<a href="<?php echo site_url('you/inbox');?>" class='btn btn-large btn-success'>
						<i class="icon-envelope icon-white <?php if(!$activeInbox) { echo 'empty';}?>">
							</i>Inbox
					</a>

					<a href="<?php echo site_url('find/gifts'); ?>" class='btn btn-large btn-success'>
						<i class='icon-search icon-white'></i>Find
					</a>

					<a href="<?php echo site_url('welcome/home'); ?>" class='btn btn-large btn-success'>
						<i class='icon-home icon-white'></i>Home
					</a>
					<a  class='btn btn-large btn-success' href='<?php echo site_url("people/".$logged_in_user_id);?>'>
							<?php if(!empty($userdata['default_photo_thumb_url'])){echo "<img src='".$userdata['default_photo_thumb_url']."' id='you_img' />";}?>
							<?php echo substr($userdata['screen_name'],0,25); ?>
					</a>

					<button class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
						<span class='caret'></span>
					</button>

						<ul class='dropdown-menu'>
							<li>
								<a href='<?php echo site_url(''); ?>'>
									Dashboard
								</a>
							</li>
							<?php if ($this->auth->validate(100)) { ?>
							<li>
								<a href='<?php echo site_url('admin'); ?>'>
									Admin Area
								</a>
							</li>
							<?php } ?>
							<li>
								<a href='<?php echo site_url('account'); ?>'>
									Settings
								</a>
							</li>
							<li>
							<a href='<?php echo site_url('logout'); ?>'>
								Log Out
							</a>
						</li>
					</ul>

				</div>
			<?php } else { ?>
				<!-- Anonymous User Links -->
										
				<div class='btn-group'>
					<a href="<?php echo site_url('welcome/home'); ?>" class='btn btn-large btn-success'>
						<i class='icon-home icon-white'></i>Home
					</a>
					<a href="<?php echo site_url('find'); ?>" class='btn btn-large btn-success'>
						<i class='icon-search icon-white'></i>Find
					</a>
					<a href='#' class='btn btn-large btn-success dropdown-toggle' data-toggle='dropdown'>
						Login
						<b class='caret'></b>
					</a>
						<ul class='dropdown-menu' id='login-form'>
							<li>
								<a href='#' style='background-color: transparent; !important'>
								<form id='drop_login' action="<?php echo site_url('member/login'); ?>" method='post'>
										<fieldset id='inputs'>
											<label for='email'>Email</label>
											<input type="text" name='email' class='required email span3' id='email' value='' />
											<label for='password'>Password</label>
											<input type='password' name='password' class='required span3' id='password' value='' />
										</fieldset>
										<fieldset id='actions'>
										<input type='hidden' name='redirect' value="<?php echo $redirect_url; ?>" />
											<input type='submit' class='btn btn-primary btn-large' value="Login" />
										</fieldset>
						
									</form>
								</a>
							</li>
							<li>	
								<a href="<?php echo $fbookUrl; ?>"  class='noborder' id='dropfbook'>
									<img class='noborder' src='<?php echo site_url("assets/images/facebook_logo.jpeg");?>' style='border: 0; width:100px;' />
								</a>
							</li>
							<li id='dropforgot'>
								<a href="<?php echo site_url('member/forgot_password'); ?>">Forgot your password?
								</a>
							</li>
						</ul>

					<a href='<?php echo site_url("register"); ?>' id='signup' class='btn btn-large btn-success'>
						Sign Up
					</a>
				</div>
			<?php } ?>
		</div><!-- close session -->

		<!-- Main Menu -->
		<ul id='nav'>
			<li>

			<a class='btn btn-success' title= 'Click here' id='header_location' href="#">
					<span id='header_location_text'><?php echo $header_location; ?></span><i style='margin: 3px 0px 0px 7px;' class='icon-refresh icon-white'></i>
			</a>
		

			<div style='display:none;' id ='relocate_form'>
			<form name='relocate' class='find_form' id="relocate" method="post" action="">
					<div class='input-append'>
					<input id ='header_relocate' size='16' class='input-medium' type="text"  placeholder="" name="location" />
						<button  id='relocate_button' type='submit' class='btn btn-medium'><i class= 'icon-refresh'></i>Relocate</button>
						<button id='relocate_cancel' class='btn btn-small'><i class='icon-remove'></i></button>
					</div>
				</form>
			</div>
			</li>	
		</ul>
	</div>		
</div>


<!-- Main Wrapper -->
<div id="main">

	<div class='wrapper clearfix'>

	<!-- Add menu modal window -->
	<div class='modal hide' id='addModal'>
		<div class='modal-header'>
			<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>x</button>
			<p class='nicebigtext' style ='text-align:center;'>The many ways to participate on GiftFlow</p>
		</div>
		<div class='modal-body'>

			<ul id='addActions'>
				<li>
				<a href="<?php echo site_url('you/add_good/?type=gift');?>" class='btn btn-large'>Add Gift</a>
					<span>What can you offer the GiftFlow community?</span>
				</li>
				<li>
				<a href="<?php echo site_url('you/add_good/?type=need');?>" class='btn btn-large btn-danger'>Add Need</a>
					<span>What do you need? Ask away!</span>
				</li>
				<li>
					<a href="<?php echo site_url('thank/addThankForm'); ?>" class='btn btn-large btn-success'>Thank Someone</a>
					<span>Try thanking a friend who doesn't yet use GiftFlow!</span>
				</li>
				<li> 
				<a href="<?php echo site_url('you/watches');?>" class='btn btn-large btn-info'>Add Watch</a>
					<span>Receive custom notifications.</span>
				</li>
				<li>
					<a href='#' class='btn btn-large btn-primary'>Invite Friends</a>
					<span>The more the merrier.</span>
				</li>
			</ul>

		</div>
		<div class='modal-footer'>
			<a href='#' data-dismiss='modal' class='btn'>Close</a>
		</div>
	</div><!-- close Add Modal -->

<?php
// Output flashdata as javascript variable so that it can be displayed in jQuery
$flashdata_success = $this->session->flashdata('success');
$flashdata_error = $this->session->flashdata('error');

if (!empty($flashdata_success)) 
{
	if(is_array($flashdata_success))
	{
		$array = $flashdata_success;
		$flashdata_success = '';
		foreach($array as $val) $flashdata_success .= $val." ";
	}
	echo "<noscript><p class='alert_success'>";
	echo $flashdata_success;
	echo "</p></noscript>";
	echo '<script type=\'text/javascript\'> var alert_success = "';
	echo $flashdata_success;
	echo '";</script>';
}
else
{
	echo "<script type='text/javascript'>var alert_success=false;</script>";
}

if (!empty($flashdata_error)) 
{
	if(is_array($flashdata_error))
	{
		$array = $flashdata_error;
		$flashdata_error = '';
		foreach($array as $val)
		{
			$flashdata_error .= $val." ";
		}
	}
?>
	<noscript>
		<p class="alert_error">
			<?php echo $flashdata_error; ?>
		</p>
	</noscript>
	<script type="text/javascript">
		var alert_error = "<?php echo $flashdata_error; ?>";
	</script>

<?php } else { ?>
	<script type='text/javascript'>
		var alert_error=false;
	</script>
<?php } ?>


<?php if(!empty($breadcrumbs)) { ?>
	<!-- Breadcrumbs -->
	<ul class="breadcrumbs clearfix">
		<?php foreach($breadcrumbs as $crumb) { ?>
		<li>
			<?php if(!empty($crumb['href'])) { ?>
			
				<a href='<?php echo $crumb['href']; ?>'>
					<?php echo $crumb['title']; ?>
				</a>
				<span class='ui-icon ui-icon-triangle-1-e' id='breadcrumb_delimiter'></span>
				
			<?php } else { ?>
			
				<span class='current'>
					<?php echo $crumb['title']; ?>
				</span>
				
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
<?php } ?>
