<div class='two_panels'>

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	
		<!-- Search Form Module -->
		<div class='search_module clearfix'>
			<form name='find_people' id="find_people" class="well form-search" action="" method='post'>
				<input type='text' class='search-query' id="keyword" name='keyword' value='' />
				<input class='btn btn-primary' type='button' id="find" value='Find' />
			</form>
		</div>
		<!-- eof Search Form Module -->
		
		<!-- Search Results -->
		<div class='search_results'>
			<ul id="results_list" class="results_list"></ul>
				
		</div>
		<!-- eof Search Results -->
		
	</div>
	<!-- eof div.right_content -->
	
</div>
<!-- eof div.two_panels -->

<script>
$(function(){

	// NB: follow button listener function located in footer view

	var ul = $('#results_list');

  var send = function send_form() {
		ul.empty();
		$.post("<?php echo site_url('ajax/find_people'); ?>", { keyword: $('#keyword').attr('value') },function(data) {
				if(data)
				{
					$.each(data.results, function( key, val){
							ul.append($(val.html));
						});
				}
				
				else
				{
					ul.append('<li>We\'re sorry but your search did not return any results</li>');
				}
			
			}, 'json');
		};

  $('#find').click(function() {
    send();
    return false;
  });
  $('#find_people').submit(function() {
    send();
    return false;
  });


});
</script>
