<!-- Simple FIND cover page -->

<div class='row' id='simple_find'>
	<div class='span6 offset2'>
		<h1>Search GiftFlow</h1>
		
		<form class='form-horizontal' id='simple_find_form' method='post' action="<?php echo site_url('find/simple_find'); ?>"  name='simple_find_form'>
			<div class='control-group'>	
				<label for='simple_type' class='control-label'>I am looking for: </label>
				<div id='simple_type' class='controls'>
					<!-- these buttons don't actually submit a value, the javascript sets the hidden input value -->
					<button type='button' class='btn type_control active' value ='gift'>Gifts</button>
					<button type='button' class='btn type_control' value ='need'>Needs</button>
					<button type='button' class='btn type_control' value ='people'>Users</button>
					<input name='type' type='hidden' id='find_type' value='gift'/>
				</div>
			</div>
			<div class='control-group simple_find'>
				<label class='control-label' for='simple_location'>Near: </label>
				<div class='controls'>
					<input class='simple_input' type='text' name='location' id='simple_location' value="<?php echo $header_location; ?>"/>
				</div>
			</div>
			<div class='control-group simple_find'>
				<label class='control-label' for='simple_keywords'>Keywords:</label>
				<div class='controls'>
				<input class='simple_input' type='text' name='q' id='simple_keywords' value='' placeholder="repair, help, conversation, etc..."/>
				</div>
			</div>
			<div class='control-group'>
				<div class='controls'>
				<button class='btn btn-primary btn-large' type='submit'>Search</button>
				</div>
			</div>

		</form>
	</div><!-- close search form -->
	<div class='row'>
		<div class='span6 offset2' id='simple_categories'>
		<h1>Browse Categories</h1>
			<div class='center category_box'>
				<?php foreach($categories as $val) { ?>
				<a href="<?php echo site_url('find/gifts/?category_id='.$val->id);?>"  class='category_icon result_sprite medium-<?php echo $val->id;?>' title="<?php echo $val->name; ?>">
					</a>
				<?php } ?>
			</div>
		</div>


</div>
</div>

<script type='text/javascript'>

$(function() {

	$('.category_icon').tooltip();

	$('.type_control').click(function() {
		$('.type_control').removeClass('active');
		$(this).addClass('active');

		var type = $(this).val();
		var holder = '';

		if(type == 'gift' ||type == 'need') {
			holder = "repair, help, bike, etc...";
		} else { 
			holder = "susan, soup kitchen, etc...";
		}
		console.log(holder);
	

		$("#simple_keywords").attr('placeholder', holder);


		console.log(type);
		$('#find_type').val(type);

	});
	$('#simple_location').click(function() {
		$(this).val('');
	})
	GF.Locations.initialize($('#simple_location'));




});

</script>
