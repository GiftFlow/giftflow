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
									<a href="#" id="write_review" class="left btn btn">Write Review</a>
							<?php } ?>
					</div>
				</div>
				<?php } ?>
	
			</div>

		<div class='transaction-main'>
			<h3 class="messages_title">Messages</h3>
									
			<ul id="transaction-messages">
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
			
				<li class="message clearfix">
					<form action="<?php echo site_url('you/view_transaction/'.$transaction->id); ?>" id="transaction_reply" name="transaction_reply" method="post">
						<input type="hidden" name="form_type" value="transaction_message" />
						<input type="hidden" name="transaction_id" value="<?php echo $transaction->id; ?>" />
						<input type="hidden" name="user_id" value="<?php echo $logged_in_user_id; ?>" />
						<label>Send a Message</label>
						<textarea rows="5" name="body" id="message_body"></textarea>
							<?php if ($transaction->status == "pending" && !$demander) { ?>
							<!--	<fieldset>
									<legend>Do you agree to participate in this transaction?</legend>
									<input id="r1" type="radio" value="accept" name="decision">Accept &nbsp
									<input id="r2" type="radio" value="decline" name="decision">Decline<BR />
								</fieldset> -->
							<?php } ?>	
						<input type="submit" value="Send" class="button btn btn-primary" />
					</form>
				</li>
			</ul>
			<!-- End of messages -->
			<!-- Review Form -->
			<?php if($transaction->status == "active" && !$has_reviewed) { ?>
			<div class='review jqmWindow'>
				<form action="<?php echo site_url('you/view_transaction/'.$transaction->id); ?>" id="review" name="review" method="post">
					<input type ="hidden" name ="form_type" value="transaction_review_new" />
					<input type="hidden" name="transaction_id" value="<?php echo $transaction->id; ?>" />
					<input type="hidden" name="user_id" value="<?php echo $logged_in_user_id; ?>" />
					<h3>Review This Transaction</h3>
					<p><!--@todo improve language-->Please include a short description of the transaction and relevant details like who gave what to whom, whether or not the other user was nice, etc. Reviews will be published simultaneously.</p>
					<div class='control-group'>
						<textarea name="body" rows="6" class="required"></textarea>
						<label for="body" class="error" style="display: none;">Please write a description for your review.</label>
					</div>
					<fieldset class="required">
						<div class='control-group'>
						<legend>Rate</legend>
						<label class="radio css_left" style="margin-right: 10px;">
							<input id="r3" type="radio" class="required" value="positive" name="rating">
							Positive
						</label>
						<label class="radio css_left" style="margin-right: 10px;">
							<input id="r4" type="radio" value="neutral" name="rating">	
							Neutral
						</label>
						<label class="radio css_left">
							<input class="required" id="r5" type="radio" value="negative" name="rating">
							Negative
						</label>
						<label for="rating" style="clear: left; display: none;" class="error">Please rate the transaction.</label>
						</div>
					</fieldset>
					<div class="css_right">
						<input type="submit" value="Submit Review" class="btn-primary btn clearfix"/>
						<a href="#" class="hide_modal">Cancel</a>
					</div>
				</form>
			</div>
			<!-- eof div.review -->
				
			<?php } elseif($has_reviewed){ ?>
			<!-- Reviews -->
			<div class="reviews">
					<?php foreach($reviews['reviews'] as $key=>$val){ ?>
						
						<h3>
							<?php echo ($val->reviewer_id == $logged_in_user_id) ? "Your" : $other_user->screen_name."'s"; ?> Review
						</h3>
						
						<p>Rating: <?php echo  $val->rating;?></p>
						<p><?php echo $val->body; ?></p>
			
					<?php }?>
			</div>
			<!-- eof div.reviews -->
			<?php } ?>
			
			<!-- History -->
			<div class="history">
				<h3>History</h3>
				<ul>
				<?php foreach($transaction->events as $event) { ?>
					<li><?php echo lang("history_".$event->event_type);?>
						by <?php echo $event->user_screen_name;?>
						on <?php echo user_date($event->created,"F jS Y g:ia"); ?>
					</li>
				<?php } ?>
				</ul>
			</div>
			<!-- eof div.history -->
			
			<!-- What's Next Sidebar -->
			<div class="whats-next" style="display: none;">
			
				<?php if($transaction->status == "cancelled"){ ?>
					
					This transaction has been cancelled.
					
				<?php } elseif($transaction->status == "declined"){ ?>
				
					This transaction has been declined.
				
				<?php } else { ?>
				
					<?php if ($transaction->status == "pending" && $transaction->decider->id == $logged_in_user_id ) { ?>
						
						<a href="#" class="button btn">Accept Offer</a>
						
					<?php } elseif($transaction->status == "active") { ?>
					
						<a href="#" class="button btn complete-transaction">Complete</a>
					
					<?php } elseif($transaction->status=="pending" && $transaction_role == "demander"){ ?>
	
							<p>Still waiting for <?php echo $transaction->decider->screen_name;?> to agree to participate.</p>
						
						<?php } ?>
					
					<form method="post" id="cancel_transaction">
						<input type="hidden" name="form_type" value="transaction_cancel" />
						<input type="submit" value="Cancel" />
					</form>
					
				<?php } ?>
				
			</div>
			<!-- eof What's Next Sidebar -->
			
		</div>
		<!-- eof div.transaction-main -->

		<!-- User Bio Sidebar -->
		<div class="sidebar" style='display: none;'>		
			<div class="top">
				<h2>
					About <?php echo $other_user->screen_name;?>
				</h2>
			</div>
			<div class="center">
			
				<p><?php echo $other_user->location->city.", ".$other_user->location->state;?></p>
				<p>
					<a href="<?php echo site_url('people/'.$other_user->id);?>">View Profile</a>
				</p>
				
			</div>
			<div class="bottom"></div>
		</div>

		<!-- Details Sidebar -->
		<div class="sidebar" style='display: none;'>		
			<div class="top">
				<h2>Transaction Details</h2>
			</div>
			<div class="center">
			
				<p>Transaction ID: <?php echo $transaction->id;?></p>
				<p>Started <?php echo user_date($transaction->created,"F jS Y g:ia");?></p>
				<!-- @todo
				<p>
					<a href="#">Report / Flag</a>
				</p>
				-->
			</div>
			<div class="bottom"></div>
		</div>
		
		<!-- TO DO if user has already written a review, display it and give them the option to edit it
			then, when status = "compeleted" - show both reviews --!>
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
	$(".review").jqm({ 	
		trigger: '#write_review'
	});
	$("a.hide_modal").click(function(){
		$(".review").jqmHide();
	});
	$("a#write_message").click(function(){
		$("#message_body").focus();
		return false;
	});
});
</script>
