<!-- View for event feed. This takes 3 different kinds of events - user_new, good_new and transaction completed -->



<?php if(!empty($results)) { ?>

	<?php if(!$row) { ?>
		<!-- Results List -->
		<ul class='events_list events'>
	<?php } ?>
	
		<?php foreach($results as $key=>$val) {?>
		
			<!-- Result Row -->
      <li class='result_row events clearfix'>


    <!-- begi USER_NEW -->
			<?php if($val->event_type_id == 4) {  ?>

            <!-- profile Image -->
              <a class='profile_image user_image medium'
               href='<?php echo site_url('people/'.$val->user->id); ?>'>
              
                <img src='<?php if(isset($val->user->photo->url)) 
                { echo $val->user->photo->thumb_url; } 
                else { echo $val->user->default_photo->thumb_url; }?>'>
            </a>
            
            <!-- Metadata -->
            <div class='result_meta'>
            
              <!-- Screen Name -->
              <a href='<?php echo site_url('people/'.$val->user->id); ?>' class='title'>
                <?php echo $val->user->screen_name;?>
              </a>
              
              <?php if(!empty($val->user->location->city)){ ?>
                <!-- location -->
                <span class='metadata location'>
                  <?php echo $val->user->location->city;?>
                </span>
              <?php } ?>
                <!-- Created -->
                <span class="metadata created">
                  <em>They joined on:
                  <?php echo user_date($val->user->created,"F jS Y");?>
                  </em>
                </span>
            </div>
       <?php } ?>
    <!-- close user new -->

     <!--Open transaction completed -->
      <?php if($val->event_type_id == 2) { ?>
      <span class='event_summary'>
        <?php echo($val->transaction->language->overview_summary); ?>
      </span> 
        <?php foreach($val->transaction->users as $user) { ?>
        <span class='trans_participant'>
        <a class='result_image user_image medium' href = '<?php echo site_url()."people/".$user->id;?>'>
                <img src='<?php if(isset($user->photo->url)) 
                { echo $user->photo->thumb_url; } 
                else { echo $user->default_photo->thumb_url; }?>'>
            </a>
                  <!--nested foreach here -->
          
                <?php foreach($val->review['reviews'] as $review) { ?>
                   <span class='event_review'>
                      <?php if($review->reviewer_id == $user->id) {
                        echo $review->user_reviewer_name." gave a ";
                        echo "<span class='".$review->rating."'>";
                        echo $review->rating;
                        echo "</span> rating. They wrote: \"";
                        echo substr($review->body, 0,50)."...\"";?>
                        <a href='<?php echo site_url()."people/".$review->reviewer_id; ?>'>read more</a>
                      <?php } ?>
                    </span>
                 <?php } ?>
          </span>
         <?php } ?>
                
    <!-- close trans_completed -->
    <?php } ?>           
    <!-- Open good_new -->
    <?php if($val->event_type_id == 8) { ?>
            
          <a class='result_image small'
              href='<?php echo site_url($val->good->type."s/".$val->good->id); ?>'>
             <img src =<?php if(isset($val->good->photo->thumb_url)) { echo $val->good->photo->thumb_url; } else { echo $val->good->default_photo->url; } ?> />
          </a>
        <div class='event_content'>

            <span class = 'event_created'>
                 <img src="<?php echo site_url(); ?>assets/images/cluster_solid.png"/>
                <?php echo user_date($val->event_created,"F jS Y"); ?>

            </span>
            <span class = 'result_meta'>
                <?php echo $val->good->user->screen_name; ?>
                <?php switch ($val->good->type) {
                        case 'need':
                          echo " needs ";
                          break;
                        case 'gift':
                          echo " offered ";
                          break;
                      } ?>

                <?php echo $val->good->title?>
            </span>                  
            <span class='event_text'>
              <?php echo  substr($val->good->description, 0, 120)."..."; ?>
            </span>
        </div>
    <!-- close good_new -->
    <?php } ?>

			</li>
			<!-- eof Result Row -->
			
		<?php } ?>
	<?php } ?>
	<?php if(!$row) { ?>
		</ul>
		<!-- eof Results List -->
	<?php } ?>
	
