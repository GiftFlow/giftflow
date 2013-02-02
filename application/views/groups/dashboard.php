<div class='row'>

	<div class='span4 chunk'>
		<h3>Groups</h3>
		<div class='groupsearch'>
		<form class='form-horizontal form_wrapper' method='post' action="" name='group_find' id='group_find'>
			<label for='find_group'>Find a Group</label>	
				<input type='text' id='group_q' name='group_q' />
				<input type='submit' value='submit' class='btn btn-primary'/>

			</form>
		</div>
		<div class='groups_results'>
			<?php echo UI_Results::groups(array(
						"results" => $groups,
						"row" => FALSE
				)); ?>
		</div>
		<div class='no_results' style='display:none;'>
			<h3>Sorry no results found.</h3>
		</div>
	</div>
	<div class='span6'>

		<?php if($logged_in) { ?>
			<a href="<?php echo site_url('groups/create'); ?>" class='btn btn-success'>Create Group</a>
		<?php } ?>
	</div>



</div>


<script type='text/javascript'>
$(function() { 
	GF.Ajax = {};
	GF.UI = {};


		GF.Ajax.process = function(data) {

			$('ul.results_list').empty();
			console.log(data);

			if(data.total_results > 0) {
				console.log('amost there!');
				$.each(data.results, function(key, val) {
					$('ul.results_list').append($(val.html))
				});
			} else {
				$('.no_results').show();
			}

		};

	// Send AJAX request
	GF.Ajax.request = function(q){
		var params = { 
			type: 'groups',
			q: q
		};
		console.log(params);
		$.post("<?php echo site_url('find/ajaxRequest'); ?>", params, GF.Ajax.process, "json");
	};

	$('#group_find').submit(function() {
		$('.no_results').hide();
		var q = $('#group_q').val();
		console.log(q);
		GF.Ajax.request(q);
		return false;
	});
		
});

</script>
