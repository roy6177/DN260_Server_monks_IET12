(function($) {
	
	"use strict";
	
	// Scroll Class
	$(window).on('scroll', function() {
		var fromTop = $(this).scrollTop();
		var fromBottom = $(document).height() - $(this).height();
		var navBarHeight = $('#mobile-navbar').height();
		
		if(fromTop > fromBottom - navBarHeight)  {
			$('#mobile-navbar.hide_on_footer').removeClass('active');
		} 
		else {
			$('#mobile-navbar.hide_on_footer').addClass('active');
		}
	});
	
})(jQuery);