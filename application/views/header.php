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
<script type="text/javascript" src="<?php echo base_url();?>assets/javascript/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/javascript/bootstrap-dropdown.js"></script>


<!-- Javascript UI Compilation -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/ui.php"></script>

<!-- GF Javascript namespace declaration -->
<script type="text/javascript">
	var GF = {};
	
	GF.siteURL = function(str){
		var base = "<?php echo site_url();?>";
		return base + str;
	};
</script>


<?php if(!empty($googlemaps)&&$googlemaps==TRUE){ ?>
	<!-- Google Maps API -->
	<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>
	<script type='text/javascript' src="<?php echo  base_url();?>assets/javascript/fluster.php"></script>
<?php }?>

<?php if(isset($js)){ foreach($js as $val){ ?>
	<!-- Custom JavaScript Files -->
	<script type="text/javascript" src="<?php echo base_url('assets/javascript/'.$val);?>"></script>
<?php } } ?>

<?php if(!empty($addthis) && $addthis == TRUE){ ?>
	<!-- AddThis -->
<?php } ?>
	                
	<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
	<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=giftflow"></script>
			
</head>
<body>
<div id='header'>
	<div class='wrapper clearfix'>
		
		<!-- Logo -->
		<a href="<?php echo site_url(); ?>" id='logo'>
			<img src="<?php echo base_url(); ?>assets/images/gift_flow_beta.png" />
		</a>
		<div id='session'>
			
			<?php if(!empty($logged_in)&&$logged_in){ ?>
			<ul id='boot_menu'>
				<li class='dropdown'>
					<!-- Logged-in User You Menu -->
					<div class='btn-group'>
					<a  class='btn btn-success' href='<?php echo site_url("people/".$logged_in_user_id);?>'>
						<?php if(!empty($userdata['default_photo_thumb_url'])){echo "<img src='".$userdata['default_photo_thumb_url']."' id='you_img' />";}?>
							Profile
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
			</li>
		</ul><!-- close boot_menu -->

			<?php } else { ?>
				<!-- Anonymous User Links -->
				<ul id='anonymous-menu' >
					
					<li class='dropdown'>
						<a href='#' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
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
											<input type='hidden' name='redirect' value="<?php echo current_url(); ?>" />
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

					</li>
					<li>
						<a href='<?php echo site_url("register"); ?>' id='signup' class='btn btn-success'>
							Sign Up
						</a>
					</li>
					<li>
						<a href='<?php echo site_url("donate"); ?>' id='donate' class='btn btn-success'>
							Donate
						</a>
					</li>
				</ul><!-- close anonymous menu -->
			<?php } ?>
		</div><!-- close session -->

		<!-- Main Menu -->
		<ul id='nav'>
			
				<?php if(!empty($logged_in)&&$logged_in){ ?>
				<li <?php if($segment==false || $segment[1]=="you") echo 'class="active"'; ?>>
				<a href="<?php echo site_url();?>"">
					You
				</a>
				</li>
				<?php } else { ?>
				<li <?php if($segment==false) echo 'class="active"'; ?>>
				<a href="<?php echo site_url();?>">
					Home
				</a>
				</li>
				<?php } ?>

			<li <?php if($segment[1]=="gifts" || ($segment[1]=="find"&&$segment[2]=="gifts")) echo '" class="active"'; ?>>
				<a href="<?php echo site_url('find/gifts/');?>">
					Gifts
				</a>
			</li>
			<li <?php if($segment[1]=="needs" || ($segment[1]=="find" && $segment[2]=="needs")) echo '" class="active"'; ?>>
				<a href="<?php echo site_url('find/needs/');?>">
					Needs
				</a>
			</li>
			<li <?php if($segment[1]=="people") echo '" class="active"'; ?>>
				<a href="<?php echo site_url('find/people');?>">
					Members
				</a>
			</li>
			<li <?php if($segment[1]=="about") echo 'class="active"'; ?>>
				<a href="<?php echo site_url('about');?>">
					About
				</a>
			</li>
		</ul>
	</div>		
</div>

<!-- script for logIn dropdown -->
<script type='text/javascript'>
$('.dropdown-toggle').dropdown();
$('#login-form').css('left', '-50px');
$('.dropdown-menu').find('form').click(function (e) {
	e.stopPropagation();
});
</script>


<!-- Main Wrapper -->
<div id="main">

	<div class='wrapper clearfix'>

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
