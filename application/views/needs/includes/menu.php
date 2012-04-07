<ul id='needs_sidebar' class='sidebar_menu'>
	<li <?php if($segment[1]=="needs" && (empty($segment[2])) || $segment[2]=='index'){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('needs');?>'> 
			Latest Needs
		</a>
	</li>
	<li <?php if($segment[2]=='browse'){ echo  "class='active'"; } ?>>
		<a href='#<?php // echo site_url('needs/browse');?>'> 
			Browse Needs
		</a>
	</li>
	<li>
		<a href='<?php echo site_url('you/needs/add');?>' > 
			New Need
		</a>
	</li>
	<li <?php if($segment[2]=='gifts'){ echo  "class='active'"; } ?>>
		<a href='#<?php // echo site_url('about/needs');?>' > 
			Needs 101
		</a>
	</li>
	<li <?php if($segment[2]=='donate' || $segment[1]=="donate"){ echo  "class='active'"; } ?>>
		<a href='<?php echo site_url('about/donate');?>' > 
			Donate
		</a>
	</li>
</ul>