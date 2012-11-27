<div class='row'>
	<div class='span2 chunk'>
		<!-- Sidebar Menu -->
		<?php echo $menu; ?>
	</div>
	
	<div class='span8 chunk'>
		<div class='row-fluid interaction_header'>	
			<div class='span2'>
				<a href="<?php echo site_url('people/'.$thread->other_user->id);?>" class="user_image medium left">
					<img src="<?php echo $thread->other_user->default_photo->thumb_url;?>" alt="<?php echo $thread->other_user->screen_name;?>" />
				</a>
			</div>
			<div class='span8'>		
				<a href="<?php echo site_url('you/view_thread/'.$thread->thread_id);?>" class="title">
					Conversation with  <?php echo $thread->other_user->screen_name; ?>
				</a>
				<span class="updated css_right">
					Updated <?php echo user_date($thread->recent->message_created,"n/j/o");?>
				</span>
			</div>
		</div>
		<div class='row-fluid'>
			<div class='span12'>
				<ul class='interaction'>

				<!-- eof review form-->
				<li class='section_header'>
					<h3 class="inbox_title">Messages</h3>
				</li>
										
				<?php foreach($thread->messages as $key=>$M) { ?>
					<?php $author = ($M->user_id == $thread->other_user->id) ?
						$thread->other_user : $u; ?>
					<?php $author = (object)$author;?>

					<li>
						<div class='row-fluid'>
							<div class='span2'	
								<a href="#" class="user_image medium css_left">
									<img src="<?php echo $author->default_photo->thumb_url; ?>" alt="<?php echo $author->screen_name;?>" />
								</a>					
							</div>
							<div class="span8">
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
			</ul>
		</div>
	</div>
</div>
</div>

<script type='text/javascript'>
$(function(){

});
</script>
