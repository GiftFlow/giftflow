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
	<div id='session' class='row-fluid'>
		<div id='header_logo' class='span2'>
			<!-- Logo -->
			<a href="<?php echo site_url(); ?>" id='logo'>
				<img src="<?php echo base_url(); ?>assets/images/gift_flow_beta.png" />
			</a>
		</div>
		<div class='span6' id='home_find_about'>
			<ul id='nav'>
			<li class='nav_li'>
					<a class='nav_a' href="<?php echo site_url('find/?type=gift'); ?>">
						Gifts
					</a>
				</li>
				<li class='nav_li'>
					<a class='nav_a' href="<?php echo site_url('find/?type=need'); ?>">
						Needs
					</a>
				</li>
				<li class='nav_li'>
					<a class='nav_a' href="<?php echo site_url('about/press'); ?>">
						About
					</a>
				</li>
			</ul>
			</div>

			<?php if(!empty($logged_in) AND $logged_in){ ?>
			<div id='header_actions' class='span4'>
				<div class='btn-group pull_right'>
					<a id='add_button' href='#' data-toggle='modal' data-target='#addModal' class='btn btn-large btn-success'>
						<i class='icon-plus icon-white'></i>Post
					</a>
					<a  class='btn btn-large btn-success' href='<?php echo site_url("you/activity");?>'>
							<?php if(!empty($userdata['default_photo_thumb_url'])){ ?>
								<img src="<?php echo $userdata['default_photo_thumb_url']; ?>" id='you_img'/>
							<?php } ?>
							<?php echo $userdata['display_name']; ?>
							
					</a>

					<button class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
						<span class='caret'></span>
					</button>

						<ul class='dropdown-menu' id='logged_in_dropdown'>
							<li>
								<a href='<?php echo site_url('you/index'); ?>'>
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
			</div> <!-- close header actions-->
			<?php } else { ?>
				<!-- Anonymous User Links -->
										
				<div class= 'span3' id='visitor_buttons'>
					<div class='btn-group pull_right'>
				
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
											<input type="text" name='email' class='required email input-medium' id='email' value='' />
											<label for='password'>Password</label>
											<input type='password' name='password' class='required input-medium' id='password' value='' />
										</fieldset>
										<fieldset id='actions'>
										<input type='hidden' name='redirect' value="<?php echo $dropdown_login_redirect; ?>" />
											<input type='submit' class='btn btn-primary btn-large' value="Login" />
										</fieldset>
						
									</form>
								</a>
							</li>
							<?php if(!empty($fbookUrl)) { ?>
							<!-- Facebook Link -->
							<li>	
								<a href="<?php echo $fbookUrl; ?>"  class='noborder' id='dropfbook'>
									<img class='noborder' src='<?php echo site_url("assets/images/facebook_logo.jpeg");?>' style='border: 0; width:100px;' />
								</a>
							</li>
							<!-- eof Facebook Link -->
							<?php } ?>
							
							<li id='dropforgot'>
								<a href="<?php echo site_url('member/forgot_password'); ?>">Forgot your password?
								</a>
							</li>
						</ul>
						<a href='<?php echo site_url("register"); ?>' id='signup' class='btn btn-success btn-large'>
							Sign Up
						</a>
					</div>
				</div><!-- close visitor buttons -->
			<?php } ?>
		</div>		
	</div>
</div><!-- close header -->


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

			<ul id='add_actions'>
				<li>
				<a href="<?php echo site_url('you/add_good/gift');?>" class='btn btn-large'>Add Gift</a>
					<span>What can you offer the GiftFlow community?</span>
				</li>
				<li>
				<a href="<?php echo site_url('you/add_good/need');?>" class='btn btn-large btn-danger'>Add Need</a>
					<span>What do you need? Ask away!</span>
				</li>
				<li>
					<a href="<?php echo site_url('you/add_thank'); ?>" class='btn btn-large btn-success'>Thank Someone</a>
					<span>Try thanking a friend who doesn't yet use GiftFlow!</span>
				</li>
				<li> 
				<a href="<?php echo site_url('you/watches');?>" class='btn btn-large btn-info'>Add Watch</a>
					<span>Receive custom notifications.</span>
				</li>
				<li>
					<a href='#' class='btn btn-large btn-primary disabled'>Invite Friends</a>
					<span>Coming Soon!</span>
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
				<span class='ui-icon ui-icon-triangle-1-e breadcrumb_delimiter'></span>
				
			<?php } else { ?>
			
				<span class='current'>
					<?php echo $crumb['title']; ?>
				</span>
				
			<?php } ?>
		</li>
		<?php } ?>
	</ul>
<?php } ?>
