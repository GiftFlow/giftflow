<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
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
		echo '<link rel="stylesheet" href="'.base_url().'assets/css/'.$val.'" />';
	}
}
?>

<!-- jQuery -->
<?php if($localhost) { ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/jquery.js"></script>
<?php } else { ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<?php } ?>

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
	<script type="text/javascript" src="<?php echo base_url().'assets/javascript/'.$val;?>"></script>
<?php } } ?>

<?php if(!empty($addthis) && $addthis == TRUE){ ?>
	<!-- AddThis -->
	<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<?php } ?>
</head>
<body>
<!-- hide_header is a flag set in giftflow/organize because to generate pages that people can print off into flyers, it looks better to not have the header appear --> 
<?php if(!isset($hide_header)) { ?>
<div id='header'>
	<div class='wrapper clearfix'>
		
		<!-- Logo -->
		<a href="<?php echo site_url(); ?>" id='logo'>
			<img src="<?php echo base_url(); ?>assets/images/gift_flow_beta.png" />
		</a>
		<div id='session'>
			
			<?php if(!empty($logged_in)&&$logged_in){ ?>
				<!-- Logged-in User You Menu -->
				<ul id='you_menu'>
					<li>
						<a href='<?php echo site_url('people/'.$logged_in_user_id); ?>' id='you'>
							<?php if(!empty($userdata['photo_thumb_url'])){ echo "<img src='".$userdata['photo_thumb_url']."' id='you_img'  />"; }  ?>
							<span style='float: left; '>Profile</span>
							<!--<span class='ui-icon ui-icon-triangle-1-s left' style="background-image: url('<?php echo base_url(); ?>assets/css/green/images/ui-icons_ffffff_256x240.png'); margin-left: 10px;"></span>-->
							<div style='clear: both;'>
							</div>
						</a>
						<ul id='you_dropdown'>
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
								<a href='<?php echo site_url('donate'); ?>'>
									Donate
								</a>
							</li>
							<li>
								<a href='<?php echo site_url('logout'); ?>'>
									Log Out
								</a>
							</li>
						</ul>
					</li>
				</ul>
			
			<?php } else { ?>
			
				<!-- Anonymous User Links -->
				<ul id='you_menu'>
					<li>
						<a href='<?php echo site_url('login'); ?>' id='login'>
							Login
						</a>
					</li>
					<li>
						<a href='<?php echo site_url('register'); ?>' id='signup'>
							Sign Up
						</a>
					</li>
					<li>
						<a href='<?php echo site_url('donate'); ?>' id='donate'>
							Donate
						</a>
					</li>
				</ul>
			<?php } ?>
		</div>

		<!-- Main Menu -->
		<ul id='nav'>
			<li>
				<?php if(!empty($logged_in)&&$logged_in){ ?>
				<a href="<?php echo site_url(); if($segment==false || $segment[1]=="you") echo '" class="active'; ?>">
					You
				</a>
				<?php } else { ?>
				<a href="<?php echo site_url(); if($segment==false) echo '" class="active'; ?>">
					Home
				</a>
				<?php } ?>
			</li>
			<li>
				<a href="<?php echo site_url('find/gifts/'); if($segment[1]=="gifts" || ($segment[1]=="find"&&$segment[2]=="gifts")) echo '" class="active'; ?>">
					Gifts
				</a>
			</li>
			<li>
				<a href="<?php echo site_url('find/needs/'); if($segment[1]=="needs" || ($segment[1]=="find" && $segment[2]=="needs")) echo '" class="active'; ?>">
					Needs
				</a>
			</li>
			<li>
				<a href="<?php echo site_url('people'); if($segment[1]=="people") echo '" class="active'; ?>">
					People
				</a>
			</li>
			<li>
				<a href="<?php echo site_url('about'); if($segment[1]=="about") echo '" class="active'; ?>">
					About
				</a>
			</li>
		</ul>
	</div>		
</div>
<?php } ?>

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
