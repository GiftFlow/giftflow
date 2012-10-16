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
	<div class='span12'>	
		<?php for($i=1; $i<13; $i++) { ?>
		<a href="<?php echo site_url('find/gifts/?category_id='.$i);?>" title='Browse our Gifts!' class='categoryIcon homepage medium-<?php echo $i;?>'>
			</a>
		<?php } ?>
	</div>
	<div class='span1'></div>
		

</div>
<div class='row'>
	<div  id='home_search' class='span8 offset2'>

		<form class='form-search' method='post' action='<?php echo site_url("find/gifts"); ?>'>
				<div class='homeInput'>
				<input name='q'  class='homeSearchInput input-large search-query' type='text' placeholder='What do you need?'>	
				<button value='submit' class='btn btn-large'>Search Gifts</button>
				</div>
		</form>
	</div>
</div>
<div class='row'>
	<div class='home_search span8 offset2'>

		<form class='form-search' method='post' action='<?php echo site_url("find/needs"); ?>'>
				<div class='homeInput needBar'>
				<input name='q'  class='homeSearchInput input-large search-query' type='text' placeholder='What can you give?'>	
				<button value='need' id='needSearch' class='typeToggle btn btn-large'>Search Needs</button>
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

$(function () {

$('.typeToggle').click( function() {
	var type = $(this).attr('value');
	$('#searchType').val(type); 
});

$('.categoryIcon').tipTip({ delay:0, fadein: 0});

});

</script>
