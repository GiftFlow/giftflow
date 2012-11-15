<div class ='row-fluid'>

	<div class='span12' id='landing_header'> 

		<p class='nicebigtext landing_text' style='text-align:center'>
			<span class='green'>Give</span> what you can.
			<span class='green'>&nbsp&nbspAsk</span> for what you need.
			<span class='green'>&nbsp&nbspPay</span> it forward.
		</p>
	</div>
</div>
<div class='row-fluid' id='landing_categories'>
	<div class='span12 center'>	
		<?php foreach($categories as $val) { ?>
		<a href="<?php echo site_url('find/gifts/?category_id='.$val->id);?>" title="<?php echo $val->name;?>" class='categoryIcon homepage medium-<?php echo $val->id;?>'>
			</a>
		<?php } ?>
	</div>
	<div class='span1'></div>
		

</div>
<div class='row'>
	<div  id='landing_search' class='span8 offset2'>

		<form class='form-search' method='post' action='<?php echo site_url("find/gifts"); ?>'>
				<div class='landing_input'>
				<input name='q'  class='landing_search_input input-large search-query' type='text' placeholder='What do you need?'>	
				<button value='submit' class='btn btn-large'>Search Gifts</button>
				</div>
		</form>
	</div>
</div>
<div class='row'>
	<div class='landing_search span8 offset2'>

		<form class='form-search' method='post' action='<?php echo site_url("find/needs"); ?>'>
				<div class='landing_input needBar'>
				<input name='q'  class='landing_search_input input-large search-query' type='text' placeholder='What can you give?'>	
				<button value='need' id='needSearch' class='typeToggle btn btn-large'>Search Needs</button>
				</div>
		</form>
	</div>
</div>
<div class='row-fluid'>
	</div>

</div>

<script type='text/javascript'>

$(function () {

$('.typeToggle').click( function() {
	var type = $(this).attr('value');
	$('#searchType').val(type); 
});

$('.categoryIcon').tooltip();

});

</script>
