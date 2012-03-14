<div class='two_panels'>

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
	
		<!-- Search Form Module -->
		<div class='search_module clearfix'>
			<form name='find_people' id="find_people" action="" method='post'>
			
				<p class='css_left'>
					<input type='text' class='big-border' id="keyword" name='keyword' value='' />
				</p>
				<p class='css_left'>
					<input class='button' type='button' id="find" value='Find' />
				</p>
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


	//$("#find_people").ajaxForm();
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



	$(".follow").click(function(){ 
		var id = $(this).attr('rel');
		$.post("<?php echo site_url('people/follow/'); ?>/"+id);
		$(this).after("<div style='float: right;'><span style='float: left; margin-right: 8px; margin-top: 1px;' class='ui-icon ui-icon-check'><\/span><span style='font-size: .9em; color: #666;'>Following<\/span><\/div>");
		$(this).remove();
		return false;
	});

	$(".button").button();
	$(".follow").button( { icons: { primary: 'ui-icon-plusthick'}   } );
});
</script>
