<ul id='about_menu' class='sidebar_menu'>
	<li style='margin-bottom: 22px;'>
		<img alt="About" src="<?php echo base_url();?>assets/images/headings/about_menu.png">
	</li>
	<li>
		<!-- AddThis Follow BEGIN -->
		<p>Follow Us</p>
		<div class="addthis_toolbox addthis_32x32_style addthis_default_style">
		<a class="addthis_button_facebook_follow" addthis:userid="giftflow"></a>
		<a class="addthis_button_twitter_follow" addthis:userid="giftflow"></a>
		</div>
		<!-- AddThis Follow END -->
	</li>
	<li <?php if($segment[2]=='story'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/story');?>'> 
			Our Story
		</a>
	</li>
	<li <?php if($segment[2] =='faq'){echo "class='active'";}?>>
		<a href='<?php echo site_url('about/faq');?>'> 
			FAQ
		</a>
	</li>
	<li <?php if($segment[2]=='tour'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/tour');?>'> 
			The Tour
		</a>
	</li>
	<li <?php if($segment[2]=='press'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/press');?>'> 
			In the Press
		</a>
	</li>
	<li <?php if($segment[2]=='donate' || $segment[1]=="donate"){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/donate');?>'> 
			Donate
		</a>
	</li>
  	<li <?php if($segment[2] =='transparency') { echo "class='active'"; }?>>
   	 <a href='<?php echo site_url('about/transparency');?>'>
   		 Transparency
    </a>
  </li>
  <li <?php if($segment[2] =='thankyou') { echo "class='active'"; }?>>
   	 <a href='<?php echo site_url('about/thankyou');?>'>
   		 Thank You
    </a>
  </li>
</ul>
