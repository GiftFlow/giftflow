<div id="your_transactions" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	<div class='right_content'>
		<a href="<?php echo site_url('you/add_good/?type='.$type);?>" id='add_good' class="button btn">Add a <?php echo ucfirst($type); ?></a>
		<?php if(!empty($goods)) { ?>
			<ul class ="transactions goods_list list_menu float_right">
				<?php 
					foreach($goods as $val) 
					{ ?>
					<?php 
						if($val->status !='disabled')
						{?>
							<li class="clearfix">
								<div class='list_controls' >
									<a href="#" class="button btn left options secondary" style='height: 2.2em;'></a>
									<ul class='tooltip_menu'>
										<li>
											<a href="<?php echo site_url($val->type.'s/'.$val->id.'/edit');?>" >
											<span class='ui-icon ui-icon-star'></span>
											Edit
											</a>
										</li>
										<li>
											<a href="<?php echo site_url($val->type.'s/'.$val->id.'/photo_add');?>" >
											<span class='ui-icon ui-icon-star'></span>
											Add Photo
											</a>
										</li>
										<li>
										
											<a href="<?php echo site_url('gifts/'.$val->id.'/disable'); ?>" id="delete_gift" >
											<span class='ui-icon ui-icon-trash'></span>
											Delete
											</a>
										</li>
									</ul>
								</div>
								<div id='edit_location' class='jqmWindow'>
								</div>					
								<a href="#" class="user_image medium left">
									<img class='thumb_image' src="<?php if(isset($val->photo->thumb_url)) { echo $val->photo->thumb_url; } else { echo $val->default_photo->url; }?>" />		
								</a>
								
								<div class="metadata left">
									<a href="<?php echo site_url($val->type.'s/'.$val->id);?>" class="title">
									 <?php echo $val->title; ?>
									</a>
									<span class="summary">
									<?php echo substr($val->description, 0, 100); ?>
									</span>
								</div>
								
								<!--<span class="status left">
									<?php echo $val->status; ?>
								</span>-->
								
								<span class="updated css_right">
									<?php echo user_date($val->created,"n/j/o");?>
								</span>
                <div class='addthis'></div>
						</li>
					<?php } ?>
				<?php } ?>
				</ul>
		<?php } else { ?>
		
			<!-- Empty State -->
			<p>
				No <?php echo $type.'s'; ?> found.
			</p>
		<?php } ?>
		
	</div>
	<!-- eof div.right_content -->
	
	<div class='add jqmWindow' id='add_good'></div>

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
  jQuery.fn.stripTags = function() { 
    return this.replaceWith( this.html().replace(/<\/?[^>]+>/gi, '') ); 
  };

	$("img.status_icon").tipTip({ delay: 0, fadein: 0 });
	
	$(".button.options").button({
		icons: {
			primary: 'ui-icon-gear',
			secondary: 'ui-icon-triangle-1-s'
		}, 
		text:false
	}).click(function(){
		close_menu();
		$(this).toggleClass('clicked');
		$(this).addClass('ui-state-focus');
		$(this).siblings(".tooltip_menu").toggle();
		$('body').bind('click', function(e){
    		if($(e.target).closest('ul.tooltip_menu').length == 0){
				// click happened outside of menu, hide any visible menu items
				close_menu();
    		}
		});
		return false;
	});
	
	function close_menu(){
		$('ul.tooltip_menu').hide();
		$(".button.options").removeClass('clicked');
	}

	$("#edit_location").jqm({ 	
		ajax: '@href', 
		trigger: '.edit', 
		onLoad: function(){
			$('#edit_location').jqmAddClose('#close_this'); 
			$("button").button();
		} 
	});
	
	$("#delete_gift").click(function(){
		var answer = confirm('Are you sure you want to delete this gift? Doing so will cancel all transactions involving this gift');
		if(answer) return true;
		else return false;
	});
	
  //Renders unique addthis buttons for every row!
  var goods =  $('.goods_list').children('li.clearfix');
  $.each(goods, function(foo, bar){
    myurl  = $(bar).find('a.title').attr('href');
    mytitle = $(bar).find('a.title').text();
    mydescription = $(bar).find('span.summary').text();
    myimage = $(bar).find('img.thumb_image').attr('src');


    var addcon = {
      pubid: 'giftflow'
    };

    var addshare = {
        url: $.trim(myurl),
        title: $.trim(mytitle),
        description: $.trim(mydescription)+'...',
        image: $.trim(myimage)
    };
//    console.log(addcon);
  //  addthis.button('.addthis', addcon, addshare); 
  });

});
</script>
