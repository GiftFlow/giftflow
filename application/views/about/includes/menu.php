<ul id='about_menu' class='sidebar_menu'>
	<li style='margin-bottom: 22px;'>
		<img alt="About" src="<?php echo base_url();?>assets/images/headings/about_menu.png">
	</li>
	<li <?php if((empty($segment[2])&&$segment[1]!="donate") || $segment[2]=='index'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about');?>'> 
			Intro
		</a>
	</li>
	<li <?php if($segment[2]=='tour'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/tour');?>'> 
			The Tour
		</a>
	</li>
	<li <?php if($segment[2]=='faq'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/faq');?>'> 
			FAQ
		</a>
	</li>
	<li <?php if($segment[2]=='story'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/story');?>'> 
			Our Story
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
  <li <?php if($segment[2] =='finances') { echo "class='active'"; }?>>
    <a href='<?php echo site_url('about/finances');?>'>
    Finances
    </a>
  </li>
</ul>
