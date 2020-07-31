jQuery(function ($) {
	// Move Compare Button & Wishlist Button & Countdown
	$('ul.products.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {
        var categoryname = $(item).find('li.category');
        var titlename    = $(item).find('li.title');
        var innername    = $(item).find('li.inner');
        var woodesc      = $(item).find('li.woo-desc');
        
        // Add wrapper for the details products
        var details      = $('<li class="details-products"></li>');
        var wooentryinner = $(item).find('ul.woo-entry-inner');
        $(details).appendTo($(wooentryinner));
        $(categoryname).appendTo($(details));
        $(titlename).appendTo($(details));
        $(innername).appendTo($(details));
        $(woodesc).appendTo($(details));
        
        // Move btnwrap to wooentryinner
        var btnwrap = $(item).find('li.btn-wrap');
        $(btnwrap).appendTo($(wooentryinner));   
        
        // Move spanonsale, outofstock, jquerycountdown, addtocart to wooentryimage
        var wooentryimage = $(item).find('li.image-wrap div.woo-entry-image');
        var spanonsale = $(item).find('li.image-wrap span.onsale');
        $(spanonsale).appendTo($(wooentryimage));
        var outofstock = $(item).find('li.image-wrap div.outofstock-badge');
        $(outofstock).appendTo($(wooentryimage));
        var jquerycountdown = $(item).find('div.product-inner div.jquery-countdown');
        $(jquerycountdown).appendTo($(wooentryimage));
        var addtocart = $(item).find('li.btn-wrap a.button');
        $(addtocart).wrap('<div class="btn-addtocart"></div>');
        addtocart = $(item).find('div.btn-addtocart');
        $(addtocart).appendTo(wooentryimage);
        
    
        var quickview    = $(item).find('li.image-wrap a.wvn-quick-view');
        var wishlist     = $(item).find('a.tinvwl_add_to_wishlist_button');
        var compare      = $(item).find('a.compare');
        
		
		var qvtext  = $(quickview).text();
        var wltext	= $(wishlist).text();
        var cptext	= $(compare).text();
        
        // Add wrapper for the Add to cart & Compare
		$(quickview).wrap('<div class="btn-qwc-wrap btn-quickview"></div>');
		$(wishlist).wrap('<div class="btn-qwc-wrap btn-wishlist"></div>');
		$(compare).wrap('<div class="btn-qwc-wrap btn-compare"></div>');
        
        // Move quickview, wishlist, compare to btnwrap
        quickview = $(item).find('div.btn-qwc-wrap.btn-quickview');
        wishlist = $(item).find('div.btn-qwc-wrap.btn-wishlist');
        compare = $(item).find('div.btn-qwc-wrap.btn-compare');
        
        $(quickview).appendTo($(btnwrap));
        $(wishlist).appendTo($(btnwrap));
        $(compare).appendTo($(btnwrap));
        
        // Add tooltip for buttons
        $("<div class='tooltip for-quickview'>" + qvtext + "</div>").appendTo($(quickview));
		$("<div class='tooltip for-wishlist'>" + wltext + "</div>").appendTo($(wishlist));
		$("<div class='tooltip for-compare'>" + cptext + "</div>").appendTo($(compare));
        
        
        // Remove wishlistwrap
        var wishlistwrap = $(item).find('div.product-inner div.tinv-wraper');
        $(wishlistwrap).remove();
        
        // Rename btnwrap to btngroups
        $(btnwrap).toggleClass('btn-wrap btn-groups');
		
        // Move btngroups to wooentryimage
        var btngroups = $(item).find('li.btn-groups');
        $(btngroups).appendTo($(wooentryimage));
        // Clone to details
        $(btngroups).clone().appendTo(details);
        $(addtocart).clone().appendTo(details);
	});

	// For single product page
	if($('body.single-product').length) {
		var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');
		var compare  = $('body.single-product div.entry-summary').find('a.compare');
		var formcart = $('body.single-product div.entry-summary').find('form.cart');
		
		// Add wrapper for the Compare
		$(compare).wrap('<div class="btn-qwc-wrap btn-compare"></div>');
		compare = $('body.single-product div.entry-summary').find('div.btn-qwc-wrap.btn-compare');
		
		$('<div class="btn-groups"></div>').appendTo($(formcart));
		var btngroups = $('body.single-product div.entry-summary').find('.btn-groups');
		
		// Add class for button Wishlist
		$(wishlist).addClass('btn-qwc-wrap btn-wishlist');
		
		$(wishlist).appendTo($(btngroups));
		$(compare).appendTo($(btngroups));
	}
	
	// After added to cart
	$(document.body).on('added_to_cart', function(){
		$('.btn-addtocart .added_to_cart').each(function(index, item) {
			var txtAdded = $(item).text();
			$(item).next().text(txtAdded);
		});
	});
    
    
    $('ul.products.grid li.product .btn-addtocart a').each(function(index, item) {
		var atctext = $(item).html();
		$(item).attr("data-hover", atctext);
	});
});