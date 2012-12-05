<!-- View for event feed. This takes 3 different kinds of events - user_new, good_new and transaction completed -->


<?php if(!empty($results)) {?>

	<?php if(!$row) { ?>
		<!-- Results List -->
		<ul class='results_list events <?php if($mini) {echo "mini"; }?>'>
	<?php } ?>
		
	<?php foreach($results as $key=>$val) {?>
			<!-- Result Row start -->

	   <?php if($val->event_type_id == 2) { ?>
		<!--Open transaction completed -->
			<?php echo UI_Results::reviews(array(
				'results' => $val,
				'row' => TRUE,
				'mini'=> TRUE
			));?>

		<?php } ?>


		<?php if($val->event_type_id == 17) { ?>
		<!-- open thankyou -->
			<?php echo UI_Results::thanks(array(
				'results'=> $val,
				'mini' => TRUE,
				'row' => TRUE
			)); ?>

		<?php } ?>


	<!-- eof Result Row -->
	<?php } ?>

<?php } ?>
<?php if(!$row) { ?>
	</ul>
<?php } ?>
	
