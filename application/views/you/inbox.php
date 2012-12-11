<div class='row' id='you_inbox'>

<div class='span2 chunk'>
	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
</div>
<div class='span9 chunk'>
		<?php if($show_welcome) { ?>
				<!--welcome view -->
				<p class='nicebigtext'> You don't have any messages! It's time to get with the flow.</p>
				<?php echo $welcome_view; ?>
		<?php } else { ?>
			<ul class= 'nav nav-tabs inbox_nav' id='inbox_tabs' data-tabs='tabs'>
			<li><a href="#gifts_pane" data-toggle="tab">Gifts (<?php echo $counts['gifts']['total'];?>)</a></li>
			<li><a href="#thanks_pane" data-toggle="tab">Thanks (<?php echo $counts['thanks']['total'];?>)</a></li>
			<li><a href="#messages_pane" data-toggle="tab">Conversations (<?php echo $counts['conversations']; ?>)</a></li>
			</ul>
			<div class='tab-content'>
				
				<!-- gifts/transactions tab pane -->
				<!-- jquery for showing results at bottom of file -->
				<div class="tab-pane" id="gifts_pane">
					<ul class="nav nav-pills" id='gifts_nav'>
						<li class='active'><a href="#all">All</a></li>
						<?php foreach($trans_status as $key=>$val) { ?>
							<li><a href="#<?php echo $key;?>"><?php echo ucfirst($key);?> (<?php echo $counts['gifts'][$key]; ?>)</a></li>
						<?php } ?>
					</ul>
					
					<?php if(!empty($transactions)) { ?>
						<?php echo UI_Results::inbox(array(
							"results" => $transactions,
							"type" => "transaction",
							"row" => FALSE
						)); ?>
					<?php } ?>

				</div> <!-- close gifts/transactions tab-pane -->


				<div class='tab-pane' id='thanks_pane'>
				
					<ul class="nav nav-pills" id='thanks_nav'>
						<li class='active'><a href="#all">All</a></li>
						<?php foreach($thank_status as $key=>$val) { ?>
							<li><a href="#<?php echo $key;?>"><?php echo ucfirst($key);?> (<?php echo $counts['thanks'][$key]; ?>)</a></li>
						<?php } ?>
					</ul>
					<?php if(!empty($thanks)) { ?>
						<?php echo UI_Results::inbox(array(
							"results" => $thanks,
							"type" => 'thank',
							"row" => FALSE
						));?>
					<?php } ?>
				</div><!-- close thanks tab-pane -->
				<div class='tab-pane' id='messages_pane'>
					<?php if(!empty($threads)) { ?>
				
						<?php echo UI_Results::inbox(array(
							"results" => $threads,
							"type" => "thread",
							'row' => FALSE
						)); ?>

					<?php } ?>
				</div><!-- close threads -->
			</div>
		<?php } ?>
	</div>
</div>

<script type='text/javascript'>
$(function(){

	GF.pills = function(tab, row) {
		$('#'+tab+" li a").click(function() {
			var stat = $(this).attr('href').substring(1);
			if(stat == 'all') {
				$('.'+row).show();
			} else {	
				$('.'+row).hide();
				$('.'+row+'.'+stat).show();
				console.log('.'+stat+'.'+row);
			}
			$('#'+tab+" li").removeClass('active');

			$(this).parent('li').addClass('active');

		});
	};


	GF.pills('gifts_nav','transaction_row');
	GF.pills('thanks_nav', 'thank_row');


	$('#inbox_tabs').tab();
	$("img.status_icon").tooltip();
});
</script>
