	
<div class='row' id='landing_page'>
	<div class ='row-fluid'>
			<div class='span12' id='landing_header'> 
				<p class='nicebigtext landing_text' style='text-align:center'>
					<span class='green'>Give</span> what you can.
					<span class='green'>&nbsp&nbspAsk</span> for what you need.
					<span class='green'>&nbsp&nbspPay</span> it forward.
				</p>
			</div>
		</div>
		<div class='row' id='landing_categories'>
			<div class='span10 offset1 center category_box'>	
				<?php foreach($categories as $val) { ?>
				<a href="<?php echo site_url('find/gifts/?category_id='.$val->id);?>" title="<?php echo $val->name;?>" class='result_sprite category_icon medium-<?php echo $val->id;?>'>
					</a>
				<?php } ?>
			</div>
			<div class='span1'></div>
				

		</div>
		<div class='row'>
			<div class='span8 offset2 landing_search'>

				<form class='form-search' method='post' action='<?php echo site_url("find/gifts"); ?>'>
						<div class='landing_input'>
						<input name='q'  class='landing_search_input input-large search-query' type='text' placeholder='What do you need?'>	
						<button value='submit' class='btn btn-large'>Search Gifts</button>
						</div>
				</form>
			</div>
		</div>
</div>
<script type='text/javascript'>

$(function () {

$('.typeToggle').click( function() {
	var type = $(this).attr('value');
	$('#searchType').val(type); 
});

$('.category_icon').tooltip();

});

</script>
