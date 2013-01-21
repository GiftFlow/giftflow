<ul id='about_menu' class='sidebar_menu'>
	<li>
		<a href="<?php echo site_url('about/index'); ?>">
			<img alt="About" src="<?php echo base_url();?>assets/images/headings/about_menu.png">
		</a>
	</li>
	<li <?php if($segment[2]=='press'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/press');?>'> 
			News
		</a>
	</li>
	<li <?php if($segment[2] =='faq'){echo "class='active'";}?>>
		<a href='<?php echo site_url('about/faq');?>'> 
			FAQ
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
