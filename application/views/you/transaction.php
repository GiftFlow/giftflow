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
				<p>Current Status: <span class='label label-info'><?php echo $transaction->status; ?></span></p>
			</div>
			<div class='span2'>
				<span class="updated css_right">
					Updated <?php echo user_date($transaction->updated,"n/j/o");?>
				</span>
			</div>
		</div>
		<div class='row-fluid'>
			<div class='span2'>
				<span class='label'>Step by Step Guide</span>
			</div>
			<div class='span10 chunk trans_helper'>
				<ul class='nav nav-pills' id='trans_nav'>
					<li <?php if($transaction->status == 'pending') { echo "class='active'"; }?> >
						<a id='nav_pending' href='#'>Pending</a>
					</li>
					<li <?php if($transaction->status =='active') { echo "class='active'"; } ?> >
						<a id='nav_active' href='#'>Active</a>
					</li>
					<li <?php if($transaction->status == 'completed') { echo "class='active'"; } ?> >
						<a id='nav_completed' href='#'>Completed</a>
					</li>
					<li <?php if($transaction->status =='declined' || $transaction->status == 'cancelled') { echo "active"; } ?> >	
						<a id='nav_cancelled' href='#'>Cancelled/Declined</a>
					</li>
				</ul>
				<div class='nav_text'>		
					<span class='nav_pending'><em>Pending: </em> The gift is "pending" until it has either been accepted or declined by the person who received the request. </span>
					<span class='nav_active'><em>Active: </em> Once the request has been accepted, the gift becomes "active".  Correspond via message, email or phone to set up a meeting. Choose a convient public location.</span>
					<span class='nav_cancelled'><em>Cancelled/Declined: </em> The recipient of the request can decline to participate. Likewise the iniitator can cancel the request. Not everything works out, but don't stop trying!</span>
					<span class='nav_completed'><em>Completed: </em> The gift interaction is completed when both sides write reviews of one another. Be sure to include details of the experience and feedback for the other person.</span>
				</div>
			</div>
	

		</div>
		<div class="inbox_summary chunk">
			<div class='row-fluid'>
			<div class='span12'>

		<!-- anything but cancelled or declined -->
			<?php if($transaction->status!="declined" && $transaction->status!="cancelled") { ?>
				<p class='nicebigtext' >What's Next</p>

				<!-- PENDING -->
					<?php if($transaction->status=="pending"){ ?>
						<?php if($demander){ ?>
							<p>Waiting for <?php echo $other_user->screen_name; ?> to respond</p>
							
								<form method="post" id="cancel_transaction">
									<input type="hidden" name="form_type" value="transaction_cancel" />
									<input type="submit" class="button btn btn-large btn-warning" value="Cancel" style="margin-top: 10px;" />
								</form>

							<?php } else { ?>
							<p>By clicking Accept, you agree to participate in this gift. Write <?php echo $other_user->screen_name; ?> a message if you have questions. </p>
							<p>Upon completion both of you will write public reviews of each other. This is a great way to build credibility and gratitude on GiftFlow. If you do not want to participate in the requested gift, simply click Decline and nothing will appear on your profile. </p>
							
								<form method='post' id='decide_transaction'>
									<input type='hidden' name='form_type' value='decide_transaction'/>
									<div class="btn-group">
									<input type="submit" class="left btn btn-large btn-success" name='decision' value="Accept" />
									<input type="submit" class="left btn btn-large btn-danger" name='decision' value="Decline" />
									</div>
								</form>
							<?php } ?>

				<!-- ACTIVE -->
					<?php } elseif ($transaction->status == 'active' && !$has_reviewed && !$other_reviewed) { ?>
							<p>	<a href="#" class="left btn" id="write_message">Write Message</a>
									Arrange a time and place for the gift to happen.
							</p>
							<p> <a href="#" id="write_review" class="left btn">Write Review</a>
									Write a review after the gift has taken place.
							</p>
					<?php } ?>
					<?php if ($transaction->status == 'active' && $other_reviewed) { ?>
							<p><?php echo $other_user->screen_name; ?> has written a review of you.</p>
								<p>Write a review of <?php echo $other_user->screen_name; ?>. Be detailed and sincere.
									<a href="#" id="write_review" class="left btn">Write Review</a>
									</p>
								<p>Has there been a misunderstanding? Anything to clear up?
									<a href="#" class="left btn" id="write_message">Write Message</a>
								</p>
					<?php } ?>
					<?php if($transaction->status == 'completed' && $has_reviewed && $is_owner) { ?>
						<p>Congratulations. You have completed a gift!! <?php echo $delete_prompt; ?></p>
						<a href="<?php echo $delete_link; ?>" class='left btn'>Delete <?php echo ucfirst($transaction->demands[0]->good->title); ?></a>
					<?php } ?>
				<?php } ?>
				<!-- cancelled or declined -->
				<?php if($transaction->status =='declined' || $transaction->status == 'cancelled') { ?>
					<p>Interaction with <?php echo $other_user->screen_name; ?> has been <?php echo $transaction->status; ?></p>
				<?php } ?>
			</div>
		</div>
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
			<!-- review form -->
			<?php if($transaction->status == "active" && !$has_reviewed) { ?>
				<li class='transaction_form message review' id='review_new' style='display:none;'>

					<?php echo $review_form; ?>
				</li>
			<?php } ?>
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
	$("a#write_message").click(function(){
		$('.transaction_form').hide();
		$('#message_new').show();
		$("#message_body").focus();
		return false;
	});
	$('a#write_review').click(function(){
		$('.transaction_form').hide();
		$('#review_new').show();
		$('#review_body').focus();
	});
});
</script>
