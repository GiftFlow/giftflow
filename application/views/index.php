<div class ='row-fluid'>

	<div class='span12' id='home_header'> 

		<p class='nicebigtext home_text' style='text-align:center'>
			<span class='green'>Give</span> what you can.
			<span class='green'>&nbsp&nbspAsk</span> for what you need.
			<span class='green'>&nbsp&nbspPay</span> it forward.
		</p>
	</div>
</div>
<div class='row-fluid' id='home_categories'>
	<div class='span12 thumb_grid'>	
		<a href='#' class='result_image'>
			<img src ="<?php echo site_url('assets/images/categories/16.png'); ?>"/>
		</a>
		<?php for($i=1; $i<17; $i++) { ?>
		<a href="<?php echo site_url('find/gifts/?category_id='.$i);?>" class='result_image'>
			<img src="<?php echo site_url('assets/images/categories/'.$i.'.png'); ?>"/>
			</a>
		<?php } ?>
	</div>
	<div class='span1'></div>
		

</div>
<div class='row'>
	<div  id='home_search' class='span8 offset2'>

			<p class='nicebigtext home_text'>See what we GiftFlowers have to offer.</p>
		<form class='form-search' method='post' action='<?php echo site_url("find/"); ?>'>
				<div id='homeInput'>
				<input name='q'  class='input-large search-query' id='homeSearchInput' type='text' placeholder='Search'>	
				<input type='hidden' id='searchType' name='type' value='gifts'/>
				<button value='gift' id='giftSearch' class='typeToggle btn btn-large'>Gifts</button>
				<button value='need' id='needSearch' class='typeToggle btn btn-large'>Needs</button>
				</div>
		</form>
	</div>
</div>
<div class='row-fluid'>
<!--
			<div class='span12'>			
					<a class='btn btn-action btn-medium' id='register' href='<?php echo site_url("member/register"); ?>'>Sign Up Now</a>
					<a class='flip btn btn-medium' id='login_flip' href='#'>Log In</a>
					<p id='home_copy'>GiftFlow is a non-profit. Please <a href='about/donate'>Donate Here</a></p>
			</div>
-->
	</div>

</div>

<script type='text/javascript'>

$('.typeToggle').click( function() {
	var type = $(this).attr('value');
	$('#searchType').val(type); 
});



</script>
