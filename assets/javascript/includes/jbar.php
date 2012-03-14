<?php
/*
 * jBar
 */
 ?>
(function($) {
	
	$.fn.bar = function(options) {
		var opts = $.extend({}, $.fn.bar.defaults, options);
		return this.each(function() {
			$this = $(this);
			var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
			
			$this.click(function(e){
				if(!$('.jbar').length){
					timeout = setTimeout('$.fn.bar.removebar()',o.time);
					var _message_span = $(document.createElement('span')).addClass('jbar-content').html(o.message);
					_message_span.css({"color" : o.color});
					var _wrap_bar;
					(o.position == 'bottom') ? 
					_wrap_bar	  = $(document.createElement('div')).addClass('jbar jbar-bottom'):
					_wrap_bar	  = $(document.createElement('div')).addClass('jbar jbar-top') ;
					
					_wrap_bar.css({"background-color" 	: o.background_color});
					if(o.removebutton){
						var _remove_cross = $(document.createElement('a')).addClass('jbar-cross');
						_remove_cross.click(function(e){$.fn.bar.removebar();})
					}
					else{				
						_wrap_bar.css({"cursor"	: "pointer"});
						_wrap_bar.click(function(e){$.fn.bar.removebar();})
					}	
					_wrap_bar.append(_message_span).append(_remove_cross).hide().insertBefore($('.content')).fadeIn('fast');
				}
			})
			
			
		});
	};
	var timeout;
	$.fn.bar.removebar 	= function(txt) {
		if($('.jbar').length){
			clearTimeout(timeout);
			$('.jbar').fadeOut('fast',function(){
				$(this).remove();
			});
		}	
	};
	$.fn.bar.defaults = {
		background_color 	: '#FFFFFF',
		color 				: '#000',
		position		 	: 'top',
		removebutton     	: true,
		time			 	: 5000	
	};
	
})(jQuery);