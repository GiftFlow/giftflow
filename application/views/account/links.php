<div id='account_links' class='two_panels'>
	
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>

		<ul class='results_list' id='linked_accounts'>
			<?php if($links['facebook']['enabled']) { ?>
			<li class='active'>
				<span>Facebook: Account Linked</span>
				<a  class='follow unlink css_right' href='<?php echo site_url('account/unlink/facebook'); ?>'>Unlink</a>
			</li>
			<?php } else { ?>
			<li>
				<span>Facebook: Not Linked</span>
				<a  class='follow link css_right' href='<?php echo site_url('member/facebook'); ?>'>Link</a>
			</li>
			
			<?php } ?>
			<?php if($links['google']['enabled']) { ?>
			<li class='active'>
				<span>Google: Account Linked</span>
				<a  class='follow unlink css_right' href='<?php echo site_url('account/unlink/google'); ?>'>Unlink</a>
			</li>
			<?php } else { ?>
			<li>
				<span>Google: Not Linked</span>
				<a  class='follow link css_right' href='<?php echo site_url('account/link/google'); ?>'>Link</a>
			</li>
			<?php } ?>
		
			<li style='color: #ccc; font-style: italic;'>Twitter: Coming Soon</li>
			<li style='color: #ccc; font-style: italic;'>OpenID: Coming Soon</li>
		</ul>

	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->


<script type='text/javascript'>
$(function(){

	$(".link").button( { icons: { primary: 'ui-icon-link' } });
	$(".unlink").button( { icons: { primary: 'ui-icon-circle-close' } } );
});
</script>