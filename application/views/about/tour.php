<div  id='about_tour' class='two_panels'>
<link rel='stylesheet' href='<?php echo base_url();?>assets/css/plax.css'/>
<!--<a id='tour_link' href='<?php echo base_url();?>about'>Back to About</a>
<span class='stereo_tour'>
     many thanks to <a id='stereomedia' href='http://www.townofnewhaven.org'><img src='<?php echo base_url();?>assets/images/applegate/stereomedia.jpg'/></a>
</span>-->
 <div id='frame'>
    <img id='werks' src='<?php echo base_url();?>assets/images/applegate/howitwerks.png'/>
    <span class ='arrows'>
    <img id='arrow_one' src='<?php echo base_url();?>assets/images/applegate/bluearrow1.png'/>
      <img id='arrow_two' src='<?php echo base_url();?>assets/images/applegate/bluearrow2.png'/>
      <img id='arrow_three' src='<?php echo base_url();?>assets/images/applegate/bluearrow3.png'/>
      <img id='arrow_four' src='<?php echo base_url();?>assets/images/applegate/bluearrow4.png'/>
    </span>

     <div class='info_panel' id='arrow_one'></div>
     <div class='info_panel' id='arrow_two'></div>
     <div class='info_panel' id ='arrow_three'></div>
     <div class='info_panel' id = 'arrow_four'></div>

  <div id='big_bot'>
    <!--  <img id='kid' src ='kid.png' /> -->
      <img id='robot' src='<?php echo base_url();?>assets/images/applegate/robot.png'/>
    </div>
  </div>
  <p id='credits'>
    <a id='tour_link' href='<?php echo base_url();?>about'>Back to About</a>
     <span class='stereo_tour'>
       many thanks to <a id='stereomedia' href='http://www.townofnewhaven.org'><img src='<?php echo base_url();?>assets/images/applegate/stereomedia.jpg'/></a>
    </span>
  </p>
</div>
  </body>
</html>
<script type='text/javascript' src='<?php echo site_url()."assets/javascript/plax.js";?>'></script>
<script type='text/javascript'>

  $(document).ready(function() {

     var panel; 


      console.log('starting');
      $('#werks').plaxify({"xRange":10, "yRange":10, "invert":'true' });
      $('#arrow_one').plaxify({"xRange":10, "yRange":9});
      $('#arrow_two').plaxify({"xRange":8, "yRange":8});
      $('#arrow_three').plaxify({"xRange":5, "yRange":6});
      $('#arrow_four').plaxify({"xRange":7, "yRange":4});
      $('#robot').plaxify({"xRange":7, "yRange":7});
      $('#kid').plaxify({"xRange":7, "yRange":7});
      
      $.plax.enable();
      console.log('enabled');

      var one = 'When you sign up for GiftFlow, be sure to add your zip code. You can also connect via Facebook';

      //$('.arrows img').unbind('mouseout');
     // $('.arrows img').unbind('mouseleave');
     // $('.arrows img').click(function(){
        
      function bobble(arrow) {  
        arrow.animate({top:"-=4px"},150).animate({top:"+=4px"},150);
        arrow.animate({top:"-=4px"},150).animate({top:"+=4px"},150);
      }
       bobble($('.arrows').children());

      function hide_robot() {
        $('#robot').fadeOut(100);
      }
      function show_robot() {
        $('#robot').fadeIn(100);
      }


      $('.arrows img').click(function() {
        $('.info_panel').hide(); 
          var arrow = $(this);
          bobble(arrow);
          bobble($('#robot'));
          //hide_robot();
          var id = $(this).attr('id');
          panel = $('div#' + id);
            panel.fadeIn();
           bobble(panel); 
          });

      $('.arrows img').bind('mouseout', function() {
           // panel.fadeOut();
            //show_robot();
       }); 

        


      });

</script>




















<!--	<h1>The Tour</h1>
		
		<p>Welcome to GiftFlow! The site is currently a work in progress. At the moment, we are in the Beta stage of development. This means a lot is happening, and you should stay tuned for lots of cool new stuff.</p>
				<object style='float: left; margin:0px 20px 10px 0px;' width="425" height="344"><param name="movie" value="http://www.youtube.com/v/0wLNXFeZbBU&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/0wLNXFeZbBU&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>

		<h2>Get Free Stuff</h2>
		<p>GiftFlow makes it easy for you to find anything you need in your area. <em>For free.</em> Search for others' gifts by keywords, tags and location. List your needs and the site will automatically pair you with matching gifts nearby.</p>
		<h2>Reuse Your Stuff</h2>
		<p>Got some stuff you don't want any more? GiftFlow makes it easy to reuse it by connecting you with people nearby who need what you have to give.</p>
		<h2>Build a Reputation for Generosity</h2>
		<p>Your GiftFlow profile lists everything you've given and everything you've taken. Profiles enable trust to develop between members, which in turn enables the online GiftFlow community to come to being in the real world.
     -->
	</div>
</div>
