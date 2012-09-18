<div id="transaction-view" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>

		<div class='clearfix transaction-summary'>
				
				<div class="clearfix thank-header">
					<a href="<?php echo site_url('people/'.$thread->other_user->id);?>" class="user_image medium left">
						<img src="<?php echo $thread->other_user->default_photo->thumb_url;?>" alt="<?php echo $thread->other_user->screen_name;?>" />
					</a>
					
					<div class="metadata left">
						<a href="<?php echo site_url('you/view_thread/'.$thread->thread_id);?>" class="title">
							Conversation with  <?php echo $thread->other_user->screen_name; ?>
						</a>
						<span class="summary">

						</span>
					</div>
					<span class="updated css_right">
						Updated <?php echo user_date($thread->recent->message_created,"n/j/o");?>
					</span>
				</div>
		<!-- eof div.transaction-main -->
		</div>

		<div class='transaction-main'>
			<ul class='transaction-messages' id='transaction-messages'>

			<!-- eof review form-->
			<li class='section_header'>
				<h3 class="messages_title">Messages</h3>
			</li>
									
			<?php foreach($thread->messages as $key=>$M) { ?>
				<?php $author = ($M->user_id == $thread->other_user->id) ?
					$thread->other_user : $u; ?>
				<?php $author = (object)$author;?>

				<li class='message clearfix'>
					<a href="#" class="user_image medium css_left">
						<img src="<?php echo $author->default_photo->thumb_url; ?>" alt="<?php echo $author->screen_name;?>" />
					</a>					
					<div class="text clearfix css_left">
						
						<a href="<?php echo site_url('people/'.$author->id);?>" class="metadata-author clearfix">
							<?php echo $author->screen_name;?>
						</a>
					
						<div class="body">
							<?php echo $M->message_body; ?>
						</div>
						
						<p class="metadata-date left">
							<?php echo time_ago(strtotime($M->message_created)); ?>
						</p>
					</div>
				</li>
			<?php } ?>
			
				<!-- transaction message form -->
				<li class="transaction_form message" id='message_new'>
					<form action="<?php echo site_url('you/view_thread/'.$thread->thread_id); ?>" id="thread_reply" name="thread_reply" method="post">
						<input type="hidden" name="form_type" value="thread_message" />
						<input type="hidden" name="thread_id" value="<?php echo $thread->thread_id; ?>" />
						<input type="hidden" name="user_id" value="<?php echo $logged_in_user_id; ?>" />
						<input type='hidden' name='recip_id' value="<?php echo $thread->other_user->id; ?>"/>
						<label>Send a Message</label>
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

});
</script>
