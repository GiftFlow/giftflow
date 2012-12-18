					<form action="<?php echo site_url('you/view_transaction/'.$transaction->id); ?>" id="review" name="review" method="post">
						<input type ="hidden" name ="form_type" value="transaction_review_new" />
						<input type="hidden" name="transaction_id" value="<?php echo $transaction->id; ?>" />
						<input type="hidden" name="user_id" value="<?php echo $logged_in_user_id; ?>" />

						<h3 id='reviews_title'>Review This Gift</h3>

						<p>Please include a short description of the gift and how you felt about the experience.</p>
						<div class='control-group'>
								<textarea id='review_body' name="body" rows="6" class="required big-border"></textarea>
								<label for="body" class="error" style="display: none;">Please write a description for your review.</label>
						</div>
						<div class='control-group required'>
							<p>Provide a rating of the interaction. </p>
							<label class="radio inline" style="margin-right: 10px;">
								<input id="r3" type="radio" class="required" value="positive" name="rating">
								Positive
							</label>
							<label class="radio inline" style="margin-right: 10px;">
								<input id="r4" type="radio" value="neutral" name="rating">	
								Neutral
							</label>
							<label class="radio inline">
								<input class="required" id="r5" type="radio" value="negative" name="rating">
								Negative
							</label>
							<label for="rating" style="clear: left; display: none;" class="error">Please rate the transaction.</label>
						</div>
						<input type="submit" value="Send" class="btn-primary btn clearfix"/>
				</form>
