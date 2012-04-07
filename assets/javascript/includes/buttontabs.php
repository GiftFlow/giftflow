<?php
/**
*	buttonTabs jQuery Plugin v0.1 7/12/2010 by Brandon Jackson
*/
?>
(function($) {
	$.fn.buttonTabs = function(options) {

var defaults={
      	onLoad:function(){},
    	};
    	
    	options = $.extend(defaults, options);

  	return $(this).each(function() {
    		var T = $(this);
    		T.addClass("button_tabs_wrapper");
    		T.children("div").addClass('button_tabs_content').hide();
    		T.children("ul").addClass("button_tabs");
    		if(T.find("a.active").size() == 1)
    		{
			if( T.find("a.active").attr('rel')){
    				$("#"+T.find("a.active").attr('rel')).addClass('button_tabs_active').show();
    			} else {
    				T.children(".button_tabs_content:eq("+T.find("a.active").parent().index()+")").addClass('button_tabs_active').show();
    			}
    		}
    		else
    		{
    			T.children("div:first").addClass('button_tabs_active').show();
    			T.find("ul li a:first").addClass("active");
    		}
    		T.find("ul.button_tabs li a").click(function(){
    			if( !$(this).hasClass('active') )
    			{
    				T.find("a.active").removeClass('active');
    				T.find(".button_tabs_active").removeClass('button_tabs_active').hide();
    				$(this).addClass('active');
    				if( $(this).attr('rel') )
    				{
    					$("#"+$(this).attr('rel')).addClass('button_tabs_active').show();
    				}
    				else
    				{
    					T.children(".button_tabs_content:eq("+$(this).parent().index()+")").addClass('button_tabs_active').show();
    				}
    				options.onLoad.call(T);
    			}
    			return false;
    		});
    	});
  }
})(jQuery);