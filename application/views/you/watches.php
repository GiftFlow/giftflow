<div id="your_watches" class="two_panels">

	<!-- Sidebar Menu -->
	<?php echo $menu; ?>
	
	
	<div class='right_content'>
		<h3>Watch a Keyword</h3>

		<?php echo $form; ?>
		
		<h3>Keywords you are currently watching</h3>
		
		<?php if(!empty($watches)) { ?>
			<ul class ="transactions goods_list list_menu float_right">
				<?php 
					foreach($watches as $val) 
					{ ?>
					
							<li class="clearfix">
								<div class='list_controls' >
									<a href="#" class="button left options secondary" style='height: 2.2em;'></a>
									<ul class='tooltip_menu'>
										<li>
											<a href="<?php echo site_url('watches/'.$val->id.'/delete'); ?>" id="delete_watch" >
											<span class='ui-icon ui-icon-trash'></span>
											Delete
											</a>
										</li>
									</ul>
								</div>
								
								
								<div class="metadata left">
									<?php echo $val->keyword; ?>
								</div>
								
						</li>
				<?php } ?>
				</ul>
		<?php } else { ?>
		
			<!-- Empty State -->
			<p>
				No watches found.
			</p>
		<?php } ?>
		
	</div>
	<!-- eof div.right_content -->
	

</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){
	

//	$('form').validate();


/*
  jQuery.fn.stripTags = function() { 
    return this.replaceWith( this.html().replace(/<\/?[^>]+>/gi, '') ); 
  };*/
		

//	$("img.status_icon").tipTip({ delay: 0, fadein: 0 });
	
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
/*
	$("#edit_location").jqm({ 	
		ajax: '@href', 
		trigger: '.edit', 
		onLoad: function(){
			$('#edit_location').jqmAddClose('#close_this'); 
			$("input:submit").button();
			$("button").button();
		} 
	});
	*/
	$("#delete_watch").click(function(){
		var answer = confirm('Are you sure you want to delete this watch?');
		if(answer) return true;
		else return false;
	});
	
	/*
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
	*/

});
</script>
