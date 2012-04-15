<div class='two_panels'>

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
     <span style='float:right;'>
     <a class='button' href='<?php echo site_url()."you/add_good/?type=gift";?>'> Add a Gift</a>
     <a class='button' href='<?php echo site_url()."you/add_good/?type=need"; ?>'> Add a Need</a>
      </span>
    <div class='events'>

      <p>Recent activity near you!</p>
      
      <?php echo UI_Results::events(array(
        'results' => (isset($events)) ? $events : array(),
        'mini' => FALSE
      )); ?>
              
    </div><!-- close events -->

	</div>
	<!-- eof div.right_content -->

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){

  $('.button').button();
});

</script>
