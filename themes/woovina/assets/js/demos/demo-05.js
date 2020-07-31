jQuery(function ($) {
	// Move Compare Button & Wishlist Button & Countdown
	$('ul.products.woovina-row.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {
		
		var addtocart  = $(item).find('li.btn-wrap a.button');
		var quickview  = $(item).find('li.image-wrap a.wvn-quick-view');
        var wishlist   = $(item).find('div.tinv-wraper.tinv-wishlist');
		var compare    = $(item).find('a.compare.button');
		var wrapper    = $(item).find('li.btn-wrap');

		var cptext	= $(compare).html();
		var qvtext  = $(quickview).text();

		// Add wrapper for the Add to cart & Compare
		$(addtocart).wrap('<div class="btn-addtocart"></div>');
		$(quickview).wrap('<div class="btn-inner btn-quickview"></div>');
        $(compare).wrap('<div class="btn-inner btn-compare"></div>');
		addtocart = $(item).find('div.btn-addtocart');
		compare   = $(item).find('div.btn-inner.btn-compare');
		quickview = $(item).find('div.btn-inner.btn-quickview');

		var btngroup 	= $('<div class="btn-group"></div>');
		// Add tooltip for buttons
		$("<div class='tooltip for-compare'>" + cptext + "</div>").appendTo($(compare));
		$("<div class='tooltip for-quickview'>" + qvtext + "</div>").appendTo($(quickview));
		
		// Add class for button Wishlist
		$(wishlist).addClass('btn-inner btn-wishlist');
		$(wishlist).find('div.tinvwl-tooltip').attr('class', 'tooltip for-wishlist');
		
		$(quickview).appendTo($(btngroup));
		$(wishlist).appendTo($(btngroup));
		$(compare).appendTo($(btngroup));
        
		
		// Move Countdown 
		var countdown  = $(item).find('div.jquery-countdown');
		var imgwrapper = $(item).find('div.woo-entry-image');
        $(addtocart).appendTo($(imgwrapper));
		$(btngroup).appendTo($(imgwrapper));
        
		if($(countdown).length) {
			$(countdown).removeClass('is-countdown');
			$(countdown).appendTo($(imgwrapper));
			$(item).find('div.jquery-countdown').each(function(index) {
				$(this).countdown({until: new Date($(this).attr('data-timer')), format: 'DHMS', padZeroes: true}); 		
			});
		}
        
        // Remove empty tag
        $(wrapper).remove();
	});
	
	
	// For single product page
	if($('body.single-product').length) {
		var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');
		var compare  = $('body.single-product div.entry-summary').find('a.compare.button');
		var formcart = $('body.single-product div.entry-summary').find('form.cart');
		var cptext	 = $(compare).html();
		
		// Add wrapper for the Compare
		$(compare).wrap('<div class="btn-inner btn-compare"></div>');
		compare = $('body.single-product div.entry-summary').find('div.btn-inner.btn-compare');
		
		$('<div class="btn-single-page"></div>').appendTo($(formcart));
		var wrapper = $('body.single-product div.entry-summary').find('.btn-single-page');
		
		// Add class for button Wishlist
		$(wishlist).addClass('btn-inner btn-wishlist');
		
		$(wishlist).appendTo($(wrapper));
		$(compare).appendTo($(wrapper));
	}
	
	// After added to cart
	$(document.body).on('added_to_cart', function(){
		$('.btn-addtocart .added_to_cart').each(function(index, item) {
			var txtAdded = $(item).text();
			$(item).next().text(txtAdded);
		});
	});
	
	// Add carousel for Thumbnail images
	$('.single-product .product.wvn-tabs-layout-horizontal .woocommerce-product-gallery .flex-control-thumbs').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 4,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 3,
					infinite: true,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1
				}
			}
		]
	});
	
});