<div class='row'>
	<div class ='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span9'>
		<div class='row-fluid inbox_summary chunk'>
			<div class='span2'> 
				<a href="<?php echo site_url('people/'.$other_user->id);?>" class="user_image medium left">
					<img src="<?php echo $other_user->default_photo->thumb_url; ?>" alt="<?php echo $other_user->screen_name;?>" />
				</a>
			</div>
			<div class='span8'>
				<span class='summary'>
				<?php echo $demander ? $transaction->language->demander_summary : $transaction->language->decider_summary; ?>
				</span>
				<p>Current Status: <span class='label label-info'><?php echo $transaction->status; ?></span>
				</p>
			</div>
			<div class='span2'>
				<span class="updated css_right">
					Updated <?php echo user_date($transaction->updated,"n/j/o");?>
				</span>
			</div>
		</div>
		<div class='row-fluid chunk'>
			<div class='span12 interact_buttons'>
					<p>
						<button id='write_message' class='interact btn btn-medium'><i class='icon icon-pencil'></i>Message</button>
						<span class='helper_text'>Message <?php echo $other_user->screen_name; ?></span>
					</p>
				<?php if($transaction->status != 'completed' && $transaction->status != 'cancelled') { ?>
					<p>
					<a  href="<?php echo site_url('you/update_transaction/completed/'.$transaction->id); ?>" id='confirm_button' class='interact btn btn-medium btn-success'><i class='icon icon-white icon-check'></i>Gift Completed</a>
						<span class='helper_text'>Click this button when the gift is completed. </span>
					</p>
				<?php } ?>
				<?php if ($transaction->status == 'completed' && !$has_reviewed) { ?>
					<p>
						<button id='write_review' class='interact btn btn-medium btn-success'><i class='icon icon-pencil icon-white'></i>Write Review</button>
						<span class='helper_text'>Write a review of your gift interaction. Be sure to include details.</span>
						<?php if($other_reviewed) { ?>
							<span class='helper_text error'><?php echo $other_user->screen_name; ?> has written a review of you. You should write on in return. </span>	
						<?php } ?>
					</p>
				<?php } ?>
				<?php if($transaction->status != 'cancelled' && $transaction->status != 'completed') { ?>
					<p class='cancel_bar'> 
						<span class='mini_helper_text'>Is this interaction not going to happen? If so, cancel it. </span>
						<a class='btn btn-small' href="<?php echo site_url('you/update_transaction/cancelled/'.$transaction->id); ?>" >Cancel</a>
						
					</p>
				<?php } ?>
									
				<?php if($transaction->status == 'completed' && $has_reviewed) { ?>
					<p>
						Congratulations. You have completed a gift!! 
					<?php if($transaction->demands[0]->good->status != 'disabled') { ?>	
						<?php echo $delete_prompt; ?></p>
						<a href="<?php echo $delete_link; ?>" class='left btn'>Disable <?php echo ucfirst($transaction->demands[0]->good->title); ?></a>
					<?php } else { ?>
						<b><?php echo $transaction->demands[0]->good->title; ?></b> is no longer listed.
					<?php } ?>
					</p>
				<?php } ?>

				<!-- cancelled or declined -->
				<?php if($transaction->status =='declined' || $transaction->status == 'cancelled') { ?>
					<p>Interaction with <?php echo $other_user->screen_name; ?> has been <?php echo $transaction->status; ?>.</p>
				<?php } ?>
			</div>
		</div>
	<div class='chunk' id='review_form' style='display:none;'>
		<?php echo $review_form; ?>
	</div>
	<div class='chunk'>
		<ul class='interaction'>

			<?php if(!empty($transaction->reviews)) { ?>
				<li class='section_header'>
					<h3 class='inbox_title'>Reviews</h3>
				</li>
				<?php foreach($transaction->users as $use) { ?>
					<?php foreach($transaction->reviews as $rev) { ?>
						<?php if($use->id == $rev->reviewer_id) { ?>
							<li>
								<div class='row-fluid'>
									<div class='span2'>
										<a href='#' class='user_image medium css_left'>
											<img src="<?php echo $use->default_photo->thumb_url; ?>" alt="<?php echo $use->screen_name;?>" />
										</a>
									</div>
									<div class='span6 result_text'>
									<p>
										<a href="<?php echo site_url('people/profile/'.$use->id); ?>" >
											<?php echo $use->screen_name;?>
										 </a>
									</p>
										<?php echo $rev->body; ?>
									<p>
											Rating: <?php echo ucfirst($rev->rating); ?>
									</p>
								</div>
							</li>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
	<div class='chunk'>
		<ul class='interaction'>

			<!-- eof review form-->
			<li class='section_header'>
				<h3 class="messages_title">Messages</h3>
			</li>
									
			<?php foreach($transaction->messages as $key=>$M) { ?>
				<?php $author = ($other_user->id == $M->user_id) ?
					$other_user : $current_user; ?>
				<li>
					<div class='row-fluid'>
						<div class='span2'>		
							<a href="#" class="user_image medium">
								<img src="<?php echo $author->default_photo->thumb_url; ?>" alt="<?php echo $author->screen_name;?>" />
							</a>					
						</div>
						<div class="span4">
							<a href="<?php echo site_url('people/'.$M->user_id);?>" class="metadata-author clearfix">
								<?php echo $M->user->screen_name;?>
							</a>
						</div>
						<div class='span8 body'>
							<?php echo $M->body; ?>
							<p class="metadata-date left">
								<?php echo time_ago(strtotime($M->created)); ?>
							</p>
						</div>
					</div>
				</li>
			<?php } ?>
				
					<!-- transaction message form -->
					<li class="transaction_form message" id='message_new'>
						<form action="<?php echo site_url('you/view_transaction/'.$transaction->id); ?>" id="transaction_reply" name="transaction_reply" method="post">
							<input type="hidden" name="form_type" value="transaction_message" />
							<input type="hidden" name="transaction_id" value="<?php echo $transaction->id; ?>" />
							<input type="hidden" name="user_id" value="<?php echo $logged_in_user_id; ?>" />
							<label>Send a Message</label>
							<textarea rows="5" name="body" id="message_body" class='message_form big-border'></textarea>
							<input type="submit" value="Send" class="btn btn-primary" />
					</form>
				</li>
		</ul>
		</div>
	</div>
	
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){

	$('#'+<?php echo $helper_text; ?>).show();

	var active = $('#trans_nav').find('li.active a');
	$('.'+active.attr('id')).show();

	$('#trans_nav li a').click(function() {
		$('.nav_text span').hide();
		var panel = $(this).attr('id');
		console.log(panel);
		$('.'+panel).show();
	});

	$("form#review").validate({
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error').removeClass('success');
	  	},
	  	success: function(label) {
		  	label.hide().closest('.control-group').addClass('success');
	  	}
	});
	$("#write_message").click(function(){
		$('#message_new').show();
		$("#message_body").focus();
		return false;
	});
	$('#write_review').click(function(){
		$('#review_form').show();
		$('#review_body').focus();
	});
	$('#review_cancel').click(function() {
		$('#review_form').hide();
	});
});
</script>
