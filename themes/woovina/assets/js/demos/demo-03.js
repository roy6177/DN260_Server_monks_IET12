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
		$(quickview).wrap('<div class="btn-qwc btn-quickview"></div>');
		$(wishlist).wrap('<div class="btn-qwc btn-wishlist"></div>');
		$(compare).wrap('<div class="btn-qwc btn-compare"></div>');
        
        // Move quickview, wishlist, compare to btnwrap
        quickview = $(item).find('div.btn-qwc.btn-quickview');
        wishlist = $(item).find('div.btn-qwc.btn-wishlist');
        compare = $(item).find('div.btn-qwc.btn-compare');
        
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
        $(addtocart).clone().appendTo(details);
        $(btngroups).clone().appendTo(details);
        
	});
    
	
	// For single product page
	if($('body.single-product').length) {
		var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');
		var compare  = $('body.single-product div.entry-summary').find('a.compare');
		var formcart = $('body.single-product div.entry-summary').find('form.cart');
		
		// Add wrapper for the Compare
		$(compare).wrap('<div class="btn-wc-single btn-compare"></div>');
		compare = $('body.single-product div.entry-summary').find('div.btn-wc-single.btn-compare');
		
		$('<div class="btn-groups-single"></div>').appendTo($(formcart));
		var btngroupssingle = $('body.single-product div.entry-summary').find('.btn-groups-single');
		
		// Add class for button Wishlist
		$(wishlist).addClass('btn-wc-single btn-wishlist');
		
		$(wishlist).appendTo($(btngroupssingle));
		$(compare).appendTo($(btngroupssingle));
	}
	
	// After added to cart
	$(document.body).on('added_to_cart', function(){
		$('.btn-addtocart .added_to_cart').each(function(index, item) {
			var txtAdded = $(item).text();
			$(item).next().text(txtAdded);
		});
        
	});
    $(document).on("ready", function () {
    $('body').delegate('.elementor-menu-cart__close-button', 'click', function(e){
            e.preventDefault();
            $('body').removeClass('show-cart');
            $('.wvn-cart-overlay').hide();
        })
    });
    
    
    
    $(document).on("ready", function () {
        $(document).on('click', '.elementor-tabs-wrapper', function () {
            $(this).addClass('open-show');
        }).on('click', '.elementor-tab-title', function (e) {
            e.stopPropagation();
            $(this).closest('.elementor-tabs-wrapper').removeClass('open-show');
        });
    });
    
});