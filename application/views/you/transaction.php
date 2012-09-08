<div id="transaction-view" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
		<div class='clearfix transaction-summary'>
				
				<div class="clearfix">
					<a href="<?php echo site_url('people/'.$other_user->id);?>" class="user_image medium left">
						<img src="<?php if(!empty($other_user->photo->id)) { echo $other_user->photo->thumb_url; } else { echo $other_user->default_photo->thumb_url; }?>" alt="<?php echo $other_user->screen_name;?>" />
					</a>
					
					<div class="metadata left">
						<a href="<?php echo site_url('you/inbox/'.$transaction->id);?>" class="title">
							Request <?php echo $demander ? "to":"from"; echo " ".$other_user->screen_name;?>
						</a>
						<span class="summary">
						<?php echo $demander ? $transaction->language->demander_summary : $transaction->language->decider_summary; ?>
						</span>
					</div>
					<span class="updated css_right">
						Updated <?php echo user_date($transaction->updated,"n/j/o");?>
					</span>
				</div>
				

				<div class="clearfix metadata-bottom">
					<p class="left meta-label">Status</p>
					<p class="left field bold"><?php echo ucfirst($transaction->status);?></p>
				</div>
				<?php if($transaction->status!="declined" && $transaction->status!="cancelled") { ?>
				<div class="clearfix metadata-bottom">
					<p class="left meta-label">What's Next</p>
					<div class="left field">
						<?php if($transaction->status=="pending"){ ?>
							<?php if($demander){ ?>
								<p>Waiting for <?php echo $other_user->screen_name	; ?> to respond</p>
								
								<form method="post" id="cancel_transaction">
									<input type="hidden" name="form_type" value="transaction_cancel" />
									<input type="submit" class="button btn btn-large btn-warning" value="Cancel" style="margin-top: 10px;" />
								</form>

							<?php } else { ?>
							
								<form method='post' id='decide_transaction'>
									<input type='hidden' name='form_type' value='decide_transaction'/>
									<div class="btn-group">
									<input type="submit" class="left btn btn-large btn-success" name='decision' value="Accept" />
									<input type="submit" class="left btn btn-large btn-danger" name='decision' value="Decline" />
									</div>
								</form>
							<?php } ?>
							<?php } elseif ($transaction->status == 'active') { ?>
									<p>It's go time! Arrange a meeting to complete the transaction in person and then write a review when you're done.</p>
							<?php } ?>
							<?php if ($transaction->status == 'active' || $transaction->status =='completed') { ?>
									<a href="#" class="left btn" id="write_message">Write Message</a>
									<?php if(!$has_reviewed) { ?>
										<a href="#" id="write_review" class="left btn btn">Write Review</a>
									<?php } ?>
							<?php } ?>
					</div>
				</div>
				<?php } ?>
	
			</div>

		<div class='transaction-main'>
			<ul class='transaction-messages' id='transaction-messages'>

			<?php if(!empty($transaction->reviews)) { ?>
				<li class='section_header'>
					<h3 class='messages_title'>Reviews</h3>
				</li>
				<?php foreach($transaction->users as $use) { ?>
					<?php foreach($transaction->reviews as $rev) { ?>
						<?php if($use->id == $rev->reviewer_id) { ?>
							<li class='message clearfix'>
								<a href='#' class='user_image medium css_left'>
									<img src="<?php if(isset($use->photo_id)) { echo $use->photo->thumb_url; } else { echo $use->default_photo->thumb_url; }?>" alt="<?php echo $use->screen_name;?>" />
								</a>
								<div class = 'text clearfix css_left' href='<?php echo site_url('people/'.$use->id); ?>'>
									<a href='<?php echo site_url("people/profile/").$use->id; ?>' >
										<?php echo $use->screen_name;?>
									 </a>
									<div class='body'>
										<?php echo $rev->body; ?>
									</div>
									<p class='metadata-date left'>
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
				<h3 class="messages_title"><?php echo lang("transaction_messages_section_title");?></h3>
			</li>
									
			<?php foreach($transaction->messages as $key=>$M) { ?>
				<?php $author = ($other_user->id == $M->user_id) ?
					$other_user : $current_user; ?>
				<li class='message clearfix'>
					<a href="#" class="user_image medium css_left">
						<img src="<?php if(isset($author->photo->id)) { echo $author->photo->thumb_url; } else { echo $author->default_photo->thumb_url; }?>" alt="<?php echo $author->screen_name;?>" />
					</a>					
					<div class="text clearfix css_left">
						
						<a href="<?php echo site_url('people/'.$M->user_id);?>" class="metadata-author clearfix">
							<?php echo $M->user->screen_name;?>
						</a>
					
						<div class="body">
							<?php echo $M->body; ?>
						</div>
						
						<p class="metadata-date left">
							<?php echo time_ago(strtotime($M->created)); ?>
						</p>
					</div>
				</li>
			<?php } ?>
			
				<!-- transaction message form -->
				<li class="transaction_form message" id='message_new'>
					<form action="<?php echo site_url('you/view_transaction/'.$transaction->id); ?>" id="transaction_reply" name="transaction_reply" method="post">
						<input type="hidden" name="form_type" value="transaction_message" />
						<input type="hidden" name="transaction_id" value="<?php echo $transaction->id; ?>" />
						<input type="hidden" name="user_id" value="<?php echo $logged_in_user_id; ?>" />
						<label><?php echo lang("transaction_messages_instructions");?></label>
						<textarea rows="5" name="body" id="message_body"></textarea>
						<input type="submit" value="Send" class="button btn btn-primary" />
					</form>
				</li>
		</div>
	</div>
	<!-- eof div.right_content -->
	
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	$("table tr:even").addClass("odd");
	$("table tr:odd").addClass("even");
	$("form#review").validate({
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error').removeClass('success');
	  	},
	  	success: function(label) {
		  	label.hide().closest('.control-group').addClass('success');
	  	}
	});
	$("a#write_message").click(function(){
		console.log('yo yo');
		$('.transaction_form').hide();
		$('#message_new').show();
		$("#message_body").focus();
		return false;
	});
	$('a#write_review').click(function(){
		console.log('herehere!');
		$('.transaction_form').hide();
		$('#review_new').show();
		$('#review_body').focus();
	});
});
</script>
