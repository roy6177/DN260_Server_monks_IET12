jQuery(function ($) {
	// Move Compare Button & Wishlist Button & Countdown
	$('ul.products.woovina-row.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {
		var wishlist   = $(item).find('div.tinv-wraper.tinv-wishlist');
        var btnwrap    = $(item).find('li.btn-wrap');
		var addtocart  = $(item).find('li.btn-wrap a.button');
		var quickview  = $(item).find('li.image-wrap a.wvn-quick-view');
		var soldby     = $(item).find('li.sold-by');
        var unitsold   = $(item).find('div.units-sold');
        var remaining   = $(item).find('li.remaining');
		var wei        = $(item).find('ul.woo-entry-inner');
        var wooentryimage = $(item).find('div.woo-entry-image');
        var wooentryimageinner = $(item).find('li.image-wrap div.woo-entry-image');
        var newbadge = $(item).find('span.new-badge');
        var onsale = $(item).find('span.onsale');
        var soldinfo = $('<li class="sold-info"></li>');
        
        $(unitsold).appendTo($(soldinfo));
        $(soldby).appendTo($(soldinfo));
        $(soldinfo).appendTo($(wei));
        $(remaining).appendTo($(wei));
        
        var badgeinfo = $('<div class="badge-info"></div>');
        $(onsale).appendTo($(badgeinfo));
        $(newbadge).appendTo($(badgeinfo));
        $(badgeinfo).appendTo($(wooentryimage, wooentryimageinner));
        
        
        
		var qvtext  = $(quickview).text();
		var atctext  = $(addtocart).text();
        
  
    
        $(wishlist).addClass('btn-action btn-wishlist');
        $(wishlist).find('div.tinvwl-tooltip').attr('class', 'tooltip wishlist'); // doi ten class tu tinvwl-tooltip sang tooltip for-wishlist
        $(wishlist).find('div.tinv-wishlist-clear').remove(); // xoa element thua
        $(quickview).wrap('<div class="btn-action btn-quickview"></div>');
        $(addtocart).wrap('<div class="btn-action btn-addtocart"></div>');
        
        
        var addtocart = $(item).find('li.btn-wrap div.btn-addtocart');
        $("<div class='tooltip addtocart'>" + atctext + "</div>").appendTo($(addtocart));
        
        var quickview = $(item).find('div.btn-quickview');
        $("<div class='tooltip quickview'>" + qvtext + "</div>").appendTo($(quickview));
        
        
        $(wishlist).appendTo($(btnwrap));
        $(quickview).appendTo($(btnwrap));
        $(addtocart).appendTo($(btnwrap));
        
        $(btnwrap).appendTo($(wooentryimage));
        $(btnwrap).clone().appendTo($(wei));
        
		
        
        
       
        
        //span onslae
        var onsale = $(item).find('li.image-wrap span.onsale');
        $(onsale).clone().prependTo(wooentryimage);
        
        //Out of stock
        var outofstockbadge = $(item).find('li.image-wrap div.outofstock-badge');
        $(outofstockbadge).clone().appendTo(wooentryimage);
        
        
        // countdown
		var countdown  = $(item).find('div.jquery-countdown');
		if($(countdown).length) {
			$(countdown).removeClass('is-countdown');
			$(countdown).appendTo($(wooentryimage));
			$(item).find('div.jquery-countdown').each(function(index) {
				$(this).countdown({until: new Date($(this).attr('data-timer')), format: 'DHMS', padZeroes: true}); 		
			});
		}
        
        
        // Variable Swatches
        var variableswatches = $(item).find('div.shopswatchinput');
        $(variableswatches).prependTo(wooentryimage);
        
	});
	

    

    
    
    
	// For single product page
	if($('body.single-product').length) {
		var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');
		var compare  = $('body.single-product div.entry-summary').find('a.compare.button');
		var formcart = $('body.single-product div.entry-summary').find('form.cart');
		var cptext	 = $(compare).html();
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




